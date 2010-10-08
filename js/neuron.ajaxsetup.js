// ajax
$(function () {
	// přidám spinner do stránky
	$('<div id="ajax-spinner"></div>').hide().ajaxStop(function () {
		// nastavení původních vlastností, třeba kvůli odesílání formuláře
		$(this).hide().css({
			position: "fixed",
			left: "50%",
			top: "50%"
		});
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

// form buttons
$("form.ajax :submit, :submit.ajax").live("click", function (event) {
	event.preventDefault();

	var form = $(this).is("form") ? $(this) : $(this.form);

	if (form.get(0).onsubmit && !form.get(0).onsubmit()) return false;

	$(this).ajaxSubmit({
		complete: function () {
			// enable
			form.find(":input").each(function () {
				$(this).attr("disabled", $(this).data("defaultDisabled"));
			});
		}
	});

	// spinner position
	if (event.pageX && event.pageY) {
		$("#ajax-spinner").css({
			position: "absolute",
			left: event.pageX + 20,
			top: event.pageY + 40
		});
	}

	$("#ajax-spinner").show();

	// set default disabled
	form.find(":input").each(function () {
		$(this).data("defaultDisabled", $(this).attr("disabled"));
	}).attr("disabled", true);
});