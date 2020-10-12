<?php
namespace App\Controller\Api;

use App\Model\Entity\Gasha;
use App\Utils\GashaUtils;
use Cake\Core\Exception\Exception;
use Cake\Event\EventInterface;

/**
 * ガシャ結果を返すコントローラクラス
 *
 * @property \App\Model\Table\GashasTable $Gashas
 * @property \App\Model\Table\CardsTable $Cards
 *
 */
class PickGashaController extends ApiController
{

    /**
     * ガシャ情報
     * @var Gasha
     */
    private $gasha;

    /**
     * カード情報
     * @var array
     */
    private $cards;

    /**
     * 提供割合
     * @var unknown
     */
    private $provision_ratios;

    /**
     * 重み付けデータ
     * @var array
     */
    private $cards_rate_data;

    /**
     * 共通処理内でガシャに必要なデータを取得
     * {@inheritDoc}
     * @param EventInterface $event
     * @see \Cake\Controller\Controller::beforeFilter()
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        try {
            // ガシャID
            $gasha_id = @$this->getRequest()->getQuery('gasha_id');

            if (is_null($gasha_id) || !is_numeric($gasha_id)) {
                throw new Exception('gasha_id is invalid.');
            }

            // ガシャ情報取得
            $this->gasha = $this->Gashas->get($gasha_id);

            // カード情報取得
            $this->cards = $this->Cards->findGashaTargetCards($this->gasha);

            // 提供割合を取得
            $this->provision_ratios = GashaUtils::getProvisionRatio($this->gasha, $this->cards);

            // 重み付けデータ作成
            $this->cards_rate_data = GashaUtils::createWeightData(array_merge($this->provision_ratios['SSR'], $this->provision_ratios['SR'], $this->provision_ratios['R']));
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            $this->log($error_message);
            return $this->response->withStatus(500, $error_message)->withStringBody(null);
        }
    }

    /**
     * 10連ガシャ
     * @return static
     */
    public function jyuren()
    {
        // ガシャを9回引く
        $card_ids = [];
        for ($i = 0; $i < 9; $i++) {
            $card_ids[] = $this->_pick($this->cards_rate_data);
        }

        // SR以上確定ガシャ(Rカードをピック対象から消去、提供割合を再取得、重み付けデータを再取得して1回ガシャを引く)
        unset($this->cards['R']);
        $this->gasha->sr_rate = 100 - $this->gasha->ssr_rate;
        $this->provision_ratios = GashaUtils::getProvisionRatio($this->gasha, $this->cards);
        $this->cards_rate_data = GashaUtils::createWeightData(array_merge($this->provision_ratios['SSR'], $this->provision_ratios['SR']));
        $card_ids[] = $this->_pick($this->cards_rate_data);

        // レスポンスにセットするカード情報を取得
        $results = $this->Cards->findByIds($card_ids);

        return $this->response->withType('json')->withStringBody(json_encode($results, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 単発ガシャ
     * @return static
     */
    public function tanpatsu()
    {
        // ガシャを1回引く
        $card_ids[] = $this->_pick($this->cards_rate_data);

        // レスポンスにセットするカード情報を取得
        $results = $this->Cards->findByIds($card_ids);

        return $this->response->withType('json')->withStringBody(json_encode($results, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 重み付けの抽選処理
     *
     * ガシャを1回引く
     * @param array $entries 重み付けデータ
     * @return string|int $card_id カードID
     */
    private function _pick($entries)
    {
        $sum  = array_sum($entries);
        $rand = rand(1, $sum);

        foreach ($entries as $card_id => $rate) {
            if (($sum -= $rate) < $rand) {
                return $card_id;
            }
        }
    }
}
