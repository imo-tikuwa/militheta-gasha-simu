$(function(){
	$("select").select2({
		theme: "bootstrap4",
		width: 'auto',
		dropdownAutoWidth: true,
	});

	$("#sidemenu-toggle").on('click', function (){
		var changed_sidemenu_css_class = $("body").hasClass('sidebar-open') ? "sidebar-collapse" : "sidebar-open";
		Cookies.set('sidemenu-toggle-class', changed_sidemenu_css_class);
	});
});