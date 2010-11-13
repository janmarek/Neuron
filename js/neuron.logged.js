// texyla
$.texyla.setDefaults({
	previewPath: "{{$texylaPreviewPath}}",
	baseDir: "{{$baseUri}}",
	iconPath: "{{$baseUri}}js/texyla/icons/%var%.png",

	// soubory
	filesPath: "{{$texylaFilesPath}}",
	filesThumbPath: "%var%",
	filesIconPath: "{{$baseUri}}js/texyla/files-icons/%var%.png",
	filesUploadPath: "{{$texylaFilesUploadPath}}"
});

// texyla
$("textarea.texyla").livequery(function () {
	$(this).texyla({
		toolbar: [
			"h2", "h3", "h4",
			null,
			"bold", "italic",
			null,
			"center", ["left", "right", "justify"],
			null,
			"ul", "ol",
			null,
			"link", "files", "symbol", "table", "youtube",
			null,
			"textTransform"
		],
		width: 640,
		padding: 0
	});
});

/**
 * Vytvoření přátelského URL
 * @param string s
 * @return string
 * @copyright Jakub Vrána, http://php.vrana.cz
 */
function webalize(s) {
	var nodiac = {
		'á': 'a', 'č': 'c', 'ď': 'd', 'é': 'e', 'ě': 'e', 'í': 'i', 'ň': 'n',
		'ó': 'o', 'ř': 'r', 'š': 's', 'ť': 't', 'ú': 'u', 'ů': 'u', 'ý': 'y',
		'ž': 'z'
	};

    s = s.toLowerCase();
    var s2 = '';
    for (var i=0; i < s.length; i++) {
        s2 += (typeof nodiac[s.charAt(i)] != 'undefined' ? nodiac[s.charAt(i)] : s.charAt(i));
    }
    return s2.replace(/[^a-z0-9_]+/g, '-').replace(/^-|-$/g, '');
}

// url
$("input.url").livequery(function () {
	var el = $(this);

	// název -> adresa
	$("#" + el.attr("rel")).keyup(function () {	
		el.val(webalize(this.value));
	});

	// korekce url adresy
	el.blur(function () {
		this.value = webalize(this.value);
	});
});

// date
$("input.datepicker").livequery(function () {
	$(this).datepicker();
});

/* Czech initialisation for the jQuery UI date picker plugin. */
/* Written by Tomas Muller (tomas@tomas-muller.net). */
jQuery(function($){
	$.datepicker.regional['cs'] = {
		closeText: 'Zavřít',
		prevText: '&#x3c;Dříve',
		nextText: 'Později&#x3e;',
		currentText: 'Nyní',
		monthNames: ['leden','únor','březen','duben','květen','červen',
        'červenec','srpen','září','říjen','listopad','prosinec'],
		monthNamesShort: ['leden','únor','březen','duben','květen','červen',
        'červenec','srpen','září','říjen','listopad','prosinec'],
		dayNames: ['neděle', 'pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota'],
		dayNamesShort: ['ne', 'po', 'út', 'st', 'čt', 'pá', 'so'],
		dayNamesMin: ['ne','po','út','st','čt','pá','so'],
		dateFormat: 'd.m.yy', firstDay: 1,
		isRTL: false};
	$.datepicker.setDefaults($.datepicker.regional['cs']);
});

// grid
$("div.gridito").livequery(function () {
	$(this).gridito();
});