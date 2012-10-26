jQuery.WPV = jQuery.WPV || {}; // Namespace
	
// The equalHeight plugin
jQuery.fn.equalHeight = function() {
	var tallest = 0;
	return this.each(function() {
		var thisHeight = jQuery(this).height();
		if(thisHeight > tallest) {
			tallest = thisHeight;
		}
	}).height(tallest);
};	

// The jQuery.rawContentHandler function
(function($) {
	var readyStarted = false;
	var readyEnded   = false;
	
	// This should be the FIRST ready handler to execute
	$(function() {
		readyStarted = true;
	});
	
	// This should be the LAST ready handler to execute
	$(document).bind("ready", function() {
		readyEnded = true;
	});
	
	$.rawContentHandler = function(cb) {
		if ($.isFunction(cb)) {
			if (!readyStarted) {
				$(function() { 
					$.rawContentHandler(cb);
				});
			}
			else {
				if (!readyEnded) {
					var tgt = $("body")[0];
					cb.call(tgt, tgt.childNodes);
				}
				
				$(window).bind("rawContent", function(e, items) {
					cb.call(e.target, items || e.target.childNodes);
				});
			}
		}
	};
})(jQuery);

(function ($, undefined) {

	jQuery.WPV.initJailImages = function( context, filter, speed, callback ) {
		var images = $("img.lazy[data-href]", context || document).not(".jail-started, :animated");
		if (filter) {
			images.filter(filter);
		}
		if (images.length) {
			var prefs = {speed : speed || 1000, timeout: 0};
			if (callback) {
				prefs.callback = callback;
			}
			images.addClass("jail-started").jail(prefs);
		}
		else {
			if (callback) {
				callback();
			}
		}
		images = null;
	};
	
	$(function () {
		// jquery.sequence img crop

		function sequenceRepaint() {
			var parentWidth = $(this).parent().width();
			var ow = parseInt($(this).attr('data-width'), 10);
			var oh = parseInt($(this).attr('data-height'), 10);
			if(ow && oh) {
				$(this).css({
					width: Math.min(ow, parentWidth),
					height: Math.min(oh, parentWidth / ow * oh)
				});
			} else {
				$(this).css({width: parentWidth});
			}

			if($(this).is(':not(.noimageresize)')) {
				function cropImage() {
					var w = this.width,
						h = this.height,
						sh = $(this).closest('.sequence').height();

					$(this).css({
						'margin-top': (sh-h)/2
					});
				}

				$(this).find('.crop img').each(function() {
					if (this.complete) {
						cropImage.apply(this, arguments);
					} else {
						$(this).bind("load", cropImage);
					}
				});
			}
		}

		$(window).resize(function() {
			$('.sequence').each(sequenceRepaint);
		}).resize();

		$('#feedback.slideout').click(function(e) {
			var wrap = $(this).parent();
			var new_left = (wrap.position().left < 0) ? 0 : -200;
			wrap.animate({
				left: new_left
			}, 400);
			
			e.preventDefault();
		});

		// lazy load images
		// The JAIL plugin is written in such a way that makes an error if it's 
		// applied in empty collection!
		var commonImages = $('img.lazy').not(".portfolios.sortable img, :animated, .wpv-wrapper img");
		if (commonImages.length) {
			commonImages.addClass("jail-started").jail({
				speed: 800
			});
		}
		
		var sliderImages = $('.wpv-wrapper img.lazy');
		if(sliderImages.length) {
			sliderImages.addClass("jail-started").jail({
				speed: 1400,
				event: 'load'
			});
		}

		// lightbox
		$.rawContentHandler(function() {
			$(".colorbox, .lightbox", this)
			.not('.no-lightbox, .size-thumbnail, .cboxElement')
			.each(function() {
				var $link = $(this);
				
				var iframe = $link.attr('data-iframe');

				if (iframe == undefined || iframe == 'false') iframe = false;
				else iframe = true;

				var href = this.href || false;
				var inline = $link.attr('data-inline');
				if (inline == undefined || inline == 'false') {
					inline = false;
				}
				else {
					inline = true;
					href = $link.attr('data-href') || this.href || false;
				}

				var width = $link.attr('data-width');
				if (width == undefined) {
					if (iframe == true || inline == true) width = '80%';
					else width = '';
				}
				var height = $link.attr('data-height');
				if (height == undefined) {
					if (iframe == true || inline == true) height = '80%';
					else height = '';
				}

				var photo = $link.attr('data-photo');
				photo = !(photo == undefined || photo == 'false');

				var close = $link.attr('data-close');
				close = !! (close == undefined || close == 'true');

				$link.colorbox({
					opacity: 0.7,
					innerWidth: width,
					innerHeight: height,
					iframe: iframe,
					inline: inline,
					href: href,
					photo: photo,
					scalePhotos: true,
					maxWidth   : "90%",
					maxHeight  : "90%",
					title: function () {
						var share = '';

						if ($('body').hasClass('cbox-share-gplus')) {
							share += '<div><div class="g-plusone" data-size="medium"></div>								<script type="text/javascript">\
								  (function() {\
								    var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;\
								    po.src = "https://apis.google.com/js/plusone.js";\
								    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);\
								  })();\
								</script></div>';
						}

						if ($('body').hasClass('cbox-share-facebook')) {
							share += '<div><iframe src="//www.facebook.com/plugins/like.php?href=' + window.location.href + '&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:auto; height:21px;" allowTransparency="true"></iframe></div>';
						}

						if ($('body').hasClass('cbox-share-twitter')) {
							share += '<div><iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/tweet_button.html" style="width:auto; height:20px;"></iframe></div>';
						}

						if ($('body').hasClass('cbox-share-pinterest')) {
							share += '<div><a href="http://pinterest.com/pin/create/button/" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a></div>';
						}

						var title = $link.attr('title') || '';

						return '<div id="cboxShare">' + share + '</div><div id="cboxTextTitle">' + title + '</div>';
					},
					onLoad: function () {
						if (!close) $("#cboxClose").css("visibility", "hidden");
						else $("#cboxClose").css("visibility", "visible");

						$("#colorbox").removeClass('withVideo');
					}
				});
			});
		});
		
		// tabs and accordions
		$.rawContentHandler(function() {
			$('.tabs', this).tabs().each(function () {
				if (Number($(this).attr('data-delay'))) {
					$(this).tabs('rotate', $(this).attr('data-delay'));
				}
			});
			$('.accordion', this).accordion({
				autoHeight: false
			});

			$(".toggle_title", this).click(function () {
				if ($(this).is('.toggle_active')) {
					$(this).parent().removeClass('open');
					$(this).removeClass('toggle_active').siblings('.toggle_content').slideUp("fast");
				} else {
					$(this).parent().addClass('open');
					$(this).addClass('toggle_active').siblings('.toggle_content').slideDown("fast");
				}
			}).siblings('.toggle_content.load_hidden').hide();
		});
		
		// :before and :after fixes for ie7
		if ($.browser.msie && $.browser.version == 7) {
			var ba = '.widget_pages li a';

			$('*').each(function (i) {
				if ($(this)[0].currentStyle['before']) {
					var before = $(this).prepend('<span class="before"></span>').find('span.before');
					before.text($(this)[0].currentStyle['before'].replace(/'/g, ''));
				}

				if ($(this)[0].currentStyle['after']) {
					var after = $(this).append('<span class="after"></span>').find('span.after');
					after.text($(this)[0].currentStyle['after'].replace(/'/g, ''));
				}
			});
		}
	});
	
})(jQuery);
