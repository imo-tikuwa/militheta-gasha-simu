<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * ガシャ処理を詰め込んだコントローラクラス
 *
 * @property \App\Model\Table\CardsTable $Cards
 *
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GashaController extends AppController
{

	// TODO
	// 単発 ←〇
	// 10連(10連目はSR以上確定) ←〇
	// フェス ←〇
	// タイプガシャ
	// ピックアップ
	// 最初にレアリティ抽選 ←〇
	// 次にレアリティで絞り込んだカード抽選 ←〇
	// その他、限定を除外など

	/**
	 * レアリティ設定
	 * @var array
	 */
	private $rarity_config = [
			"normal" => [
					"ssr" => 3,
					"sr" => 12,
					"r" => 85
			],
			"normal_sr_kakutei" => [
					"ssr" => 3,
					"sr" => 97,
					"r" => 0
			],
			"fes" => [
					"ssr" => 6,
					"sr" => 12,
					"r" => 82
			],
			"fes_sr_kakutei" => [
					"ssr" => 6,
					"sr" => 94,
					"r" => 0
			],
	];

	private $rarity_codes;

	/**
	 * Initialize Method.
	 */
	public function initialize()
	{
		parent::initialize();
		$this->Cards = TableRegistry::getTableLocator()->get("Cards");

		// レアリティのコード値とコード名を反転させた配列。検索に使う
		$this->rarity_codes = array_flip(_code('Cards.rarity'));
	}

	/**
	 * 単発ガシャ
	 *
	 * @param string $gasha_type
	 */
	public function tanpatu($gasha_type = 'normal') {

		$cards[] = $this->_pick($gasha_type);

		$this->viewBuilder()->enableAutoLayout(false);
		$this->autoRender = false;
		echo json_encode($cards);
		return;
	}

	/**
	 * 10連ガシャ
	 * 10連目はSR確定ガシャ
	 *
	 * @param string $gasha_type
	 */
	public function jyuren($gasha_type = 'normal') {

		$cards = [];
		for ($i = 0; $i < 9; $i++) {
			$cards[] = $this->_pick($gasha_type);
		}
		$cards[] = $this->_pick($gasha_type . "_sr_kakutei");

		$this->viewBuilder()->enableAutoLayout(false);
		$this->autoRender = false;
		echo json_encode($cards);
		return;
	}

	/**
	 * ガシャを1回引く
	 * @param string $gasha_type ガシャタイプ（SR確定：sr_kakutei、フェス：fes）
	 */
	private function _pick($gasha_type = 'normal') {

		// レアリティ設定
		$rarity_data = $this->rarity_config[$gasha_type];
		$ssr_rate = $rarity_data['ssr'];
		$sr_rate = $rarity_data['sr'];
		$r_rate = $rarity_data['r'];

		// レアリティ抽選
		$rarity = rand(1, 100);
		if ($rarity <= $ssr_rate) {
			$pick_rarity = 'SSR';
		} else if ($rarity <= $sr_rate) {
			$pick_rarity = 'SR';
		} else {
			$pick_rarity = 'R';
		}
		$rarity_code = $this->rarity_codes[$pick_rarity];

		// mysqlのrand()でランダム抽選
		$card = $this->Cards->find()->where(['rarity' => $rarity_code])->order('rand()')->first();
		return $card;
	}
}
