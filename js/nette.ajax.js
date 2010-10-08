jQuery.extend({
	nette: {
		updateSnippet: function (id, html) {
			$("#" + id).html(html);
		},

		success: function (payload) {
			// redirect
			if (payload.redirect) {
				window.location.href = payload.redirect;
				return;
			}

			// snippets
			if (payload.snippets) {
				for (var i in payload.snippets) {
					jQuery.nette.updateSnippet(i, payload.snippets[i]);
				}
			}
		}
	}
});

jQuery.ajaxSetup({
	success: jQuery.nette.success,
	dataType: "json"
});

jQuery.nette.updateSnippet = function (id, html) {
	$("#" + id).fadeTo("fast", 0.01, function () {
		$(this).html(html).fadeTo("fast", 1).find("input:text:first").focus();
	});
};

jQuery.fn.extend({
	ajaxSubmit: function (options) {
		var form;
		var sendValues = {};

		// submit button
		if (this.is(":submit")) {
			form = this.parents("form");
			sendValues[this.attr("name")] = this.val() || "";

		// form
		} else if (this.is("form")) {
			form = this;

		// invalid element, do nothing
		} else {
			return null;
		}

		// validation
		if (form.get(0).onsubmit && !form.get(0).onsubmit()) return null;

		// get values
		var values = form.serializeArray();

		for (var i = 0; i < values.length; i++) {
			var name = values[i].name;

			// multi
			if (name in sendValues) {
				var val = sendValues[name];

				if (!(val instanceof Array)) {
					val = [val];
				}

				val.push(values[i].value);
				sendValues[name] = val;
			} else {
				sendValues[name] = values[i].value;
			}
		}

		// send ajax request
		var ajaxOptions = options || {};
		ajaxOptions.url = form.attr("action");
		ajaxOptions.data = sendValues;
		ajaxOptions.type = form.attr("method") || "get";

		return jQuery.ajax(ajaxOptions);
	}
});