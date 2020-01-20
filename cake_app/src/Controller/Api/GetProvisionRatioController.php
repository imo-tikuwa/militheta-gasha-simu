<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * 提供割合を返すコントローラ
 *
 * @property \App\Model\Table\GashasTable $Gashas
 * @property \App\Model\Table\CardsTable $Cards
 *
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GetProvisionRatioController extends AppController
{

	/**
	 * Initialize Method.
	 */
	public function initialize()
	{
		parent::initialize();
		$this->Gashas = TableRegistry::getTableLocator()->get("Gashas");
		$this->Cards = TableRegistry::getTableLocator()->get("Cards");

		$this->rarity_codes = _code("Cards.rarity");
		$this->type_codes = _code("Cards.type");
	}

	/**
	 * 提供割合情報を返す
	 *
	 * @param string $gasha_id
	 */
	public function index($gasha_id = null) {

		$this->viewBuilder()->enableAutoLayout(false);
		$this->autoRender = false;
		$this->response->withCharset('UTF-8');
		$this->response->withType('json');

		if (empty($gasha_id)) {
			echo json_encode([]);
			exit;
		}

		// ガシャ情報取得
		$gasha = $this->Gashas->get($gasha_id);

		// カード情報取得
		$cards = $this->Cards->findGashaTargetCards($gasha);

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
		$per_ssr_pick_rate = floor_plus(($ssr_rate - ($gasha->ssr_pickup_rate * $ssr_pickup_target_count)) / (count($cards['04']) - $ssr_pickup_target_count), 3);
		$per_sr_pick_rate = floor_plus(($sr_rate - ($gasha->sr_pickup_rate * $sr_pickup_target_count)) / (count($cards['03']) - $sr_pickup_target_count), 3);
		$per_r_pick_rate = floor_plus(($r_rate - ($gasha->r_pickup_rate * $r_pickup_target_count)) / (count($cards['02']) - $r_pickup_target_count), 3);

		// 結果を返す
		$response = [];
		foreach (array_reverse($this->rarity_codes) as $rarity_code => $rarity_text) {
			$response[$rarity_text] = [];
			if (!array_key_exists($rarity_code, $cards)) {
				continue;
			}
			foreach ($cards[$rarity_code] as $card) {
				$card_info = [
						'name' => $card['name'],
						'type' => $this->type_codes[$card['type']],
				];
				switch ($rarity_code) {
					case '02':
						$card_info['rate'] = $per_r_pick_rate;
						break;
					case '03':
						$card_info['rate'] = $per_sr_pick_rate;
						break;
					case '04':
						$card_info['rate'] = $per_ssr_pick_rate;
						break;
				}
				$response[$rarity_text][] = $card_info;
			}
		}

		$this->response->body(json_encode($response, JSON_UNESCAPED_UNICODE));
		return;
	}

}
