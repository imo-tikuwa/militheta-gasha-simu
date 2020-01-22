<?php
namespace App\Utils;

use App\Model\Entity\Gasha;
use Cake\Utility\Hash;

class GashaUtils {

	/**
	 * 提供割合を返す
	 * @param Gasha $gasha
	 * @param unknown $cards
	 * @return unknown[]
	 */
	public static function getProvisionRatio(Gasha $gasha, $cards) {

		// ガシャ情報からSSRとSRのレートを取得、Rのレートを計算
		$ssr_rate = $gasha->ssr_rate;
		$sr_rate = $gasha->sr_rate;
		$r_rate = 100 - $ssr_rate - $sr_rate;

		// ピックアップ対象カードのカウントアップ
		$ssr_pickup_target_count = 0;
		$sr_pickup_target_count = 0;
		$r_pickup_target_count = 0;
		foreach ($cards as $rarity => $per_rarity_cards) {
			foreach ($per_rarity_cards as $card) {
				if ($card['pickup'] === true) {
					switch ($card['rarity']) {
						case 'SSR':
							$ssr_pickup_target_count++;
							break;
						case 'SR':
							$sr_pickup_target_count++;
							break;
						case 'R':
							$r_pickup_target_count++;
							break;
					}
				}
			}
		}

		// ピックアップカード1枚当たりのピック率
		$per_ssr_pickup_rate = ($ssr_pickup_target_count > 0) ? @(BASE_SSR_PICKUP_RATE / $ssr_pickup_target_count) : 0;
		$per_sr_pickup_rate = ($sr_pickup_target_count > 0) ? @(BASE_SR_PICKUP_RATE / $sr_pickup_target_count) : 0;
		$per_r_pickup_rate = ($r_pickup_target_count > 0) ? (BASE_R_PICKUP_RATE / $r_pickup_target_count) : 0;

		// 非ピックアップ1枚当たりのピック確率を計算する
		// SSR、SRは最初にピックアップの確率で減算し、残りの枚数で分け合う
		$per_ssr_rate = floor_plus(($ssr_rate - ($per_ssr_pickup_rate * $ssr_pickup_target_count)) / (count($cards['SSR']) - $ssr_pickup_target_count), 3);
		$per_sr_rate = floor_plus(($sr_rate - ($per_sr_pickup_rate * $sr_pickup_target_count)) / (count($cards['SR']) - $sr_pickup_target_count), 3);
		$per_r_rate = 0;
		if (isset($cards['R'])) {
			$per_r_rate = floor_plus(($r_rate - ($per_r_pickup_rate * $r_pickup_target_count)) / (count($cards['R']) - $r_pickup_target_count), 3);
		}

		// 結果を返す
		$provition_ratios = [];
		foreach (array_reverse(_code("Cards.rarity")) as $rarity_code => $rarity_text) {

			// Nカードはガシャ対象ではないのでスキップ
			if ($rarity_text == 'N') {
				continue;
			}

			$provition_ratios[$rarity_text] = [];
			if (!array_key_exists($rarity_text, $cards)) {
				continue;
			}
			$tmp_cards = [];
			foreach ($cards[$rarity_text] as $card) {
				$card_info = [
						'id' => $card['id'],
						'name' => $card['name'],
						'type' => $card['type'],
				];
				switch ($rarity_text) {
					case 'R':
						if ($card['pickup'] === true) {
							$card_info['rate'] = $per_r_pickup_rate;
						} else {
							$card_info['rate'] = $per_r_rate;
						}
						break;
					case 'SR':
						if ($card['pickup'] === true) {
							$card_info['rate'] = $per_sr_pickup_rate;
						} else {
							$card_info['rate'] = $per_sr_rate;
						}
						break;
					case 'SSR':
						if ($card['pickup'] === true) {
							$card_info['rate'] = $per_ssr_pickup_rate;
						} else {
							$card_info['rate'] = $per_ssr_rate;
						}
						break;
				}
				$tmp_cards[] = $card_info;
			}

			// 確率の高いピックアップカードが上に来るようソートする
			usort($tmp_cards, function ($a, $b) {
				return $a['rate'] < $b['rate'];
			});

			$provition_ratios[$rarity_text] = $tmp_cards;
		}

		return $provition_ratios;
	}

	/**
	 * 抽選で使用する重み付けデータを作成
	 *
	 * ガシャページによると小数点4桁で切り捨てしているとのこと
	 * とりあえず1000倍して小数点以下の数字をなくす
	 *
	 * @param array $provision_ratios 提供割合のデータ
	 * @return number Key:カードID、Value:レート の配列
	 */
	public static function createWeightData($provision_ratios) {

		$card_id_and_rates = Hash::combine($provision_ratios, '{n}.id', '{n}.rate');
		foreach ($card_id_and_rates as $card_id => $rate) {
			$card_id_and_rates[$card_id] = $rate * 1000;
		}

		return $card_id_and_rates;
	}
}