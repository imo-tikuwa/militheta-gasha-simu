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

		// TODO ピックアップ対象カードはとりあえずゼロとする
		$ssr_pickup_target_count = 0;
		$sr_pickup_target_count = 0;
		$r_pickup_target_count = 0;

		// 非ピックアップ1枚当たりのピック確率を計算する
		// SSR、SRは最初にピックアップの確率で減算し、残りの枚数で分け合う
		$per_ssr_pick_rate = floor_plus(($ssr_rate - ($gasha->ssr_pickup_rate * $ssr_pickup_target_count)) / (count($cards['SSR']) - $ssr_pickup_target_count), 3);
		$per_sr_pick_rate = floor_plus(($sr_rate - ($gasha->sr_pickup_rate * $sr_pickup_target_count)) / (count($cards['SR']) - $sr_pickup_target_count), 3);
		$per_r_pick_rate = 0;
		if (isset($cards['R'])) {
			$per_r_pick_rate = floor_plus(($r_rate - ($gasha->r_pickup_rate * $r_pickup_target_count)) / (count($cards['R']) - $r_pickup_target_count), 3);
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
			foreach ($cards[$rarity_text] as $card) {
				$card_info = [
						'id' => $card['id'],
						'name' => $card['name'],
						'type' => $card['type'],
				];
				switch ($rarity_text) {
					case 'R':
						$card_info['rate'] = $per_r_pick_rate;
						break;
					case 'SR':
						$card_info['rate'] = $per_sr_pick_rate;
						break;
					case 'SSR':
						$card_info['rate'] = $per_ssr_pick_rate;
						break;
				}
				$provition_ratios[$rarity_text][] = $card_info;
			}
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