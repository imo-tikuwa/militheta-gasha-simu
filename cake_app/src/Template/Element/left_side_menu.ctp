<?php
$functions = _code('LeftSideMenu');
if (!empty($functions) && count($functions) > 0) {
	$html = "";
	foreach ($functions as $alias => $function) {
		$html .= "<li class=\"nav-item\">";
		$html .= "<a href=\"" . $this->Url->build(['controller' => "{$function['controller']}", 'action' => 'index']) . "\" class=\"nav-link";
		if ($this->name == $function['controller']) {
			$html .= " active";
		}
		$html .= "\">";
		$html .= "<i class=\"{$function['icon_class']} fa-fw mr-2\"></i><p>{$function['label']}</p>";
		$html .= "</a>";
		$html .= "</li>";
	}
	echo $html;
}
?>
<li class="nav-item"><a href="/admin/access-logs" class="nav-link<?php if ($this->name == "AccessLogs") { echo " active"; } ?>"><i class="far fa-chart-bar fa-fw mr-2"></i><p>アクセスログ集計</p></a></li>
<li class="nav-item"><a href="/admin/latest-logs" class="nav-link<?php if ($this->name == "LatestLogs") { echo " active"; } ?>"><i class="far fa-chart-bar fa-fw mr-2"></i><p>直近のアクセスログ</p></a></li>