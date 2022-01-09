<?php
namespace App\Controller\Api;

use App\Utils\GashaUtils;

/**
 * 提供割合を返すコントローラクラス
 *
 * @property \App\Model\Table\GashasTable $Gashas
 * @property \App\Model\Table\CardsTable $Cards
 *
 */
class GetProvisionRatioController extends ApiController
{

    /**
     * 提供割合情報を返す
     *
     * @param string $gasha_id ガシャID
     * @return \Cake\Http\Response
     */
    public function index($gasha_id = null)
    {
        if (is_null($gasha_id)) {
            return $this->response->withStringBody(null);
        }

        // ガシャ情報取得
        $gasha = $this->Gashas->get($gasha_id);

        // カード情報取得
        $cards = $this->Cards->findGashaTargetCards($gasha);

        // 提供割合を取得
        $provision_ratios = GashaUtils::getProvisionRatio($gasha, $cards);

        $string = json_encode($provision_ratios, JSON_UNESCAPED_UNICODE);
        assert($string !== false);
        return $this->response->withType('json')->withStringBody($string);
    }
}
