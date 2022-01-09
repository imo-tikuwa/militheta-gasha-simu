<?php
namespace App\Utils;

use App\Model\Entity\Gasha;
use Cake\Utility\Hash;

class GashaUtils
{

    /**
     * 提供割合を返す
     * @param Gasha $gasha ガシャ情報
     * @param array $cards カード情報
     * @return array
     */
    public static function getProvisionRatio(Gasha $gasha, $cards)
    {
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

        // ピックアップカードのピック率
        // フェスのときはベースの値が2倍になる？2020-03-29のミリオンフェスで新規フェス限が0.99%ずつとなってることを確認、とりあえずの修正を実施
        // 2021-09-17現在、SHS限定ガシャは2枚ずつピックアップとなってて、1枚辺りのピックアップ確率が0.899%となっている模様
        if ($gasha->isFesLimited()) {
            $base_ssr_pickup_rate = BASE_SSR_PICKUP_RATE * 2;
        } elseif ($gasha->isShsLimited()) {
            $base_ssr_pickup_rate = SHS_LIMITED_SSR_PER_PICKUP_RATE * $ssr_pickup_target_count;
        } else {
            $base_ssr_pickup_rate = BASE_SSR_PICKUP_RATE;
        }
        $per_ssr_pickup_rate = ($ssr_pickup_target_count > 0) ? @($base_ssr_pickup_rate / $ssr_pickup_target_count) : 0;
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
        foreach (array_reverse(_code("Codes.Cards.rarity")) as $rarity_code => $rarity_text) {
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

            // ソートする
            usort($tmp_cards, function ($a, $b) {

                // ピックアップが上に来るようにする
                // @phpstan-ignore-next-line
                if ($a['rate'] < $b['rate']) {
                    return 1;
                } elseif ($a['rate'] > $b['rate']) {
                    return -1;
                }

                // Princess、Fairy、Angelの順とする
                if ($a['type'] === 'Princess' && $b['type'] !== 'Princess') {
                    return -1;
                } elseif ($a['type'] !== 'Princess' && $b['type'] === 'Princess') {
                    return 1;
                }
                if ($a['type'] === 'Fairy' && $b['type'] !== 'Fairy') {
                    return -1;
                } elseif ($a['type'] !== 'Fairy' && $b['type'] === 'Fairy') {
                    return 1;
                }
                if ($a['type'] === 'Angel' && $b['type'] !== 'Angel') {
                    return -1;
                } elseif ($a['type'] !== 'Angel' && $b['type'] === 'Angel') {
                    return 1;
                }

                // IDの昇順とする
                if ($a['id'] < $b['id']) {
                    return -1;
                } elseif ($a['id'] > $b['id']) {
                    return 1;
                }

                return 0;
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
     * @return array Key:カードID、Value:レート の配列
     */
    public static function createWeightData($provision_ratios)
    {
        $card_id_and_rates = Hash::combine($provision_ratios, '{n}.id', '{n}.rate');
        foreach ($card_id_and_rates as $card_id => $rate) {
            $card_id_and_rates[$card_id] = $rate * 1000;
        }

        return $card_id_and_rates;
    }
}
