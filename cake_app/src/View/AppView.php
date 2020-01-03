<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link	  https://cakephp.org CakePHP(tm) Project
 * @since	 3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;

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
	public function initialize()
	{
	}

	/**
	 * チェックボックスのエンティティ配列とvalue値を元にチェックボックスが初期表示用のboolを返す
	 * @param array $child_entities
	 * @param string $column_name
	 * @param string $this_val
	 * @return boolean
	 */
	public function isCheckboxChecked($child_entities = null, $column_name, $this_value) {
		if (!is_null($child_entities) && count($child_entities) > 0) {
			foreach ($child_entities as $child_entity) {
				if ($child_entity[$column_name] == $this_value) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * 一覧画面や詳細画面でチェックボックス項目の値を表示する
	 * @param array $child_entities
	 * @param array $selections
	 * @param string $column_name
	 * @param string $delimiter
	 */
	public function displayCheckboxItem($child_entities = null, $selections = null, $column_name = null, $delimiter = "、") {
		if (!is_null($child_entities) && count($child_entities) > 0 && !is_null($selections) && count($selections) > 0) {
			$values = [];
			foreach ($child_entities as $child_entity) {
				$values[] = @$selections[$child_entity[$column_name]];
			}
			return implode($delimiter, $values);
		}
		return "";
	}

	/**
	 * FrozenDateもしくはFrozenTimeを指定のフォーマットに変換して返す
	 * @param unknown $obj
	 * @param string $format
	 * @return string|number|string
	 */
	public function formatDate($obj = null, $format = 'yyyy-MM-dd') {
		if (!is_null($obj) && ($obj instanceof FrozenDate || $obj instanceof FrozenTime)) {
			return $obj->i18nFormat($format);
		}
		return "";
	}
}
