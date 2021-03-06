<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 */
$functions = _code('LeftSideMenu');
$html = "";
if (!empty($functions) && count($functions) > 0) {
	foreach ($functions as $alias => $function) {
		if (AuthUtils::hasRole($this->getRequest(), ['controller' => $function['controller'], 'action' => ACTION_INDEX])) {
			$html .= "<li class=\"nav-item\">";
			if (!method_exists("App\\Controller\\Admin\\{$function['controller']}Controller", 'index')) {
				$url = $this->Url->build(['controller' => $function['controller'], 'action' => ACTION_EDIT]);
			} else {
				$url = $this->Url->build(['controller' => $function['controller'], 'action' => ACTION_INDEX, '?' => _code("InitialOrders.{$alias}")], ['escape' => false]);
			}
			$html .= $this->Html->link(
				"<i class=\"{$function['icon_class']} fa-fw mr-2\"></i><p>{$function['label']}</p>",
				$url,
				['class' => ($this->name == $function['controller']) ? 'nav-link active' : 'nav-link', 'escapeTitle' => false]
			);
			$html .= "</li>";
		}
	}
}

$access_logs_active = ($this->name == "AccessLogs") ? " active" : "";
$html .= "<li class=\"nav-item\"><a href=\"/admin/access-logs\" class=\"nav-link{$access_logs_active}\"><i class=\"far fa-chart-bar fa-fw mr-2\"></i><p>アクセスログ集計</p></a></li>";
$latest_logs_active = ($this->name == "LatestLogs") ? " active" : "";
$html .= "<li class=\"nav-item\"><a href=\"/admin/latest-logs\" class=\"nav-link{$latest_logs_active}\"><i class=\"far fa-chart-bar fa-fw mr-2\"></i><p>直近のアクセスログ</p></a></li>";

// 管理者のみ権限管理可能
if (AuthUtils::isSuperUser($this->getRequest())) {
	$active_class = ($this->name == 'Account') ? ' active' : '';
	$html .= "<li class=\"nav-item mt-3\"><a href=\"/admin/account\" class=\"nav-link{$active_class}\" ><i class=\"fas fa-user-shield mr-2\"></i><p>アカウント/権限管理</p></a></li>";
}
$html .= "<li class=\"nav-item mt-3\"><a href=\"/admin/auth/logout\" class=\"nav-link\"><i class=\"fas fa-sign-out-alt mr-2\"></i><p>ログアウト</p></a></li>";
echo $html;