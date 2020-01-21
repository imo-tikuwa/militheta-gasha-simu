<?php
namespace App\Controller\Api;

use App\Utils\GashaUtils;

/**
 * 提供割合を返すコントローラクラス
 *
 * @property \App\Model\Table\GashasTable $Gashas
 * @property \App\Model\Table\CardsTable $Cards
 *
 * @method \App\Model\Entity\Card[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GetProvisionRatioController extends ApiController
{

	/**
	 * 提供割合情報を返す
	 *
	 * @param string $gasha_id
	 */
	public function index($gasha_id = null) {


		if (empty($gasha_id)) {
			echo json_encode([]);
			exit;
		}

		// ガシャ情報取得
		$gasha = $this->Gashas->get($gasha_id);

		// カード情報取得
		$cards = $this->Cards->findGashaTargetCards($gasha);

		// 提供割合を取得
		$provision_ratios = GashaUtils::getProvisionRatio($gasha, $cards);

		$this->response->body(json_encode($provision_ratios, JSON_UNESCAPED_UNICODE));
		return;
	}

}
