<?php
namespace App\Controller\Api;

use App\Utils\GashaUtils;
use Cake\Core\Exception\CakeException;
use Cake\Event\EventInterface;

/**
 * ガシャ結果を返すコントローラクラス
 *
 * @property \App\Model\Table\GashasTable $Gashas
 * @property \App\Model\Table\CardsTable $Cards
 *
 */
class TargetPickGashaController extends ApiController
{
    /**
     * 1～9連目の重み付けデータ
     * @var array
     */
    private $cards_rate_data;

    /**
     * 10連目の重み付けデータ
     * @var array
     */
    private $ssrsr_cards_rate_data;

    /**
     * ピックしたいカードのID配列
     * @var array
     */
    private $target_card_ids;

    /**
     * 共通処理内でガシャに必要なデータを取得
     *
     * @param EventInterface $event EventInterface
     * @see \Cake\Controller\Controller::beforeFilter()
     * @return \Cake\Http\Response|void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // リクエストタイムアウトの時間を5分に設定
        set_time_limit(300);

        try {
            $request_data = $this->getRequest()->getData();

            // ガシャID
            $gasha_id = @$request_data['gasha_id'];

            // ターゲットカードID
            $this->target_card_ids = @$request_data['target_card_ids'];

            if (is_null($gasha_id) || !is_numeric($gasha_id)) {
                throw new CakeException('gasha_id is invalid.');
            } elseif (is_null($this->target_card_ids) || empty($this->target_card_ids)) {
                throw new CakeException('target_card_ids is invalid.');
            }

            // ガシャ情報取得
            $gasha = $this->Gashas->get($gasha_id);

            // カード情報取得
            $cards = $this->Cards->findGashaTargetCards($gasha);

            // 提供割合を取得
            $provision_ratios = GashaUtils::getProvisionRatio($gasha, $cards);

            // 1～9連目の重み付けデータ作成
            $this->cards_rate_data = GashaUtils::createWeightData(array_merge($provision_ratios['SSR'], $provision_ratios['SR'], $provision_ratios['R']));

            // 10連目(SR以上確定)の重み付けデータ作成
            if ($this->getRequest()->getParam('action') === 'jyuren') {
                unset($cards['R']);
                $gasha->sr_rate = 100 - $gasha->ssr_rate;
                $provision_ratios = GashaUtils::getProvisionRatio($gasha, $cards);
                $this->ssrsr_cards_rate_data = GashaUtils::createWeightData(array_merge($provision_ratios['SSR'], $provision_ratios['SR']));
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            $this->log($error_message);
            return $this->response->withStatus(500, $error_message)->withStringBody(null);
        }
    }

    /**
     * ピックしたいカードがそろうまで10連ガシャを引き続ける
     * @return \Cake\Http\Response
     */
    public function jyuren()
    {
        $card_ids = [];
        while (true) {
            // ガシャを9回引く
            $pick_ids = [];
            for ($i = 0; $i < 9; $i++) {
                $pick_ids[] = $this->_pick($this->cards_rate_data);
            }

            // SR以上確定ガシャ
            $pick_ids[] = $this->_pick($this->ssrsr_cards_rate_data);

            // 10枚のカードIDをマージ
            $card_ids = array_merge($card_ids, $pick_ids);

            $this->target_card_ids = array_diff($this->target_card_ids, $pick_ids);
            if (empty($this->target_card_ids)) {
                // ピックしたいカードのID配列が空になったらガシャから解放する
                break;
            } elseif (count($card_ids) >= TARGET_PICK_ALLOW_MAX_NUM) {
                // ガシャ上限に到達したら終了
                break;
            }
        }

        // レスポンスにセットするカード情報を取得
        $results = $this->Cards->findByIds($card_ids);

        $string = json_encode($results, JSON_UNESCAPED_UNICODE);
        assert($string !== false);
        return $this->response->withType('json')->withStringBody($string);
    }

    /**
     * ピックしたいカードがそろうまで単発ガシャを引き続ける
     * @return \Cake\Http\Response
     */
    public function tanpatsu()
    {
        $card_ids = [];
        while (true) {
            // ガシャを1回引く
            $pick_id = $this->_pick($this->cards_rate_data);
            $card_ids[] = $pick_id;

            $this->target_card_ids = array_diff($this->target_card_ids, [$pick_id]);
            if (empty($this->target_card_ids)) {
                // ピックしたいカードのID配列が空になったらガシャから解放する
                break;
            } elseif (count($card_ids) >= TARGET_PICK_ALLOW_MAX_NUM) {
                // ガシャ上限に到達したら終了
                break;
            }
        }

        // レスポンスにセットするカード情報を取得
        $results = $this->Cards->findByIds($card_ids);

        $string = json_encode($results, JSON_UNESCAPED_UNICODE);
        assert($string !== false);
        return $this->response->withType('json')->withStringBody($string);
    }

    /**
     * 重み付けの抽選処理
     *
     * ガシャを1回引く
     * @param array $entries 重み付けデータ
     * @return int $card_id カードID
     */
    private function _pick($entries)
    {
        $sum  = (int)array_sum($entries);
        $rand = rand(1, $sum);

        /** @var int $card_id */
        foreach ($entries as $card_id => $rate) { // @phpstan-ignore-line
            if (($sum -= $rate) < $rand) {
                return $card_id;
            }
        }
    }
}
