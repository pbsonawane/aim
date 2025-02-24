$(document).ready(function() {
	var disableCallbacks = location.href.match(/(\?|&)nocallbacks($|&|=)/);

	function consoleWrite(message) {
		$('#console').focus().append(message + '\n');
	}

	$('#demo2').html($('#demo1').html());
	//$('#demo1 li').first().addClass('active');
	//$('#demo2 li').first().addClass('active');

	if (!disableCallbacks) {
		active_menu_cb = function(e, submenu) {
			e.preventDefault();
			//$('#demo1').find('li').removeClass('active');
			var li =  $(this).parent();
			var lis = li.parents('li');
			//li.addClass('active');
			//lis.addClass('active');
		};

		$('#demo1, #demo2').find("li > a").click(function(e) {
			e.preventDefault();
			var isLink = $(this).is("a");
			var href = isLink ? $(this).attr('href') : '';

			if (isLink && href !== '#') {
				consoleWrite('Click my caret to open my submenu');
			} else if (isLink) {
				consoleWrite('Dummy link');
			}
		});
	} else {
		active_menu_cb = $.noop;
	}

	consoleWrite('navgoco console waiting for input...');

	$('pre > code').each(function() {
		var that = $(this),
			type = that.attr('class'),
			source = that.data('source'),
			code = $('#' + source + '-' + type).html();
		that.text($.trim(code));
	});

	$(".tabs a").click(function(e) {
		e.preventDefault();
	//	$(this).parent().siblings().removeClass('active').end().addClass('active');
		$(this).parents('ul').next().children().hide().eq($(this).parent().index()).show();
	});

	$(".panes").each(function() {
		$(this).children().hide().eq(0).show();

	});
	hljs.tabReplace = '    ';
	hljs.initHighlightingOnLoad();
});
