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
				"<i class=\"{$function['icon_class']} fa-fw me-2\"></i><p>{$function['label']}</p>",
				$url,
				['class' => ($this->name == $function['controller']) ? 'nav-link text-white active' : 'nav-link text-white-50', 'escapeTitle' => false]
			);
			$html .= "</li>";
		}
	}
}

$access_logs_active = ($this->name == "AccessLogs") ? " active" : "";
$html .= "<li class=\"nav-item\"><a href=\"/admin/access-logs\" class=\"nav-link text-white-50{$access_logs_active}\"><i class=\"far fa-chart-bar fa-fw me-2\"></i><p>アクセスログ集計</p></a></li>";
$latest_logs_active = ($this->name == "LatestLogs") ? " active" : "";
$html .= "<li class=\"nav-item\"><a href=\"/admin/latest-logs\" class=\"nav-link text-white-50{$latest_logs_active}\"><i class=\"far fa-chart-bar fa-fw me-2\"></i><p>直近のアクセスログ</p></a></li>";

// 管理者のみ権限管理可能
if (AuthUtils::isSuperUser($this->getRequest())) {
	$active_class = ($this->name == 'Account') ? ' text-white active' : ' text-white-50';
	$html .= "<li class=\"nav-item mt-3\"><a href=\"/admin/account\" class=\"nav-link{$active_class}\" ><i class=\"fas fa-user-shield me-2\"></i><span class=\"ms-2\">アカウント/権限管理</span></a></li>";
}
$html .= "<li class=\"nav-item mt-3\"><a href=\"/admin/auth/logout\" class=\"nav-link text-white-50\"><i class=\"fas fa-sign-out-alt me-2\"></i><span class=\"ms-2\">ログアウト</span></a></li>";
echo $html;
