(function ($, undefined) {

$.widget("ui.gridito", {

	options: {
		
	},


	_create: function () {
		var _this = this;
		
		this.table = this.element.find("table.gridito-table");
		this.table.addClass("ui-widget ui-widget-content");
		this.table.find("th").addClass("ui-widget-header");
		this.table.find("tbody tr").hover(function () {
			$(this).addClass("ui-state-hover");
		}, function () {
			$(this).removeClass("ui-state-hover");
		});
		
		// sorting icons
		function initSortingIcons(normalClass, hoverClass) {
			_this.table.find("thead th ." + normalClass).hover(function () {
				$(this).removeClass(normalClass).addClass(hoverClass);
			}, function () {
				$(this).removeClass(hoverClass).addClass(normalClass);
			});
		};

		initSortingIcons("ui-icon-carat-2-n-s", "ui-icon-triangle-1-n");
		initSortingIcons("ui-icon-triangle-1-n", "ui-icon-triangle-1-s");
		initSortingIcons("ui-icon-triangle-1-s", "ui-icon-carat-2-n-s");

		// buttons
		this.element.find("a.gridito-button").each(function () {
			var el = $(this);
			el.button({
				icons: {
					primary: el.attr("data-gridito-icon")
				},
				disabled: el.hasClass("disabled")
			});
			
			// window button
			if (el.hasClass("gridito-window-button")) {
				el.click(function (e) {
					e.stopImmediatePropagation();
					e.preventDefault();
			
					var win = $('<div></div>').appendTo('body');
					win.attr("title", $(this).attr("data-gridito-window-title"));
					win.load(this.href, function () {
						win.dialog({
							modal: true
						});
						win.find("input:first").focus();
					});
				});
			}
			
			if (el.attr("data-gridito-question")) {
				el.click(function (e) {					
					if (!confirm($(this).attr("data-gridito-question"))) {
						e.stopImmediatePropagation();
						e.preventDefault();
					}
				});
			}
		});
	}
	
});

})(jQuery);