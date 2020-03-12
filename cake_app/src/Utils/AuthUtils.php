<?php
namespace App\Utils;

use Cake\Http\ServerRequest;
use Cake\Log\LogTrait;

class AuthUtils {

    use LogTrait;

    /**
     * Adminsテーブルのスーパーユーザー判定
     * @param ServerRequest $request
     * @return boolean
     */
    public static function isSuperUser(ServerRequest $request = null)
    {
        if (is_null($request)) {
            return false;
        }
        $super_user = false;
        try {
            $super_user = (SUPER_USER_ID === $request->getSession()->read('Auth.Admin.id'));
        } catch (\Exception $e) {
            $this->log($e->getMessage());
        }
        return $super_user;
    }

    /**
     *
     * リクエスト先へのアクセスに必要な権限を持っているかチェック
     *
     * @param ServerRequest $request
     * @param string $properties 権限チェックプロパティ $requestオブジェクト以外の権限チェックをしたいとき
     *                           'controller' => '[コントローラ名]',
     *                           'action' => '[アクション名]'
     *                           を配列でセットする
     *
     * @return boolean
     */
    public static function hasRole(ServerRequest $request = null, array $properties = [])
    {
        if (is_null($request)) {
            return false;
        }

        // スーパーユーザーは全機能にアクセス可
        if (self::isSuperUser($request)) {
            return true;
        }

        // チェック対象のコントローラとアクションの設定
        $controller = $request->getParam('controller');
        if (isset($properties['controller'])) {
            $controller = $properties['controller'];
        }
        $action = $request->getParam('action');
        if (isset($properties['action'])) {
            $action = $properties['action'];
        }

        $privilegs = $request->getSession()->read('Auth.Admin.privilege.'.$controller);
        if (empty($privilegs)) {
            return false;
        }

        switch ($action) {
            case ACTION_INDEX:
            case ACTION_VIEW:
                $has_role = in_array(ROLE_READ, $privilegs, true);
                break;
            case ACTION_ADD:
            case ACTION_EDIT:
                $has_role = in_array(ROLE_WRITE, $privilegs, true);
                break;
            case ACTION_DELETE:
                $has_role = in_array(ROLE_DELETE, $privilegs, true);
                break;
            case ACTION_CSV_EXPORT:
                $has_role = in_array(ROLE_CSV_EXPORT, $privilegs, true);
                break;
            case ACTION_CSV_IMPORT:
                $has_role = in_array(ROLE_CSV_IMPORT, $privilegs, true);
                break;
                // 上記以外のアクションは一律アクセス可能
            default:
                $has_role = true;
        }

        return $has_role;
    }
}