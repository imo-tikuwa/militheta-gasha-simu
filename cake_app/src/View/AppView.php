<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\View\View;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link https://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
    }

    /**
     * FrozenDateもしくはFrozenTimeを指定のフォーマットに変換して返す
     * @param FrozenDate|FrozenTime $obj FrozenDate、もしくはFrozenTimeオブジェクト
     * @param string $format 日付フォーマット
     * @return string|number
     */
    public function formatDate($obj = null, $format = 'yyyy-MM-dd')
    {
        if (!is_null($obj) && ($obj instanceof FrozenDate || $obj instanceof FrozenTime)) {
            return $obj->i18nFormat($format);
        }

        return "";
    }

    /**
     * アカウント権限編集画面の権限設定フォームを表示
     * @param array $current_privilege 現在の権限の設定値
     * @return string html
     */
    public function makePrivilegeEditHtml($current_privilege = null)
    {
        $html = "";
        foreach (_code('BakedFunctions') as $controller => $function_name) {
            $html .= "<div class=\"row\">";
            $html .= "  <div class=\"col-lg-1 col-md-2 col-sm-2\">";
            $html .= "    {$function_name}";
            $html .= "  </div>";
            $html .= "  <div class=\"col-lg-11 col-md-10 col-sm-10\">";
            foreach (_code("AdminRoles.{$controller}") as $role_key => $role_name) {
                $id = strtoupper($controller) . "_" . $role_key;
                $checked = false;
                if (!is_null($current_privilege) && isset($current_privilege[$controller]) && in_array($role_key, $current_privilege[$controller], true)) {
                    $checked = true;
                }
                $html .= "<div class=\"form-check form-check-inline\">";
                $html .= $this->Form->checkbox("privilege.{$controller}[]", [
                    'value' => $role_key,
                    'id' => $id,
                    'class' => 'form-check-input',
                    'label' => false,
                    'hiddenField' => false,
                    'checked' => $checked,
                ]);
                $badge_class = _code("SystemProperties.RoleBadgeClass.{$role_key}");
                $html .= "<label class=\"form-check-label rounded-0 {$badge_class}\" for=\"{$id}\">{$role_name}</label>";
                $html .= "</div>";
            }
            $html .= "";
            $html .= "  </div>";
            $html .= "</div>";
        }

        return $html;
    }

    /**
     * アカウント権限一覧画面の権限設定を表示
     * @param array $current_privilege 現在の権限の設定値
     * @return string html
     */
    public function makePrivilegeListHtml($current_privilege = null)
    {
        $html = "";
        if (is_null($current_privilege)) {
            return $html;
        }
        foreach (_code('BakedFunctions') as $controller => $function_name) {
            if (isset($current_privilege[$controller]) && !empty($current_privilege[$controller])) {
                foreach ($current_privilege[$controller] as $controller_privilege) {
                    $role_name = _code("AdminRoles.{$controller}.{$controller_privilege}");
                    $badge_class = _code("SystemProperties.RoleBadgeClass.{$controller_privilege}");
                    $html .= "<label class=\"form-check-label rounded-0 {$badge_class}\">{$role_name}</label>&nbsp;";
                }
                $html .= "<br />";
            }
        }

        return $html;
    }
}
