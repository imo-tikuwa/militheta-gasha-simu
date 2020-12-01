<?php
namespace App\Utils;

use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Exception;
use Psr\Log\LogLevel;

class AuthUtils
{

    /**
     * Adminsテーブルのスーパーユーザー判定
     * @param ServerRequest $request リクエスト情報
     * @return bool
     */
    public static function isSuperUser(ServerRequest $request = null)
    {
        if (is_null($request)) {
            return false;
        }
        $super_user = false;
        try {
            $super_user = (SUPER_USER_ID === $request->getSession()->read('Auth.Admin.id'));
        } catch (Exception $e) {
            Log::write(LogLevel::ERROR, $e->getMessage());

            return false;
        }

        return $super_user;
    }

    /**
     *
     * リクエスト先へのアクセスに必要な権限を持っているかチェック
     *
     * @param ServerRequest $request リクエスト情報
     * @param string $properties 権限チェックプロパティ $requestオブジェクト以外の権限チェックをしたいとき
     *                           'controller' => '[コントローラ名]',
     *                           'action' => '[アクション名]'
     *                           を配列でセットする
     *
     * @return bool
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

        $privileges = $request->getSession()->read('Auth.Admin.privilege.' . $controller);
        if (empty($privileges)) {
            return false;
        }

        switch ($action) {
            case ACTION_INDEX:
            case ACTION_VIEW:
                $has_role = in_array(ROLE_READ, $privileges, true);
                break;
            case ACTION_ADD:
            case ACTION_EDIT:
                $has_role = in_array(ROLE_WRITE, $privileges, true);
                break;
            case ACTION_DELETE:
                $has_role = in_array(ROLE_DELETE, $privileges, true);
                break;
            case ACTION_CSV_EXPORT:
                $has_role = in_array(ROLE_CSV_EXPORT, $privileges, true);
                break;
            case ACTION_CSV_IMPORT:
                $has_role = in_array(ROLE_CSV_IMPORT, $privileges, true);
                break;
            case ACTION_EXCEL_EXPORT:
                $has_role = in_array(ROLE_EXCEL_EXPORT, $privileges, true);
                break;
            case ACTION_EXCEL_IMPORT:
                $has_role = in_array(ROLE_EXCEL_IMPORT, $privileges, true);
                break;
                // 上記以外のアクションは一律アクセス可能
            default:
                $has_role = true;
        }

        return $has_role;
    }
}
