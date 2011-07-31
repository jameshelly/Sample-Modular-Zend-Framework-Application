(function($) {
	$.fn.blockQuote = function(options) {
		var defaults = {
			// Quote Container/Text Style
			blockColor: "#000",
			blockSize: "16px",
			blockFont: "Georgia",
			blockFloat: "right",
			// Quotation Mark Style
			startQuoteColor: "#000",
			startQuoteSize: "20px",
			endQuoteColor: "#000",
			endQuoteSize: "20px",
			// Allows for non-conflicting class names
			idName: "blockQuote"
		};
		settings = $.extend({}, defaults, options);
		
		// Block Quote Content
		var blockQuoteStart = '<div class="' + settings.className + '"><b class="quotationStart">&ldquo;</b>';
		var blockQuoteText = $(this).text();
		var blockQuoteEnd = '<b class="quotationEnd">&rdquo;</b></div>';
		// Block Quote Output
		$("#" + settings.idName).html(blockQuoteStart + blockQuoteText + blockQuoteEnd);
		// Block Quote Styling
		$("." + settings.className).css({"color": settings.blockColor, "font-size": settings.blockSize, "font-family": settings.blockFont, "float": settings.blockFloat});
		$(".quotationStart").css({"color": settings.startQuoteColor, "font-size": settings.startQuoteSize});
		$(".quotationEnd").css({"color": settings.endQuoteColor, "font-size": settings.endQuoteSize});
	}
})(jQuery);