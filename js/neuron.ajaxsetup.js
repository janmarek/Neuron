// ajax
$(function () {
	// přidám spinner do stránky
	$('<div id="ajax-spinner"></div>').hide().ajaxStop(function () {
		// nastavení původních vlastností, třeba kvůli odesílání formuláře
		$(this).hide();
	}).appendTo("body");
});

// links
$("a.ajax").live("click", function (event) {
	event.preventDefault();

	$.get(this.href);

	// spinner position
	$("#ajax-spinner").css({
		position: "absolute",
		left: event.pageX + 20,
		top: event.pageY + 40
	}).show();
});