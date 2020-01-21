<?php
namespace App\Controller\Api;

use App\Utils\GashaUtils;

/**
 * ガシャ結果を返すコントローラクラス
 *
 * @property \App\Model\Table\GashasTable $Gashas
 * @property \App\Model\Table\CardsTable $Cards
 *
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PickGashaController extends ApiController
{

	/**
	 * 10連ガシャ
	 *
	 * @param string $gasha_id ガシャID
	 */
	public function index($gasha_id) {

		// ガシャ情報取得
		$gasha = $this->Gashas->get($gasha_id);

		// カード情報取得
		$cards = $this->Cards->findGashaTargetCards($gasha);

		// 提供割合を取得
		$provision_ratios = GashaUtils::getProvisionRatio($gasha, $cards);

		// 重み付けデータ作成
		$cards_rate_data = GashaUtils::createWeightData(array_merge($provision_ratios['SSR'], $provision_ratios['SR'], $provision_ratios['R']));

		// ガシャを9回引く
		$card_ids = [];
		for ($i = 0; $i < 9; $i++) {
			$card_ids[] = $this->_pick($cards_rate_data);
		}

		// SR以上確定ガシャ(Rカードをピック対象から消去、提供割合を再取得、重み付けデータを再取得して1回ガシャを引く)
		unset($cards['R']);
		$gasha->sr_rate = 100 - $gasha->ssr_rate;
		$provision_ratios = GashaUtils::getProvisionRatio($gasha, $cards);
		$cards_rate_data = GashaUtils::createWeightData(array_merge($provision_ratios['SSR'], $provision_ratios['SR']));
		$card_ids[] = $this->_pick($cards_rate_data);

		// レスポンスにセットするカード情報を取得
		$this->log($card_ids, 'debug');
		$results = $this->Cards->findByIds($card_ids);

		$this->response->body(json_encode($results, JSON_UNESCAPED_UNICODE));
		return;
	}

	/**
	 * 重み付けの抽選処理
	 *
	 * ガシャを1回引く
	 * @param array $entries
	 * @return integer $card_id
	 */
	private function _pick($entries) {

		$sum  = array_sum($entries);
		$rand = rand(1, $sum);

		foreach($entries as $card_id => $rate){
			if (($sum -= $rate) < $rand) {
				return $card_id;
			}
		}
	}
}
