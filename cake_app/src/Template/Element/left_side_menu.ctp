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