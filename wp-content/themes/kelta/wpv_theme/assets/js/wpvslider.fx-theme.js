(function ($, undefined) {
	
	var TRANSITION_PROPERTY_NAME = getCssPropertyName("transition");
	var TRANSFORM_PROPERTY_NAME  = getCssPropertyName("transform");
	var TRANSITION_DURATION = getCssPropertyName("transitionDuration");
	var TRANSITION_DELAY = getCssPropertyName("transitionDelay");
	var TRANSITION_TIMING_FUNCTION = getCssPropertyName("transitionTimingFunction");
	var TRANSITION_END_EVENT = $.browser.webkit ? "webkitTransitionEnd" : window.opera ? "oTransitionEnd" : "transitionend";
	
	var EASINGS = {
		bounce: 'cubic-bezier(0.0, 0.35, .5, 1.3)',
		linear: 'linear',
		swing: 'ease-in-out',

		// Penner equation approximations from Matthew Lein's Ceaser: http://matthewlein.com/ceaser/
		easeInQuad:     'cubic-bezier(0.550, 0.085, 0.680, 0.530)',
		easeInCubic:    'cubic-bezier(0.550, 0.055, 0.675, 0.190)',
		easeInQuart:    'cubic-bezier(0.895, 0.030, 0.685, 0.220)',
		easeInQuint:    'cubic-bezier(0.755, 0.050, 0.855, 0.060)',
		easeInSine:     'cubic-bezier(0.470, 0.000, 0.745, 0.715)',
		easeInExpo:     'cubic-bezier(0.950, 0.050, 0.795, 0.035)',
		easeInCirc:     'cubic-bezier(0.600, 0.040, 0.980, 0.335)',
		easeInBack:     'cubic-bezier(0.600, -0.280, 0.735, 0.045)',
		easeOutQuad:    'cubic-bezier(0.250, 0.460, 0.450, 0.940)',
		easeOutCubic:   'cubic-bezier(0.215, 0.610, 0.355, 1.000)',
		easeOutQuart:   'cubic-bezier(0.165, 0.840, 0.440, 1.000)',
		easeOutQuint:   'cubic-bezier(0.230, 1.000, 0.320, 1.000)',
		easeOutSine:    'cubic-bezier(0.390, 0.575, 0.565, 1.000)',
		easeOutExpo:    'cubic-bezier(0.190, 1.000, 0.220, 1.000)',
		easeOutCirc:    'cubic-bezier(0.075, 0.820, 0.165, 1.000)',
		easeOutBack:    'cubic-bezier(0.175, 0.885, 0.320, 1.275)',
		easeInOutQuad:  'cubic-bezier(0.455, 0.030, 0.515, 0.955)',
		easeInOutCubic: 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
		easeInOutQuart: 'cubic-bezier(0.770, 0.000, 0.175, 1.000)',
		easeInOutQuint: 'cubic-bezier(0.860, 0.000, 0.070, 1.000)',
		easeInOutSine:  'cubic-bezier(0.445, 0.050, 0.550, 0.950)',
		easeInOutExpo:  'cubic-bezier(1.000, 0.000, 0.000, 1.000)',
		easeInOutCirc:  'cubic-bezier(0.785, 0.135, 0.150, 0.860)',
		easeInOutBack:  'cubic-bezier(0.680, -0.550, 0.265, 1.550)'
	};
	
	/**
	 * Returns the CSS property name supported by the current browser.
	 * If none is supported, returns an empty string.
	 * Uses it's own cache for the results.
	 * 
	 * @return {String} The CSS property name, as supported by the current browser.
	 */
	function getCssPropertyName( w3cName ) {
		// init cache
		if ( !getCssPropertyName.cache ) {
			getCssPropertyName.cache = {};
		}

		// camelize w3cName
		var name  = String( w3cName ).replace(/\-(\w)/g, function(all, letter) {
			return letter.toUpperCase();
		});

		if ( !(name in getCssPropertyName.cache) ) {
			var s = $("<i/>")[0].style, 
			i, tmp, result = "", v = [ "Moz", "Webkit", "O", "Khtml", "Ms" ];

			if (s[w3cName] !== undefined) {
				result = w3cName;
			}
			else {
				if (s[name] !== undefined) {
					result = name;
				}
				else {
					for (i = 0; i < v.length; i++) {
						tmp = v[i] + name.charAt(0).toUpperCase() + name.substr(1);
						if (s[tmp] !== undefined) {
							result = tmp;
							break;
						}
					}
				}
			}
			getCssPropertyName.cache[ name ] = result;
		}

		return getCssPropertyName.cache[ name ];
	}
	
	function createCountingCallback(count, callback, scope) {
		return function() {
			if (--count < 1) {
				callback.call(scope);
			}
		};
	}
	
	$.fn.setTransition = function(props, duration, easing, delay, callback) {
		if (!TRANSITION_PROPERTY_NAME) {
			return this;
		}
		return this.each(function(i, o) {
			$(o)
			.css(TRANSITION_PROPERTY_NAME, "all")
			.css(TRANSITION_DELAY, (delay || 0) + "ms")
			.css(TRANSITION_DURATION, duration + "ms")
			.css(TRANSITION_TIMING_FUNCTION, easing ? EASINGS[easing] || "ease" : "ease");
			setTimeout(callback || $.noop, (delay || 0) + duration);
		});
	};
	
	$.fn.wpvAddClass = function(className, duration, easing, delay, callback) {
		var	cb = createCountingCallback(this.length, callback || $.noop, this);
		return this.delay(delay).queue(function() {
			if (TRANSITION_PROPERTY_NAME) 
				$(this).setTransition("all", duration, easing, 0, cb).addClass(className);
			else 
				$(this).addClass(className, duration, easing, cb);
		}).dequeue();
	};
	
	$.fn.wpvRemoveClass = function(className, duration, easing, delay, callback) {
		var	cb = createCountingCallback(this.length, callback || $.noop, this);
		return this.delay(delay).queue(function() {
			if (TRANSITION_PROPERTY_NAME) 
				$(this).setTransition("all", duration, easing, 0, cb).removeClass(className);
			else 
				$(this).removeClass(className, duration, easing, cb);
		}).dequeue();
	};
	
	function getGrid(cfg) {
		var root = cfg.slider.view;
		var gridLayer = $(".wpv-fx-grid", root);

		if (!gridLayer.length) {
			gridLayer = $('<div class="wpv-fx-grid" />').css({
				width: "100%",
				height: "100%",
				position: "absolute",
				zIndex: 30
			})
			.bind("click", function(e) {
				cfg.slider.getCurrentSlide().trigger(e);
			})
			.appendTo(root);
		} else {
			gridLayer.unbind().empty();
		}

		var facetWidth = Math.ceil($(root).width() / cfg.cols);
		var facetHeight = Math.ceil($(root).height() / cfg.rows);
		var i = 0;
		for (var row = 0; row < cfg.rows; row++) {
			for (var col = 0; col < cfg.cols; col++) {
				var facet = $('<div class="wpv-fx-grid-facet" />').css({
					width: facetWidth,
					height: facetHeight,
					left: col * facetWidth,
					top: row * facetHeight,
					position: "absolute",
					overflow: "hidden",
					zIndex: 30
				}).appendTo(gridLayer);

				facet[0].row = row;
				facet[0].col = col;
				facet[0].facetWidth = facetWidth;
				facet[0].facetHeight = facetHeight;
				facet[0].rows = cfg.rows;
				facet[0].cols = cfg.cols;
				facet[0].index = i++;
			}
		}

		if (cfg.toShow) {
			cfg.toShow.unbind("fxStop").bind("fxStop", function () {
				gridLayer.find("wpv-fx-grid-facet").andSelf().stop(1, 1);
			});
		}

		if (cfg.visibleGrid) {
			var gridMask = $(".wpv-fx-grid-mask", root);
			if (!gridMask.length) {
				gridMask = gridLayer.clone().attr("class", "wpv-fx-grid-mask").css({
					position: "absolute",
					overflow: "hidden",
					width: "100%",
					height: "100%",
					zIndex: 31
				})
				.bind("click", function(e) {
					cfg.slider.getCurrentSlide().trigger(e);
				})
				.appendTo(root);
			} else {
				$(".wpv-fx-grid-mask", root).fadeIn(600);
			}
		}

		return gridLayer;
	}

	function toNum(x, defaultValue) {
		x = parseFloat(x);
		if (isNaN(x)) {
			return defaultValue || 0;
		}
		return x;
	}

	function parseCssPrefs(css, slice) {
		var newCss = {};

		function parse(x) {
			return eval(x.replace(/@(.+?)\b/g, function (a, b) {
				return slice[b];
			}));
		}

		for (var x in css) {
			if ($.isArray(css[x]) && typeof css[x][0] == "string") {
				newCss[x] = [parse(css[x][0]), css[x][1]];
			} else if (String(css[x]).indexOf("@") != -1) {
				newCss[x] = parse(css[x]);
			} else {
				newCss[x] = css[x];
			}
		}
		return newCss;
	}

	function wave(grid, cfg, callback) {
		var cnt = cfg.cols * cfg.rows;
		var waveDuration = Math.max(cfg.waveDuration, cnt);
		var subDelay = Math.floor(waveDuration / cnt);
		var done = 0;
		var img = cfg.toShow.show().find("> img");

		cfg.toShow.show();
		//var top = (toNum(img.css("top")) + toNum(img.css("marginTop"))) - toNum(img.css("marginBottom"));
		//var left = (toNum(img.css("left")) + toNum(img.css("marginLeft"))) - toNum(img.css("marginRight"));
		var top  = Math[$.browser.webkit ? "floor" : "round"]((cfg.toShow.parent().height() - $(img).height())/2);
		var left = Math[$.browser.webkit ? "floor" : "round"]((cfg.toShow.parent().width () - $(img).width ())/2);
		cfg.toShow.hide();

		var facetImage = $('<img src="' + img[0].src + '" />').css({
			width: img[0].style.width,
			height: img[0].style.height
		});

		var _grid = sortGrid(grid, cfg.waveType);

		$.each(_grid, function (i, o) {
			var cssFrom = parseCssPrefs(cfg.cssFrom, o);
			var cssTo = parseCssPrefs(cfg.cssTo, o);
			var facet = $(o);

			facetImage.clone().css({
				marginLeft: left + (o.facetWidth * o.col * -1),
				marginTop: top + (o.facetHeight * o.row * -1)
			}).appendTo(o);

			facet.css(cssFrom).stop(1, 0).delay(Math.ceil(subDelay * i)).queue(function() {
				facet.animate(cssTo, {
					duration : cfg.subslideDuration, 
					easing : cfg.easing, 
					queue : false,
					complete : function () {
						facet.css("filter", "none");
						cssFrom = cssTo = facet = null;
						if (++done >= cnt) {
							callback();
						}
					}
				});
			});
		});

		img = facetImage = null;
	}

	function initGridFx(slider) {
		getGrid($.extend({
			rows: 3,
			cols: 6,
			slider: slider,
			visibleGrid: true
		}, slider.options.effect_settings));
	}

	function runGridFx(cfg) {
		if (cfg.toShow.data("Slide").type == "image") {
			var _cfg = $.extend(true, {
				rows: 3,
				cols: 6,
				subslideDuration: 300,
				subslideTransition: "fade",
				waveDuration: 900,
				waveType: "natural",
				easing: "easeOutQuint",
				cssFrom: {},
				cssTo: {},
				visibleGrid: false
			}, cfg);

			var grid = getGrid(_cfg);

			var w1 = cfg.toHide ? $("> .wpv-slide", cfg.toHide).width() : 0;
			var h1 = cfg.toHide ? $("> .wpv-slide", cfg.toHide).height() : 0;
			var w2 = $("> .wpv-slide", cfg.toShow).width();
			var h2 = $("> .wpv-slide", cfg.toShow).height();
			
			wave(grid, _cfg, function () {
				_cfg.toHide.hide();
				_cfg.toShow.show();
				grid.remove();
				_cfg.callback();
				_cfg = grid = null;
			});
		}

		/*
		 * Grid-based effects does not support content other than images thus we
		 * need to create a pseudo-fx for this cases
		 * (img to html and html to html transitions)
		 */
		else {
			$(".wpv-fx-grid-mask", cfg.slider.view).fadeOut();
			$.WPVSlider.fx.fade.run(cfg);
		}
	}

	function sortGrid(grid, sorter) {
		var slices = $(".wpv-fx-grid-facet", grid).get();
		if (sorter && sorter != "natural") {
			slices.sort(_sorters[sorter]);
		}
		return slices;
	}

	// Grid sorting functions ----------------------------------------------------
	var _sorters = {
		R2L: function (a, b) { // right to left
			return a.col === b.col ? _sorters.T2B(a, b) : a.col < b.col ? 1 : -1;
		},
		R2L_SNAKE: function (a, b) { // right to left - snake
			return a.col === b.col ? _sorters[a.col % 2 == 0 ? "B2T" : "T2B"](a, b) : a.col < b.col ? 1 : -1;
		},
		L2R: function (a, b) { // left to right
			return a.col === b.col ? _sorters.T2B(a, b) : a.col < b.col ? -1 : 1;
		},
		L2R_SNAKE: function (a, b) { // left to right - snake
			return a.col === b.col ? _sorters[a.col % 2 == 0 ? "T2B" : "B2T"](a, b) : a.col < b.col ? -1 : 1;
		},
		B2T: function (a, b) { // bottom to top
			return a.row === b.row ? _sorters.L2R(a, b) : a.row < b.row ? 1 : -1;
		},
		B2T_SNAKE: function (a, b) { // bottom to top - snake
			return a.row === b.row ? _sorters[a.row % 2 == 0 ? "L2R" : "R2L"](a, b) : a.row < b.row ? 1 : -1;
		},
		T2B: function (a, b) { // top to bottom
			return a.row === b.row ? _sorters.L2R(a, b) : a.row < b.row ? -1 : 1;
		},
		T2B_SNAKE: function (a, b) { // top to bottom - snake
			return a.row === b.row ? _sorters[a.row % 2 == 0 ? "L2R" : "R2L"](a, b) : a.row < b.row ? -1 : 1;
		},
		BR2TL: function (a, b) { // bottom/right to top/left
			var x = a.col + a.row;
			var y = b.col + b.row;
			return x === y ? _sorters.B2T(a, b) : x < y ? 1 : -1;
		},
		TL2BR: function (a, b) { // top/left to bottom/right
			var x = a.col + a.row;
			var y = b.col + b.row;
			return x === y ? _sorters.T2B(a, b) : x < y ? -1 : 1;
		},
		TR2BL: function (a, b) { // top/right to bottom/left
			var x = a.col - a.row;
			var y = b.col - b.row;
			return x === y ? _sorters.T2B(a, b) : x < y ? 1 : -1;
		},
		BL2TR: function (a, b) { // bottom/left to top/right
			var x = a.col - a.row;
			var y = b.col - b.row;
			return x === y ? _sorters.B2T(a, b) : x < y ? -1 : 1;
		},
		RAND: function (a, b) { // random
			var x = Math.random();
			return x == 0.5 ? 0 : x < 0.5 ? -1 : 1;
			//return _sorters[["R2L", "L2R", "B2T", "T2B", "BR2TL", "TL2BR", "TR2BL", "BL2TR"][Math.floor(Math.random() * 8)]].call(null, a, b);
		}
	}

	$.WPVSlider.fx.gridFadeQueue = {
		init: initGridFx,
		changeCaptions: changeCaptionsOnGrid,
		run: function (cfg) {

			runGridFx(
			$.extend({
				easing: "linear",
				waveDuration: 600,
				subslideDuration: 600,
				cssFrom: {
					opacity: 0
				},
				cssTo: {
					opacity: 1
				},
				visibleGrid: true
			}, cfg, cfg.slider.options.effect_settings));
		}
	};
	
	$.WPVSlider.fx.gridFadeSlideQueue = {
		init: initGridFx,
		changeCaptions: changeCaptionsOnGrid,
		run: function (cfg) {
			runGridFx(
			$.extend({
				easing: "linear",
				waveDuration: 600,
				subslideDuration: 600,
				cssFrom: {
					opacity: 0,
                    			width : 0
				},
				cssTo: {
					opacity: 1,
                    			width : "@facetWidth"
				},
				visibleGrid: true
			}, cfg, cfg.slider.options.effect_settings));
		}
	};

	$.WPVSlider.fx.gridWaveBL2TR = {
		init: initGridFx,
		changeCaptions: changeCaptionsOnGrid,
		run: function (cfg) {
			runGridFx(
			$.extend({
				easing: "linear",
				waveType: "BL2TR",
				waveDuration: 200,
				subslideDuration: 300,
				visibleGrid: true,
				rows: 3,
				cols: 6,
				cssFrom: {
					opacity: 0,
					marginTop: "@facetHeight",
					marginLeft: "-@facetWidth"
				},
				cssTo: {
					opacity: [1, "easeInSine"],
					marginTop: 0,
					marginLeft: 0
				}
			}, cfg, cfg.slider.options.effect_settings));
		}
	};

	$.WPVSlider.fx.gridRandomSlideDown = {
		init: initGridFx,
		changeCaptions: changeCaptionsOnGrid, 
		run: function (cfg) {
			runGridFx(
			$.extend({
				waveType: "RAND",
				rows: 3,
				cols: 6,
				waveDuration: 600,
				subslideDuration: 600,
				visibleGrid: true,
				easing: "easeOutExpo",
				cssFrom: {
					height: 0,
					marginTop: "-@facetHeight",
					opacity: 0
				},
				cssTo: {
					opacity: 1,
					height: "@facetHeight",
					marginTop: 0
				}
			}, cfg, cfg.slider.options.effect_settings));
		}
	};

	// effects with subcaptions

	function getSubcaptionSpeed(subcaption, cfg) {
		var x = parseInt($(subcaption).attr("data-animation-duration"), 10);
		if (isNaN(x) || x < 0) {
			x = cfg.speed;
		}
		return x;
	}

	// NOTE that the per-subcaption delay is added to the global one!

	function getSubcaptionDelay(subcaption, cfg) {
		var x = parseInt($(subcaption).attr("data-animation-delay"), 10);
		if (isNaN(x) || x < 0) {
			x = 0;
		}
		return x;
	}

	function getSubcaptionEasing(subcaption, cfg) {
		return $(subcaption).attr("data-animation-easing") || cfg.slider.options.effect_settings.easing || "easeInOutCirc";
	}
	
	// Caption animation functions 
	// =========================================================================
	function doChangeCaptions(cfg, callback) {
		var oldSubCaptions = cfg.oldCaption.find(".sub-caption");
		var newSubCaptions = cfg.newCaption.find(".sub-caption");
		var isQueue        = cfg.slider.options.effect_settings.caption_queue;
		var hideFunctions  = [];
		var showFunctions  = [];
		
		if (!oldSubCaptions.length) {
			oldSubCaptions = cfg.oldCaption;
		}

		if (!newSubCaptions.length) {
			newSubCaptions = cfg.newCaption;
		}

		$(newSubCaptions).wpvRemoveClass("visible", 1);	
		if ( !$.support.opacity ) {
			$(newSubCaptions).css({opacity: 0});
		}
		
		if (cfg.oldCaption.length) {
			hideFunctions.push(function (next) {
				cfg.oldCaption.wpvRemoveClass(
					"visible", 
					Math.ceil(cfg.speed/2), 
					cfg.easing, 
					0, 
					next
				);
				if ( !$.support.opacity ) {
					cfg.oldCaption.animate({opacity: 0}, { 
						duration: Math.ceil(cfg.speed/2),
						easing : cfg.easing,
						queue : false
					});
				}
			});
		}
		
		oldSubCaptions.each(function (i, o) {
			hideFunctions.push(function (next) {
				$(o).wpvRemoveClass("visible", 1, "linear", 0, next);
				if ( !$.support.opacity ) {
					$(o).css({opacity: 0});
				}
			});
		});
		
		if (cfg.newCaption.length) {
			showFunctions.push(function (next) {
				if ( !$.support.opacity ) {
					cfg.newCaption.animate({opacity: 1}, { 
						duration: 500,
						easing : "swing",
						queue : false
					});
				}
				cfg.newCaption.wpvAddClass("visible", 500, "swing", 0, next);
			});
		}
		
		newSubCaptions.each(function (i, o) {
			var speed = getSubcaptionSpeed(o, cfg);
			var easing = getSubcaptionEasing(o, cfg);
			var delay = 0//getSubcaptionDelay(o, cfg);
			
			showFunctions.push(function (next) {
				
				
				
				$(o).wpvAddClass("visible", speed, easing, delay, function() {
					if (isQueue) {
						next();
					}
				});
				
				if ( !$.support.opacity ) {
					$(o).delay(delay).animate({opacity: 1}, { 
						duration: speed-100,
						easing : easing,
						queue : false
					});
				}
				
				if (!isQueue) {
					next();
				}
			});
		});
		
		$.WPVSlider.createCaptionFx(cfg, hideFunctions, showFunctions, callback || $.noop)();
	}
	
	function changeCaptionsOnGrid(cfg, callback) {
		var hideFunctions  = [];
		var showFunctions  = [];
		
		if (cfg.oldCaption.length) {
			hideFunctions.push(function (next) {
				cfg.oldCaption.animate({opacity: 0}, { 
					duration: cfg.speed,
					easing : cfg.easing,
					queue : false,
					complete : function() {
						$(this).css("zIndex", 50);
					}
				});
				next();
			});
		}
		
		if (cfg.newCaption.length) {
			showFunctions.push(function (next) {
				cfg.newCaption.css("opacity", 0).animate({opacity: 1}, { 
					duration: cfg.speed,
					easing : cfg.easing,
					queue : false,
					complete : function() {
						$(this).css("zIndex", 100);
					}
				});
				next();
			});
		}
		
		$.WPVSlider.createCaptionFx(cfg, hideFunctions, showFunctions, callback || $.noop)();
	}
	
	function changeCaptionsOnFade(cfg, callback) {
		var oldSubCaptions = cfg.oldCaption.find(".sub-caption");
		var newSubCaptions = cfg.newCaption.find(".sub-caption");
		var isQueue        = true;//cfg.slider.options.effect_settings.caption_queue;
		var hideFunctions  = [];
		var showFunctions  = [];
		
		$(newSubCaptions).css({opacity: 0});
		
		if (oldSubCaptions.length) {
			oldSubCaptions.each(function (i, o) {
				hideFunctions.push(function (next) {
					$(o).animate({opacity: 0}, { 
						duration: Math.ceil(cfg.speed/2),
						easing : cfg.easing,
						queue : false
					});
					next();
				});
			});
		}
		
		if (cfg.oldCaption.length) {
			hideFunctions.push(function (next) {
				cfg.oldCaption.animate({opacity: 0}, { 
					duration: Math.ceil(cfg.speed/2),
					easing : cfg.easing,
					queue : false,
					complete: next
				});
			});
		}
		
		if (cfg.newCaption.length) {
			showFunctions.push(function (next) {
				cfg.newCaption.css({opacity: 1});
				next();
			});
		}
		
		if (newSubCaptions.length) {
			showFunctions.push(function (next) {
				newSubCaptions.each(function (i, o) {
					setTimeout(function() {
						$(o).animate({opacity: 1}, { 
							duration: 400,
							easing : "swing",
							queue : false,
							complete : function() {
								if ( isQueue && i >= newSubCaptions.length - 1 ) {
									next();
								}
							}
						});
					}, i * 300);
				});
			});
		}
		
		$.WPVSlider.createCaptionFx(cfg, hideFunctions, showFunctions, callback || $.noop)();
	}
	
	function changeCaptionsOnSlide(cfg, callback) {
		var oldSubCaptions = cfg.oldCaption.find(".sub-caption");
		var newSubCaptions = cfg.newCaption.find(".sub-caption");
		var hideFunctions  = [];
		var showFunctions  = [];
		
		$(newSubCaptions).css({opacity: 0});
		
		hideFunctions.push(function (next) {
			if (oldSubCaptions.length) {
				oldSubCaptions.each(function(i, o) {
					$(o)
					.stop(1, 0)
					.animate(
						{ opacity: 0 }, 
						{ 
							duration : Math.ceil(cfg.speed/2),
							easing   : cfg.easing,
							queue    : false,
							complete : function() {
								//$(this).css(zIndex: -1});
								if (i >= oldSubCaptions.length - 1) {
									next();
								}
							}
						}
					);
				}).css({ zIndex: 50 });
			}
			else {
				next();
			}
		});
		
		
		if (cfg.oldCaption.length) {
			hideFunctions.push(function (next) {
				cfg.oldCaption.animate({opacity: 0}, 200, "swing", function() {
					$(this).css({ zIndex: 50 });
				});
				next();
			});
		}
		
		if (cfg.newCaption.length) {
			showFunctions.push(function (next) {
				cfg.newCaption.animate({opacity: 1}, 200, "swing", function() {
					$(this).css({ zIndex: 100 });
				});
				next();
			});
		}
		
		showFunctions.push(function (next) {
			if (newSubCaptions.length) {
				newSubCaptions.animate({opacity: 1}, { 
					duration: 400,
					easing : "swing",
					queue : false,
					complete : next
				}).css({ zIndex: 100 });
			}
			else {
				next();
			}
		});
		
		$.WPVSlider.createCaptionFx(cfg, hideFunctions, showFunctions, callback || $.noop)();
	}
	
	function changeCaptionsMulti(cfg, callback) {
		var oldSubCaptions = cfg.oldCaption.find(".sub-caption");
		var newSubCaptions = cfg.newCaption.find(".sub-caption");
		var hideFunctions  = [];
		var showFunctions  = [];
		
		$(newSubCaptions).css({opacity: 0, left : "-100%"});
		cfg.newCaption.css({ opacity: 0 });
		
		hideFunctions.push(function (next) {
			if (oldSubCaptions.length) {
				oldSubCaptions.each(function(i, o) {
					setTimeout(function() {
						$(o)
						.stop(1, 0)
						.animate({
							opacity: 0,
							left: -(o.offsetLeft + $(o).outerWidth()),
							avoidTransforms : true
						}, { 
							duration : 600,
							easing   : "easeInQuart",
							queue    : false,
							complete : function() {
								$(this).css("zIndex", 50);
								if (i >= oldSubCaptions.length - 1) {
									next();
								}
							}
						});
					}, 300 * i);
				});
			}
			else {
				next();
			}
		});
		
		if (cfg.oldCaption.length) {
			hideFunctions.push(function (next) {
				cfg.oldCaption.css({ opacity: 0, zIndex : 50 });
				next();
			});
		}
		
		if (cfg.newCaption.length) {
			showFunctions.push(function (next) {
				cfg.newCaption.css({ opacity: 1, zIndex : 100 });
				next();
			});
		}
		
		
		showFunctions.push(function (next) {
			if (newSubCaptions.length) {
				newSubCaptions.each(function(i, o) {
					setTimeout(function() {
						$(o).css({
							left : -$(o).outerWidth(),
							zIndex : 100
						}).animate({
							opacity: 1,
							left: 0,
							avoidTransforms : true
						}, { 
							duration : 600,
							easing   : "easeOutQuart",
							queue    : false,
							complete : function() {
								if (i >= newSubCaptions.length - 1) {
									next();
								}
							}
						});
					}, 300 * i);
				});
			}
			else {
				next();
			}
		});
		
		$.WPVSlider.createCaptionFx(cfg, hideFunctions, showFunctions, callback || $.noop)();
	}
	
	$.WPVSlider.fx.slideMultipleCaptions = {
		run: function () {
			return $.WPVSlider.fx.slide.run.apply(this, arguments);
		}
	};
	
	$.WPVSlider.fx.fadeMultipleCaptions = {
		run: function () {
			return $.WPVSlider.fx.fade.run.apply(this, arguments);
		}
	};
	
	$.WPVSlider.fx.zoomIn = {
		
		init : function(slider) {
			if ($("html").is(".csstransitions.csstransforms")) {
				slider.getSlideElements().eq(0).wpvAddClass(
					"current", 
					slider.options.animation_time,
					slider.options.effect_settings.easing
				);
			}
		},
		
		run: function (cfg) {

			cfg = $.extend({
				duration: 1000,
				easing: "easeOutSine"
			}, cfg);

			if ($("html").is(".csstransitions.csstransforms")) {
				cfg.toShow.wpvAddClass("current", cfg.duration, cfg.easing, 0, cfg.callback);
				cfg.toHide.wpvRemoveClass("current", cfg.duration, cfg.easing);
			}
			else {
				cfg.toHide.css({
					zIndex: 2
				});
				
				var content = $(cfg.toShow[0].firstChild);
				var targetHeight = cfg.toShow.height();
				var targetWidth = cfg.toShow.width();
				var halfX = targetWidth / 2;
				var halfY = targetHeight / 2;
				var contentHeight = content.height();
				var contentWidth = content.width();
			
				if (content.is("img")) {
					var diffY = Math.abs(parseFloat(content.css("marginTop" )));
					var diffX = Math.abs(parseFloat(content.css("marginLeft")));
					content.css({
						opacity: 0,
						position: "absolute",
						clip: "rect(" 
							+ (diffY + halfY) + "px " 
							+ (diffX + halfX) + "px " 
							+ halfY + "px " 
							+ halfX + "px)"
					}).show().animate({
						opacity: 1
					}, {
						duration: cfg.duration,
						easing: cfg.easing,
						step: function (cur, fx) {
							content[0].style.clip = "rect( " 
							+ (diffY - halfY * fx.pos) + "px " 
							+ (diffX + halfX * fx.pos) + "px " 
							+ (diffY + halfY * fx.pos) + "px " 
							+ (diffX - halfX * fx.pos) + "px)";
						},
						complete: function () {
							cfg.toHide.hide();
							content.css({
								clip: "auto"
							});
							cfg.callback();
						}
					});
					cfg.toShow.css({
						display: "block",
						zIndex: 3
					});
				} else {
					content.css({
						width: "100%",
						height: "100%"
					});

					cfg.toShow.css({
						opacity: 0,
						zIndex: 3,
						display: "block",
						position: "absolute",
						width: 0,
						height: 0,
						top: "50%",
						left: "50%"
					}).animate({
						opacity: 1,
						top: 0,
						left: 0,
						width: "100%",
						height: "100%"
					}, {
						duration: cfg.duration,
						easing: cfg.easing,
						complete: function () {
							cfg.toHide.hide();
							cfg.callback();
						}
					});
				}
			}
		}
	};

	$.WPVSlider.fx.slideAndFade = {
		run: function (cfg) {
			cfg = $.extend({
				duration: 800,
				easing: "easeOutExpo"
			}, cfg);
			
			var shiftX = cfg.toShow.width() * (cfg.dir == "prev" ? -1 : 1);
			
			cfg.toHide.css({
				//left   : 0.001,
				//opacity: 1,
				zIndex : 2
			});
			
			cfg.toShow.css({ 
				left: shiftX,
				zIndex : 3,
				opacity : 0,
				display : "block"
			});
			
			setTimeout(function() {
				cfg.toShow.css({ left: shiftX / 3 });
			
				cfg.toHide.animate({
					left: shiftX * -0.3,
					opacity : 0,
					avoidTransforms : true
				}, cfg.duration, cfg.easing, function () {
					$(this).css({ display: "none", zIndex : 2 });
				});
				
				
				cfg.toShow.animate({ 
					left : 0, 
					opacity: 1,
					avoidTransforms : true 
				}, cfg.duration, cfg.easing, cfg.callback);
				
				//cfg.toShow.css({ 
					//display : "block"
				//});
			}, 50);
		}
	};
	
	$.WPVSlider.fx.slide                .changeCaptions = changeCaptionsOnSlide;	
	$.WPVSlider.fx.fade                 .changeCaptions = changeCaptionsOnFade;
	$.WPVSlider.fx.zoomIn               .changeCaptions = changeCaptionsOnGrid;
	$.WPVSlider.fx.slideAndFade         .changeCaptions = changeCaptionsMulti;
	$.WPVSlider.fx.fadeMultipleCaptions .changeCaptions = changeCaptionsMulti;
	$.WPVSlider.fx.slideMultipleCaptions.changeCaptions = changeCaptionsMulti;
	
	
})(jQuery);