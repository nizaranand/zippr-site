jQuery.WPV = jQuery.WPV || {}; // Namespace


(function($) {
	function fixMetas(firstTime) {
		$('meta[name="viewport"]').each(function(i, el) {
			el.content = firstTime
				? 'width=device-width,minimum-scale=1.0,maximum-scale=1.0'
				: 'width=device-width,minimum-scale=0.25,maximum-scale=1.6';
		});
	}

	fixMetas(true);

	$("body").bind('gesturestart', fixMetas);

})(jQuery);

/**
 * Modified version if the touchwipe plugin by  Andreas Waltl, 
 * netCU Internetagentur (http://www.netcu.de)
 * We have added support for the desktop browsers, so it works the same with 
 * mouse events too.
 */
(function ($) {
	var EVENT_TOUCH_START = 'ontouchstart' in document.documentElement ? "touchstart" : "mousedown";
	var EVENT_TOUCH_MOVE = 'ontouchmove' in document.documentElement ? "touchmove" : "mousemove";
	var EVENT_TOUCH_END = 'ontouchend' in document.documentElement ? "touchend" : "mouseup";
	$.fn.touchwipe = function (settings) {
		var config = {
			min_move_x : 20,
			min_move_y : 20,
			wipeLeft : function () {},
			wipeRight : function () {},
			wipeUp : function () {},
			wipeDown : function () {},
			preventDefaultEvents : true,
			canUseEvent : function () { return true; }
		};
		
		if (settings) {
			$.extend(config, settings);
		}
		
		this.each(function (i, o) {
			var startX;
			var startY;
			var isMoving = false;
			
			function cancelTouch() {
				$(o).unbind(EVENT_TOUCH_MOVE, onTouchMove);
				startX = null;
				isMoving = false
			}
			
			function onTouchMove(e) {
				if (config.preventDefaultEvents) {
					e.preventDefault();
				}
				if (isMoving) {
					var x = e.originalEvent.touches ? e.originalEvent.touches[0].pageX : e.pageX;
					var y = e.originalEvent.touches ? e.originalEvent.touches[0].pageY : e.pageY;
					var dx = startX - x;
					var dy = startY - y;
					if (Math.abs(dx) >= config.min_move_x) {
						cancelTouch();
						if (dx > 0) {
							config.wipeLeft.call(o, e);
						} else {
							config.wipeRight.call(o, e);
						}
					} else if (Math.abs(dy) >= config.min_move_y) {
						cancelTouch();
						if (dy > 0) {
							config.wipeDown.call(o, e);
						} else {
							config.wipeUp.call(o, e);
						}
					}
				}
			}
			
			function onTouchStart(e) {
				if (!config.canUseEvent(e)) {
					return true;
				}
				if (e.originalEvent.touches) {
					if (e.originalEvent.touches.length > 1) {
						return true;
					}
					startX = e.originalEvent.touches[0].pageX;
					startY = e.originalEvent.touches[0].pageY;
				}
				else {
					startX = e.pageX;
					startY = e.pageY;
				}
				isMoving = true;
				$(o).bind(EVENT_TOUCH_MOVE, onTouchMove);
				if (config.preventDefaultEvents) {
					e.preventDefault();
				}
				e.stopPropagation();
			}
			
			$(o).bind(EVENT_TOUCH_START, onTouchStart);
		});
		return this;
	}
})(jQuery);

function virtualWidth() { 
	return jQuery.browser.mozilla ? 
		window.innerWidth : 
		document.documentElement.clientWidth || document.body.clientWidth;
}

(function ($, undefined) {
	var IE        = $.browser.msie;
	var IE8       = IE && $.browser.version == 8;
	var J_WIN     = $(window);
	var J_HTML    = $("html");
	var WIN_WIDTH = $.browser.mozilla ? window.innerWidth : document.documentElement.clientWidth || document.body.clientWidth;
	var IS_TOUCH  = 'ontouchstart' in document.documentElement;
	
	if (virtualWidth() <= 700) {
		$("body").removeClass("has-left-column");
	}

	$.fn.aPosition = function() {
		thisLeft = this.offset().left;
		thisTop = this.offset().top;
		thisParent = this.parent();

		parentLeft = thisParent.offset().left;
		parentTop = thisParent.offset().top;

		return {
			left: thisLeft-parentLeft,
			top: thisTop-parentTop
		};
	};
	
	/* @MEDIA (size only) -------------------------------------------------- 
	========================================================================
	 Screen width   | .boxed .boxed-layout
	 _______________|_____________________
	 <=480          | 100%
	 <=481 & <=700  | 100%
	 >=701 & <=980  | 680
	 >=981 & <=1100 | 960 (default)
	 >=1201         | 1180
	 
	*/
	var lastClass; 
	J_WIN.bind('resize.sizeClass load.sizeClass', function () {
		WIN_WIDTH = $.browser.mozilla
		? window.innerWidth
		: document.documentElement.clientWidth || document.body.clientWidth; 
		
		//document.title = "Width: " + WIN_WIDTH;
		var toAdd = "", 
			toDel = "";
		
		do {
			if (WIN_WIDTH < 480) {
				toAdd = "width-0-480";
				break;
			} 
			if (WIN_WIDTH >= 480 && WIN_WIDTH <=700) {
				toAdd = "width-480-700 width-480-plus";
				break;
			}
			if (WIN_WIDTH > 700 && WIN_WIDTH <980) {
				toAdd = "width-700-980 width-480-plus width-700-plus";
				break;
			}
			else if (WIN_WIDTH >= 980 && WIN_WIDTH <1100) {
				toAdd = "width-980-1100 width-480-plus width-700-plus width-980-plus";
				break;
			}
			toAdd = "width-480-plus width-700-plus width-980-plus width-1100-plus";
		} while (false);
		
		if (lastClass != toAdd) {
			lastClass = toAdd;
			if (IE8) { 
				J_HTML.removeClass(
					"width-0-480 width-480-700 width-700-980 width-980-1100 " + 
					"width-480-plus width-700-plus width-980-plus width-1100-plus"
				).addClass(toAdd);
			}
			
			J_WIN.trigger("switchlayout");
		}
	});
	
	// Switch between normal menu and HTML select
	// ---------------------------------------------------------------------
	var selectMenu;
	function getSelectMenu() {
		if (!selectMenu) {
			var j = 0, deep = 0;
			selectMenu = ['<select id="main-menu-select">'];
			var realMenu = $(".main-menu .menu");
			var indent  = " " + String.fromCharCode(0x200A) + 
						  " " + String.fromCharCode(0x200A) +
						  " " + String.fromCharCode(0x200A) +
						  " " + String.fromCharCode(0x200A);
			
			function walk($items) {
				$items.each(function(i, o) {
					var a = $("> a" , o);
					var b = $("> ul", o);
					var href = a.attr("href");
					
					selectMenu[++j] = '<option';
					if ($(o).is(".current_page_item, .current-menu-item")) {
						selectMenu[++j] = ' selected="selected"';
					}
					selectMenu[++j] = ' value="' + href + '"> ';
					for (var x = 0; x < deep; x++) {
						selectMenu[++j] = indent;
					}
					selectMenu[++j] = " " + a.text() + '</option>';
							
					deep++;
					walk($("> ul > li", o));
					deep--;
					
				});
			}
			
			walk($("> li", realMenu));
			selectMenu[++j] = ['</select>'];
			selectMenu = selectMenu.join("");
			selectMenu = $(selectMenu).insertAfter(realMenu);
			selectMenu.hide();
			selectMenu.change(function() {
				var url = $(this).val();
				if (url) {
					top.location.href = url;
				}
			})
			
		}
		return selectMenu;
	}
	
	J_WIN.bind("switchlayout", function(e) {
		
		if (virtualWidth() < 700) {
			$(".main-menu .menu").hide();
			getSelectMenu().show();
		}
		else {
			$(".main-menu .menu").show();
			$("#main-menu-select").hide();
		}
	});
	
	J_WIN.bind("delayedResize", function() {
		var slider = jQuery('#header-slider').data("WPVSlider");
		if (slider) { // if ready
			slider.setResizing('cover');
		}
	});
	
	var delayedResizeTimeout;
	function delayedResizeHandler() {
		if (delayedResizeTimeout) {
			window.clearTimeout(delayedResizeTimeout);
		}
		delayedResizeTimeout = setTimeout(function() {
			J_WIN.trigger("delayedResize");
		}, 150);
		
	}
	J_WIN.bind('resize',  delayedResizeHandler);
	
	$(function () {
		
		if ($.isArray(window.wpvBgSlides)) {
			var body = $('body');
			body.fastSlider({}, wpvBgSlides);

			$(window).bind('keydown', function(e) {
				switch(e.keyCode || e.which) {
					case 37:
						(body.data("fastSlider") && body.data("fastSlider").prev());
					break;
					case 38:
						(body.data("fastSlider") && body.data("fastSlider").goToNextGalleryItem());
					break;
					case 39:
						(body.data("fastSlider") && body.data("fastSlider").next());
					break;
					case 40:
						(body.data("fastSlider") && body.data("fastSlider").goToPrevGalleryItem());
					break;
				};
			});
		} else if($("#header-slider").data("WPVSlider")) {
			var slider = $("#header-slider").data("WPVSlider");

			$(window).bind('keydown', function(e) {
				switch(e.keyCode || e.which) {
					case 37:
						slider.prev();
					break;
					case 39:
						slider.next();
					break;
				};
			});
		}
		
		//J_WIN.triggerHandler('resize.sizeClass'); 
		
		function stripShareButtons(html) {
			var shareStart = "<!-- Starts share-btns (do not remove this comment) -->";
			var shareEnd = "<!-- Ends share-btns (do not remove this comment) -->";
			var start = html.indexOf(shareStart);
			var end   = html.indexOf(shareEnd);
			if (start > -1 && end > -1) {
				html = html.substring(0, start) + html.substr(end + shareEnd.length);
			}
			return html;
		}
		
		function parseReducedResponce(text, callback) {
			var data = {};
			$.each(text.split("-----VAMTAM-----SPLIT-----"), function(i, token) {
				var index = token.indexOf("|");
				if (index > -1) {
					data[token.substr(0, index)] = token.substr(index + 1);
				}
			});
			
			var awaited = 0, ready = 0;
			function commonCallback() {
				if (++ready >= awaited) callback(data);
			}
			
			// Include scripts
			$.each(["jquery.jplayer.min.js", "jquery.sequence.js"], function(i, script) {
				if (data.scripts.indexOf(script) > -1) {
					var src = data.scripts.match(new RegExp("<script .*?src=['\"](.*?" + script + ".*?)['\"]", "i"));
					if (src && src[1]) {
						awaited++;
						jQuery.getScript(src[1], commonCallback);
					}
				}
			});
			
			if (awaited < 1) {
				callback(data);
			}
			
			return data;
		}
		
		// Load More Button
		// ---------------------------------------------------------------------
		$(".load-more").die("click.pagination").live("click.pagination", function(e) {

			// Skip if alredy started
			if ($(this).is(":animated")) {
				return false;
			}
			
			var $currentLink = $(this);
			var $currentList = $currentLink.prev();
			
			var containerSelector = $currentList.is("section.portfolios > ul")
			? "section.portfolios > ul"
			: $currentList.is(".loop-wrapper") 
				? ".loop-wrapper:first"
				: null;

			if (containerSelector) {
				// Start loading indicator
				$(this).addClass("loading").find("> *").animate({opacity: 0});
				
				$.ajax({
					url      : $("a.lm-btn", this).attr("href"),
					dataType : "text",
					success  : function(html) {
						
						html = stripShareButtons(html);
						
						var article = $('<div/>').html(
							html.replace(/[\s\S]*?<article\b[^>]*>([\s\S]*)<\/article>[\s\S]*/i, "$1")
							.replace(/<script[^>]*>([\s\S]*?)<\/script>/gi, '<span class="script" style="display:none">$1</span>')
						);
						
						var newContainer = $(containerSelector, article);
						if (newContainer.length) {
							
							// get the height to start from
							var startHeight = $currentList.height();
							
							// Append the new items as transparent ones
							var newItems = newContainer.children().css("opacity", 0);
							$currentList.append(newItems);
							
							$("span.script", $currentList).each(function(i, o) {
								$.globalEval($(o).text());
							}).remove();
							
							$currentList.trigger("rawContent", newItems.get());
							
							// Get the final height
							var endHeight = $currentList.height();
							
							// Expand the container 
							$currentList.height(endHeight);
							$currentList.css("height", "auto").children().animate({opacity: 1}, 1000);
							jQuery.WPV.initHoverFX($currentList);
							
							var newPager = $(".load-more", article);
							
							if (newPager.length) {
								$currentLink
									.html(newPager.html())
									.removeClass("loading")
									.find("> *").animate({opacity: 1}, 600, "linear");
							}
							else {
								$currentLink.slideUp().remove();
							}
							$(window).trigger("resize").trigger("scroll");
							article = newContainer = startHeight = endHeight = newPager = null;
						}
					}
				});
			}
			return false;
		});
		
		// Prev/Next Pagination
		// ---------------------------------------------------------------------
		$(".next-post a, .prev-post a").die("click.pagination").live("click.pagination", function(e) {
			e.preventDefault();
			e.stopPropagation();
			var thisContainer = $("#main").find(".page-wrapper:first");
			
			if (thisContainer.css("position") == "static") {
				thisContainer.css("position", "relative");
			}
			
			$(this).addClass("loading").css({ 
				color : "transparent !important"
			});
			
			if ($.fn.jPlayer) {
				$(".jp-jplayer").jPlayer( "destroy" );
			}
			
			var newUrl = $(this).attr("href");
			$.ajax({
				url      : newUrl,
				data     : { reduced : 1 },
				dataType : "text",
				success  : function(html) {
					
					parseReducedResponce(html, function(data) {
						
						if (data.title) {
							document.title = data.title;
							if(window.history.pushState) {
								history.pushState( { location: newUrl }, data.title, newUrl );
							}
						}
						
						if (data.ptitle) {
							$("header.page-header").replaceWith(data.ptitle);
						}
						
						if (data.content) {
							
							var _html = data.content.replace(
								/<script[^>]*>([\s\S]*?)<\/script>/gi, 
								function(all, code) {
									return '<span class="script" style="display:none">'
									+ encodeURIComponent(code)
									+ '</span>';
								}
							);
							
							_html = stripShareButtons(_html);
							
							var oldChildren = thisContainer.children();

							var page = $('<div/>').css({
								height : "auto",
								opacity: 0
							}).appendTo(thisContainer).html(_html);
							
							$(".page-wrapper:first", page).removeClass("page-wrapper");
							
							$(".load-more, .widget.wpv_flickr", page).remove();
							
							$("span.script", page).each(function(i, o) {
								$.globalEval(decodeURIComponent($(o).text()));
							}).remove();
							
							page.trigger("rawContent");
							
							var _done = 0;
							function commonCallback() {
								if (++_done == 2) {
									jQuery.WPV.initPortfolioGallery(page);
									$('#commentform').validator();
									$(window).trigger("resize").trigger("scroll");
								}
							}
							
							oldChildren.each(function(i) {
								$(this).animate({opacity: 0}, 800, "linear", function() {
									$(this).find("*").unbind().removeData().end().empty().remove();
									if (i >= oldChildren.length - 1)
										commonCallback();
								});
							});

							page.animate({opacity : 1}, 1000, "linear", commonCallback);
						}
					});
				}
			});
		});
		
		$('#commentform, .searchform').validator();
		
		// Main Menu
		// ---------------------------------------------------------------------
		$("nav ul li").each(function (i, o) {

			$(this).find("ul").css({
				visibility: "hidden",
				display: "none",
				opacity: 0
			});

			$(this).hover(

			function () {

				$('ul:first', this).stop(1, 1).delay(100).queue(function () {

					if ($(o).is(".hover")) {
						return;
					}

					var submenu = $('ul:first', o);
					var thisOffset = $(o).offset();
					var vw = $(window).width();
					var isFirst = submenu.is("nav > div > ul > li > ul");

					submenu.css({
						visibility: "hidden",
						display: "inline-block"
					});

					$(o).addClass("hover");

					if (thisOffset.left + $(o).outerWidth({margin: true}) + submenu.outerWidth({margin: true}) > vw) {
						submenu.css({
							right: isFirst ? 0 : "100%",
							left: "auto"
						});
					} else {
						submenu.css({
							left: isFirst ? 0 : "100%",
							right: "auto"
						});
					}

					$(this).css({
						opacity: 0,
						visibility: "visible"
					}).animate({
						opacity: 1
					}, 200, "linear").dequeue();
				});
			}, function () {
				$('ul:first', this).stop(1, 0).delay(200).queue(function () {
					$(this).animate({
						opacity: 0
					}, 100, "linear", function () {
						$(this).css({
							display: "none"
						});
						$(o).removeClass("hover");
					}).dequeue();
				});
			});
		});
		
		$(".menu-item > .sub-menu").each(function() {
			$(this).parent().addClass("has-submenu");
		});

		$('.post-head a img').parent().addClass('a-reset');

		$('.sitemap li:not(:has(.children))').addClass('single');
		
		
		// Tooltip
		// ---------------------------------------------------------------------
		var tooltip_animation = 250;
		$('.shortcode-tooltip').hover(function () {
			$(this).find('.tooltip').fadeIn(tooltip_animation).animate({
				bottom: 25
			}, tooltip_animation);
		}, function () {
			$(this).find('.tooltip').animate({
				bottom: 35
			}, tooltip_animation).fadeOut(tooltip_animation);
		});

		jQuery.WPV.initHoverFX = function() {};
		
		
		
		var w1, h1;
		
		$(window).bind("resize.resizeMainSlider", function(e) {
			var mainSlider = $("#header-slider").data("WPVSlider");
			if (mainSlider) {
				w1 = w1 || parseFloat(mainSlider.getOption("width"));
				h1 = h1 || parseFloat(mainSlider.getOption("height"));
				if (!isNaN(w1) && !isNaN(h1)) {
					var w2 = $(mainSlider.view).width();
					var h2 = Math.min(h1 * w2/w1, h1);
					mainSlider.setOption("height", h2);
					mainSlider.view.style.height = h2 + "px";
					$(mainSlider.view).closest(".wpv-wrapper").css("height", h2)
					.find(".wpv-fx-grid-mask").remove();
					//console.log(w1, h1, w2);
				}
				mainSlider.setResizing("cover");
			}
		}).triggerHandler("resize.resizeMainSlider");
		
		
		function getSliderResizingMetaData(container) {
			var meta = $(container).data("sliderResizingMetadata");
			if (!meta) {
				meta = {
					startWidth  : container.clientWidth,
					startHeight : container.clientHeight,
				};
				meta.lastWidth = meta.startWidth;
				$(container).data("sliderResizingMetadata", meta);
				
				$(container).css({
					width : "100%",
					height: meta.startHeight
				});
				
				var slider = $(".wpv-view", container).data("WPVSlider");
				meta.maxWidth  = parseFloat(slider.getOption("width" )),
				meta.maxHeight = parseFloat(slider.getOption("height"));
				meta.slider = slider;
				$(".wpv-view, .wpv-wrapper", container).css({
					width : "auto",
					height: meta.startHeight
				});
				
				slider.setResizing("cover");
			}
			return meta;
		}
		
		$(window).bind("sliderload resize loadPortfolio ", function(e) {
			$(".slider-shortcode-wrapper.style-gallery").each(function(i, o) {
				if ($(".wpv-view", o).is(":visible")) {
					var meta = getSliderResizingMetaData(o);
					meta.slider.setResizing("cover");
					if (!isNaN(meta.maxWidth) && 
						!isNaN(meta.maxHeight) && 
						meta.maxWidth > 0 && 
						meta.maxHeight > 0) {
						
						$(o).css({
							maxWidth  : meta.maxWidth,
							maxHeight : meta.maxHeight
						});
						
						if (meta.lastWidth != $(o).width()) {
							meta.lastWidth = $(o).width();
							o.style.height = Math.round(meta.startHeight * ( meta.lastWidth / meta.startWidth)) + "px";
							$(".wpv-view, .wpv-wrapper, .wpv-slide-wrapper", o).css({
								height : Math.min(parseFloat(o.style.height), meta.maxHeight),
								width  : Math.min(meta.lastWidth, meta.maxWidth)
							});
						}
					}
				}
			});
		});
		
		$(window).bind("customresize", function(e, method, styles) {//console.log(e.target)
			var gray = $(e.target).data("gray");
			if (gray) {
				onTop(e.target, gray);
				gray.css(styles);
			}
		});
		
		// Starts Portfolio ----------------------------------------------------
		function onTop(bottom, top) {
			var z = parseInt($(bottom).css("zIndex"), 10);
			if (isNaN(z)) {
				z = 0;
			}
			z = Math.max(z + 1, 2);
			$(top).css("zIndex", z);
		}
		
		function grayscaleHandler() {
			$(this).addClass("grey").css({
				"imageRendering": "optimizeQuality",
				"-ms-interpolation-mode": "bicubic"
			});
			var gray = $(this)
						.clone()
						.addClass('grayscaled')
						.css({
							'position':'absolute',
							opacity: 1,
							"imageRendering": "optimizeQuality",
							"-ms-interpolation-mode": "bicubic"
						})
						.data("colorSrc", this);
			$(this).data("gray", gray);
			onTop(this, gray);
			
			if ($(this).css("position") == "static") {
				$(this).wrap("<div style='position: relative'></div>");
			}
			
			gray.insertBefore(this);
			
			grayscale(gray);
		}

		function grayscale(image) {
			try {
				image = image.get(0);
				if(Modernizr.canvas) {
					var src = image.src;
					var canvas = document.createElement('canvas');
					var ctx = canvas.getContext('2d');
					var imgObj = new Image();
					imgObj.src = src;
					canvas.width = imgObj.width;
					canvas.height = imgObj.height; 
					ctx.drawImage(imgObj, 0, 0); 
					var imgPixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
					for(var y = 0; y < imgPixels.height; y++){
						for(var x = 0; x < imgPixels.width; x++){
							var i = (y * 4) * imgPixels.width + x * 4;
							var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
							imgPixels.data[i] = avg; 
							imgPixels.data[i + 1] = avg; 
							imgPixels.data[i + 2] = avg;
						}
					}
					ctx.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
					image.src = canvas.toDataURL();
				} else {
					image.style.filter += " progid:DXImageTransform.Microsoft.BasicImage(grayscale=1)";
				}
			} catch(e) {
				//console.log(e);
			}
	    }
		
		if ( !IS_TOUCH ) {
			$('img.desaturate').live('jailStartAnimation.portfolio', grayscaleHandler);
			$(window).bind("load rawContent", function() {
				$('.portfolios .thumbnail img:not(.lazy, .gray, .grayscaled), img.desaturate:not(.lazy, .gray)').each(function() {
					if (this.complete) {
						grayscaleHandler.apply(this, arguments);
					}
					else {
						$(this).one("load", grayscaleHandler);
					}
				});
			});

			$('.portfolios > ul > li, .team-member').live('mouseenter', function() {
				var gray = $(this).find('img.grayscaled').stop(1, 0);
				onTop(gray.data("colorSrc"), gray);
				gray.animate({ opacity: 0.01 }, 600, 'swing');
			})
			.live('mouseleave', function() {
				$(this).find('img.grayscaled').stop(1, 0).animate({ opacity: 1 }, 600, 'swing');
			});
		}
		
		function getPortfolioColumnCount($ul) { 
			if ($ul.is(".portfolio_two_columns"))
				return 2;
			if ($ul.is(".portfolio_three_columns"))
				return 3;
			if ($ul.is(".portfolio_four_columns"))
				return 4;
			return 1;
		}
		
		function getPortfolioColumnWidth($ul) { // float
			if (virtualWidth() <= 700)
				return 1;
			switch (getPortfolioColumnCount($ul)) {
				case 2:
					return 0.5;
				case 3:
					return 0.333333;
				case 4:
					return 0.25;
			}
			return 1;
		}
		
		function getPortfolioColumnGap($ul) {// stub
			return 0; // Means 0% as set in base.css
		}
		
		jQuery.WPV.initPortFolio = function(context) {
			
			var portfolioImages = $('.portfolios.sortable', context || document)
				.find("img.lazy").not(".jail-started");
			
			portfolioImages.closest(".portfolios.sortable > ul").fadeTo(1, 0.01)
				.parent().addClass("loading").css("backgroundPosition", "center 150px");
				
			function callback() {
				//jQuery.WPV.initHoverFX(portfolioImages);
				
				setTimeout(function() {
					var portfolios = $(".portfolios.sortable", context || document);
					portfolios.each(function (pi) {

						var list   = $('> ul', this),
							items  = $("> li", list),
							links  = $('.sort_by_cat a', this),
							cat    = "all", 
							toShow = items, 
							toHide = $();
							
						// The initial switch to absolute layout
						list.css({
							position: "relative",
							height  : list.height()
						});
						
						function updateThumbnailPositions(internal) {
							list.css("height", list.height());
							
							var row       = 0,
								col       = 0,
								baseLineY = 0,
								baseLineX = 0,
								rowHeight = 0,
								listWidth = list.width(),
								colCount  = getPortfolioColumnCount(list),
								colWidthF = getPortfolioColumnWidth(list),
								colWidth  = Math.floor(listWidth * colWidthF);
							
							var colGap    = getPortfolioColumnGap(list) * 100,
								rowGap    = listWidth * (colGap / 100),
								len       = toShow.length;
								
							toShow.each(function (j) {
								
								var isFirst = j % colCount === 0 || 1 === colWidthF;
								var item    = $(this).css({ height: "auto", width: colWidth, marginBottom : 0});
								var height  = item.outerHeight() + rowGap;
								
								if (isFirst) {
									baseLineY += rowHeight;
									rowHeight = 0;
									col = 0;
								}
								
								rowHeight = Math.max(rowHeight, height);
								baseLineX = col * colWidth + col * colGap;
								col++;
								
								var cssFrom = {
									display : "block",
									zIndex  : 200
								};
								var cssTo = {
									top  : baseLineY,
									left : baseLineX,
									avoidTransforms : true
								};
								
								if (!internal) {
									cssFrom.opacity = 0;
									cssTo.opacity = 1;
								}
								
								if (internal) {
									$(this).css(cssFrom).css(cssTo);
									if (j == toShow.length - 1) {
										list.trigger("portfolioReflow");
									}
									
								}
								else {
									list.addClass("animated");
									item.css(cssFrom)
									.delay(cat == 'all' ? Math.max(0, (j - 3) * 100) : j * 200)
									.queue(function() {
										item.animate(cssTo, {
											easing : "easeInOutQuint",
											duration : 800,
											complete : function() {
												item.css("zIndex", 2);
												if (j == toShow.length - 1) {
													list.removeClass("animated");
													list.trigger("portfolioReflow");
												}
											}
										});
									}).dequeue();
								}
								
								if (j === len - 1) {
									baseLineY += rowHeight;
								}
							});
							
							toHide
							.filter(":visible")
							.css({ zIndex: 1 }).animate({ opacity: "hide" }, cat == 'all' ? 1000 : 600, "swing", function() {
								list.trigger("portfolioReflow");
							});
							
							list.stop(1, 0).animate({height : baseLineY}, IS_TOUCH ? 1 : 800, "swing", function() {
								list.trigger("portfolioReflow");
								list.parent().removeClass("loading");
							});
						}
							
						links.click(function (e) {
							links.removeClass('active');
							$(this).addClass('active');
							cat = $(this).attr('data-value');
							toShow = cat == 'all' ? items : items.filter('[data-type*=' + cat + ']');
							toHide = cat == 'all' ? $([])   : items.not('[data-type*=' + cat + ']');
							toShow.stop(1, 1);
							toHide.stop(1, 1);
							updateThumbnailPositions();
							return false;
						});
						
						setTimeout(function() {
							
							updateThumbnailPositions(true);
							
							items.css({
								position: "absolute",
								opacity : 1
							});
							
							list.addClass("transitionable").fadeTo(600, 1)
							.parent().removeClass("loading");
							
							J_WIN.bind({
								switchlayout : function(e) { //console.log("switch at ", WIN_WIDTH);
									updateThumbnailPositions( true );
								},
								resize : function(e) {
									updateThumbnailPositions( true );
								}
							});
							
							if ( pi >= portfolios.length - 1) {
								$(window).trigger("loadPortfolio");
							}
							
						}, 500);
					});
				}, 0);
				
			}
			
			var timer;
			if (portfolioImages.length) {
				timer = setTimeout(callback, 5000);
				portfolioImages.addClass("jail-started").jail({
					speed: 2,
					event: "load",
					callback: function() {
						if (timer) {
							clearTimeout(timer);
						}
						callback();
					}
				});
			}
			else {
				callback();
			}
		}
		
		jQuery.WPV.initPortFolio(document);
		// Ends Portfolio ------------------------------------------------------

		// scroll to top button
		$(window).bind('resize scroll', function () {
			$('#scroll-to-top').css("opacity", window.pageYOffset > 0 ? 1 : 0);
		});
		$('#scroll-to-top').click(function () {
			$('html,body').animate({
				scrollTop: 0
			}, 300);
		});

		
	}); 
	
	
	// Starts New animated services 
	// =========================================================================
	(function() {
		
		var fadeSpeed  = 200,
			fadeEasing = "easeInOutQuad",
			sizeSpeed  = 400,
			sizeEasing = "easeInOutQuad",
			delay      = 200,
			hoverColumn;
		$(".row:has(> div > .services.has-more)").each(function(i, o) {
			var origRow = $(o),
				row   = origRow.clone().insertAfter(o),
				cols  = row.find("> div").addClass("has-more-col"),
				len   = cols.length;
			
			if (len < 2) {
				return;
			}
			
			var rowWidth,
				normalWidth,
				smallWidth,
				bigWidth;
			
			row.mouseleave(restoreAll);
			$("body").bind("touchstart", function() {
				if (hoverColumn) {
					hoverColumn = null;
					restoreAll();
				}
			});
			cols.bind("touchstart", function(e) { e.stopPropagation(); });
			
			function init(e) {
				var h = 0;
				if (virtualWidth() < 700) {
					row.hide();
					origRow.show();
				}
				else {
					origRow.hide();
					row.show();
					row.css("width", "100%");
					if (rowWidth != row.width()) {
						rowWidth = row.width();
						var gap     = (rowWidth/100) * 3;
						rowWidth    = Math.floor(rowWidth - (gap * (len-1)));
						normalWidth = rowWidth / len;
						smallWidth  = rowWidth / (len + 1);
						bigWidth    = smallWidth * 2;
						cols.removeData("targetHeight").width(normalWidth).css("height", "auto").each(function() {
							h = Math.max(h, $(this).innerHeight());
						}).height(h);
						cols
						.removeClass("one_half grid-1-2 one_third grid-1-3 one_fourth grid-1-4 one_fifth grid-1-5 one_sixth grid-1-6 two_thirds grid-2-3 two_fourths grid-2-4 two_fifths grid-2-5 two_sixths grid-2-6 three_fourths grid-3-4 three_fifths grid-3-5 three_sixths grid-3-6 four_fifths grid-4-5 four_sixths grid-4-6 five_sixths grid-5-6")
						.css({
							position : "relative",
							"float": "left",
							margin : "0 " + gap + "px 0 0"
						})
						.eq(cols.length - 1).css("marginRight", 0);
						
						var wrapper = $("> .column-scroller", row);
						if (!wrapper.length) {
							wrapper = $('<div class="column-scroller"/>');
							row.wrapInner(wrapper);
						}
					}
				}
			}			
			J_WIN.bind(($.browser.msie && $.browser.version == 9 ? "resize" : "switchlayout") + " load", init);
			
			function getColumnTargetHeight(col) {
				var meta = $(col).data("targetHeight");
				if (!meta) {
					var toShow  = $(".open", col).css({
							visibility: "hidden",
							width : bigWidth,
							height : "auto"
						}).addClass("visible"),
					content = $(".open-content", toShow).css("opacity", 0);
					
					meta = toShow.outerHeight();
					toShow.css({
						width : "100%",
						height : 0,
						paddingTop: 0,
						paddingBottom: 0,
						visibility: "visible"
					});
				}
				return meta;
			}
			
			function restoreAll() { 
				cols.stop(1, 0);
				
				var Q = $({});
				
				Q.queue(function() {
					var toFadeOut = cols.find(".closed, .open-content"), l = toFadeOut.length;
					if (!l) {
						Q.dequeue();
					}
					else {
						var done = 0;
						toFadeOut.each(function(i, content) {
							
							$(content).stop(1, 0);
							
							if (!$(content).is(":visible") || String($(content).css("opacity")) == "0") {
								if (++done >= l) {
									Q.dequeue();
								}
							}
							else {
								$(content).animate({ opacity: 0 }, fadeSpeed, fadeEasing, function() {
									if (++done >= l) {
										Q.dequeue();
									}
								});
							}
						});
					}
				});
				
				Q.queue(function() {
					cols.delay(fadeSpeed).animate({ width : normalWidth }, {
						duration: sizeSpeed, 
						easing : sizeEasing,
						queue : false
					});
					cols.find(".open").stop(1, 0).delay(fadeSpeed).animate({ height : 0 }, sizeSpeed, sizeEasing);
					cols.find(".closed").stop(1, 0).delay(fadeSpeed).animate({ opacity: 1 }, fadeSpeed, fadeEasing);
				});
			}
			
			cols.find(".open").wrapInner('<div class="open-content"></div>');
			cols.each(function(j, item) {
				var $item = $(item);
				$item.mouseenter(function() {
					hoverColumn = this;
					cols.stop(1, 0);
					var otherCols = cols.not(this);
					
					$item.delay(delay)
					
					// Fade-out all
					$item.queue(function() {
						var toFadeOut = $item.find(".closed").add(".open-content", cols),
							l         = toFadeOut.length;
						if (!l) {
							$item.dequeue();
						}
						else {
							var done = 0;
							toFadeOut.each(function(i, content) {
								
								$(content).stop(1, 0);
								
								if (String($(content).css("opacity")) == "0") {
									if (++done >= l) {
										$item.dequeue();
									}
								}
								else {
									$(content).stop(1, 0).animate({ opacity: 0 }, fadeSpeed, fadeEasing, function() {
										if (++done >= l) {
											$item.dequeue();
										}
									});
								}
							});
						}
					});
						
					// Resize others to smaller width
					$item.queue(function() {
						otherCols.find(".open").stop(1, 0).animate({ height : 0 }, sizeSpeed, sizeEasing);
						otherCols.animate({width : smallWidth}, {
							duration: sizeSpeed, 
							easing : sizeEasing,
							queue : false
						});
						
						$item.dequeue();
					});
					
					$item.queue(function(next) {
						$item.animate({width : bigWidth}, {
							duration: sizeSpeed, 
							easing : sizeEasing,
							queue : false,
							complete : next
						});
					});
					
					$item.queue(function() {
						$(".open", item).stop(1, 1).animate({ height : getColumnTargetHeight(item) }, sizeSpeed, sizeEasing, function() {
							$item.dequeue();
						});
					});
					
					$item.queue(function() {
						otherCols.find(".closed").stop(1, 1).animate({ opacity: 1 }, fadeSpeed, fadeEasing);
						$(".open > .open-content", item).stop(1, 1).animate({ opacity: 1 }, fadeSpeed, fadeEasing);
					});
					
					return false;
				});
			});
		});
	})();
	// =========================================================================
	// Ends New animated services
	
	
	// Portfolio - detail page -------------------------------------------------
	jQuery.WPV.initPortfolioGallery = function(context) {
		
		function fixHeight(viewer) {
			if (viewer.css("position") == "static") {
				viewer.css("position", "relative");
			}
			
			viewer.css("height", "auto").css({
				width     : viewer.width (),
				height    : viewer.height(),
				minHeight : viewer.height()
			});
		}
		
		$("article.portfolio", context || document).each(function(i, o) {
			var viewer	 = $("> .row > .portfolio_image_wrapper", this),
				thumbs	 = $("> .row > .portfolio_details a.portfolio-small", this);
			
			if (!thumbs.length) return;
			
			var timer = setTimeout(function() {
				fixHeight(viewer);
			}, 4000);
			
			viewer.one("jailComplete", function() {
				if (timer) {
					clearTimeout(timer);
					timer = null;
				}
				fixHeight(viewer);
			});
			
			thumbs.removeClass("lightbox").unbind("click").bind("click.portfolioGallery", function(e) {
				var oldView = $("> a.portfolio_image, > img", viewer).css({ zIndex : 1 }),
					newView = $('<img />').css({
					position : "absolute",
					zIndex : 3,
					left: "50%",
					top : 0,
					opacity  : 0
				}).attr({
					alt : $("> img", this).attr("alt")
				})
				.appendTo(viewer);
				
				newView.bind("load error", function(e) {
					$(this).css({
						marginLeft: -this.offsetWidth  / 2
					});
					
					oldView.animate({opacity:0}, 400, "linear");
					newView.animate({opacity:1}, 400, "linear", function() {
						newView.css({
							zIndex   : 1
						});
						oldView.unbind().remove();
					});
				}).attr("src", this.href);
				
				return false;
			});
		});
	};
	jQuery.WPV.initPortfolioGallery();
		
	// Internet Explorer fixes -------------------------------------------------
	if ($.browser.msie && $.browser.version == 8) { 
		$('p:empty').hide(); 
		$('*:last-child').addClass('last last-child');
		
		$("header.main-header").hover(
			function() {
				$(".header-overline", this).stop().animate({right: 0}, 500, "swing");
			},
			function() {
				$(".header-overline", this).stop().animate({right: "100%"}, 500, "swing");
			}
		);
	}  
 
	// -------------------------------------------------------------------------
	$.rawContentHandler(function() {
		$(".loop-wrapper.news .page-content", this).equalHeight();
	});
	
	$(function() {
		var hb = $(".top-nav-box");
		$(".main-header").append(
			$('<div class="top-nav-toggle"/>').click(function() {
				if ($(this).is(".opened")) {
					hb.stop().animate({ height : 0 });
					$(this).removeClass("opened");
				}
				else {
					hb.stop().css("height", "auto");
					var h = hb.outerHeight();
					hb.css("height", 0).animate({ height : h });
					$(this).addClass("opened");
				}
			})
		);
		
	});
	
	J_WIN.triggerHandler('resize.sizeClass');
	
	// Some touch control for the sliders
	$.rawContentHandler(function() {
		
		// Main slider
		$(".wpv-wrapper").touchwipe({
			preventDefaultEvents : false,
			canUseEvent : function(e) {
				return true;//$(e.target).is(".wpv-slide, .wpv-slide *");
			},
			wipeLeft: function(e) {
				e.preventDefault();
				$(this).find(".wpv-view").data("WPVSlider").next();
			},
			wipeRight: function(e) {
				e.preventDefault();
				$(this).find(".wpv-view").data("WPVSlider").prev();
			}
		});
		
		// BG lider
		$(".fast-slider").touchwipe({
			canUseEvent : function(e) {
				return $(e.target).is(".fast-slider");
			},
			wipeLeft: function() {
				$(this).data("fastSlider") && $(this).data("fastSlider").prev();
			},
			wipeRight: function() { 
				$(this).data("fastSlider") && $(this).data("fastSlider").next();
			},
			wipeDown: function() {
				$(this).data("fastSlider") && $(this).data("fastSlider").goToPrevGalleryItem();
			},
			wipeUp: function() {
				$(this).data("fastSlider") && $(this).data("fastSlider").goToNextGalleryItem();
			}
		});
	});
	
})(jQuery);

