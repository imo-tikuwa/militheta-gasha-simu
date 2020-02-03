<?php
namespace App\Controller\Api;

use App\Utils\GashaUtils;
use Cake\Event\Event;
use Cake\Core\Exception\Exception;

/**
 * ガシャ結果を返すコントローラクラス
 *
 * @property \App\Model\Table\GashasTable $Gashas
 * @property \App\Model\Table\CardsTable $Cards
 *
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TargetPickGashaController extends ApiController
{

	/**
	 * ガシャ情報
	 * @var unknown
	 */
	private $gasha;

	/**
	 * カード情報
	 * @var unknown
	 */
	private $cards;

	/**
	 * 提供割合
	 * @var unknown
	 */
	private $provision_ratios;

	/**
	 * 1～9連目の重み付けデータ
	 * @var unknown
	 */
	private $cards_rate_data;

	/**
	 * ピックしたいカードのID配列
	 * @var unknown
	 */
	private $target_card_ids;

	/**
	 * 共通処理内でガシャに必要なデータを取得
	 * {@inheritDoc}
	 * @see \Cake\Controller\Controller::beforeFilter()
	 */
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);

		// リクエストタイムアウトの時間を5分に設定
		set_time_limit(300);

		try {
			// getDataでパラメータ取れないので別の方法で取得？
			$request_data = json_decode(file_get_contents('php://input'), true);

			// ガシャID
			$gasha_id = @$request_data['gasha_id'];

			// ターゲットカードID
			$this->target_card_ids = @$request_data['target_card_ids'];

			if (is_null($gasha_id) || !is_numeric($gasha_id)) {
				throw new Exception('gasha_id is invalid.');
			} else if (is_null($this->target_card_ids) || empty($this->target_card_ids)) {
				throw new Exception('target_card_ids is invalid.');
			}

			// ガシャ情報取得
			$this->gasha = $this->Gashas->get($gasha_id);

			// カード情報取得
			$this->cards = $this->Cards->findGashaTargetCards($this->gasha);

			// 提供割合を取得
			$this->provision_ratios = GashaUtils::getProvisionRatio($this->gasha, $this->cards);

			// 1～9連目の重み付けデータ作成
			$this->cards_rate_data = GashaUtils::createWeightData(array_merge($this->provision_ratios['SSR'], $this->provision_ratios['SR'], $this->provision_ratios['R']));

		} catch (\Exception $e) {

			$error_message = $e->getMessage();
			$this->log($error_message);
			return $this->response->withStatus(500, $error_message)->withStringBody(null);
		}
	}

	/**
	 * ピックしたいカードがそろうまで10連ガシャを引き続ける
	 */
	public function jyuren()
	{
		// 10連目(SR以上確定)の重み付けデータ作成
		unset($this->cards['R']);
		$this->gasha->sr_rate = 100 - $this->gasha->ssr_rate;
		$this->provision_ratios = GashaUtils::getProvisionRatio($this->gasha, $this->cards);
		$ssrsr_cards_rate_data = GashaUtils::createWeightData(array_merge($this->provision_ratios['SSR'], $this->provision_ratios['SR']));

		$card_ids = [];
		while (true) {

			// ガシャを9回引く
			$pick_ids = [];
			for ($i = 0; $i < 9; $i++) {
				$pick_ids[] = $this->_pick($this->cards_rate_data);
			}

			// SR以上確定ガシャ
			$pick_ids[] = $this->_pick($ssrsr_cards_rate_data);

			// 10枚のカードIDをマージ
			$card_ids = array_merge($card_ids, $pick_ids);

			// ピックしたいカードのID配列が空になったらガシャから解放する
			$this->target_card_ids = array_diff($this->target_card_ids, $pick_ids);
			if (empty($this->target_card_ids)) {
				break;
			}
			// ガシャ上限に到達したら終了
			else if (count($card_ids) >= TARGET_PICK_ALLOW_MAX_NUM) {
				break;
			}
		}

		// レスポンスにセットするカード情報を取得
		$results = $this->Cards->findByIds($card_ids);

		return $this->response->withStringBody(json_encode($results, JSON_UNESCAPED_UNICODE));
	}

	/**
	 * ピックしたいカードがそろうまで単発ガシャを引き続ける
	 */
	public function tanpatsu()
	{
		$card_ids = [];
		while (true) {

			// ガシャを1回引く
			$pick_id = $this->_pick($this->cards_rate_data);
			$card_ids[] = $pick_id;

			// ピックしたいカードのID配列が空になったらガシャから解放する
			$this->target_card_ids = array_diff($this->target_card_ids, [$pick_id]);
			if (empty($this->target_card_ids)) {
				break;
			}
			// ガシャ上限に到達したら終了
			else if (count($card_ids) >= TARGET_PICK_ALLOW_MAX_NUM) {
				break;
			}
		}

		// レスポンスにセットするカード情報を取得
		$results = $this->Cards->findByIds($card_ids);

		return $this->response->withStringBody(json_encode($results, JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 重み付けの抽選処理
	 *
	 * ガシャを1回引く
	 * @param array $entries
	 * @return integer $card_id
	 */
	private function _pick($entries)
	{

		$sum  = array_sum($entries);
		$rand = rand(1, $sum);

		foreach($entries as $card_id => $rate){
			if (($sum -= $rate) < $rand) {
				return $card_id;
			}
		}
	}
}
