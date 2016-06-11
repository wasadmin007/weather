/**
* BrainyFilter 2.2, April 19, 2014 / brainyfilter.com
* Copyright 2014 Giant Leap Lab / www.giantleaplab.com
* License: Commercial. Reselling of this software or its derivatives is not allowed. You may use this software for one website ONLY including all its subdomains if the top level domain belongs to you and all subdomains are parts of the same OpenCart store.
* Support: support@giantleaplab.com
*/
var BrainyFilter = {
	
	ajaxHandler: null,
	
	sliderId: "#bf-slider-range",
	
	filterFormId: "#ps-module-filter-form",
	
	maxFieldId: "[name='higher']",
	
	minFieldId: "[name='lower']",
	
	max: 0,
	
	min: 0,
	
	currency: {rate: 1, symbol: ''},
	
	submitType: 'auto',
	
	submitBtnType: 'fixed',
	
	submitDelay: 2000,
	
	timeout: null,
	
	urlSeparators: null,
	
	hidePanel: true,

	priceFilter: true,
	
	selectionCache: {},
	
	enableSliding: false,
	
	limitHeight: false,

	limitHeightOpts: null,

	visibleItems: null,

	hideItems: null,
	
	productCounts: true,
	
	init: function(opts) {
		this.max = opts.max;
		this.min = opts.min;
		this.priceFilter = opts.price_filter;
		this.currency.symbol = opts.currencySymbol;
		this.currency.symbolSide = opts.currencySymbolSide;
		this.submitType = opts.submitType || this.submitType;
		this.submitBtnType = opts.submitBtnType || this.submitBtnType;
		this.urlSeparators = opts.urlSeparators;
		this.hidePanel = opts.hidePanel;
		this.enableSliding = opts.enableSliding;
		this.limitHeightOpts = opts.limitHeightOpts;
		this.limitHeight = opts.limitHeight;
		this.visibleItems = opts.visibleItems;
		this.hideItems = opts.hideItems;
		this.productCounts = opts.productCounts;
		this.submitDelay = opts.submitDelay;
		if (this.productCounts) {
			this.getTotalByAttr();
		}
		
		if (this.priceFilter) {
			jQuery(this.sliderId)[0].slide = null;
			jQuery(this.sliderId).slider({
				range: true,
				min: opts.min,
				max: opts.max,
				values: [opts.lowerValue, opts.higherValue],
				slide: function( event, ui ) {
					jQuery(BrainyFilter.minFieldId).val(ui.values[0]);
					jQuery(BrainyFilter.maxFieldId).val(ui.values[1]);
				},
				stop : BrainyFilter.currentSendMethod
			});
			jQuery('#bf-price-container input').keyup(function(e) {
				var index = (jQuery(this).attr('id') == "bf-range-max") ? 1 : 0;
				jQuery(BrainyFilter.sliderId).slider("values", index, jQuery(this).val());
			});
		}

		jQuery(this.filterFormId).find('select, input').change(this.currentSendMethod);
			
		jQuery(this.filterFormId).find('input[type="checkbox"], input[type="radio"]').change(function(){
			if (!jQuery(this).parents('.bf-attr-filter').find('.bf-count').size()) {
				BrainyFilter.addCross(jQuery(this));
			}
		});
		
		jQuery(this.filterFormId).find('input[type="checkbox"], input[type="radio"]').each(function(i,v){
			BrainyFilter.addCross(jQuery(v));
		});
		
		if (this.submitType == 'button' && this.submitBtnType == 'float') {
			this.floatSubmit();
		}
		
		jQuery(this.filterFormId).submit(function(){
			BrainyFilter.sendRequest();
			return false;
		});
		
		this.selectionCache = jQuery(this.filterFormId).serialize();
		
		this.initSliding();

		this.collapse();
		
		// check whether our changes were applied successfully via vqMode,
		// and we have product container for ajax job on the page
		if (!jQuery('#brainyfilter-product-container').size()) {
			// hide panel if there is not products container on the page
			jQuery('#brainyfilter-panel').css('opacity', '0.3');
			jQuery('#brainyfilter-panel').append('<div class="ajax-shadow"></div>');
			jQuery('#brainyfilter-panel .ajax-shadow').fadeIn(200);
			// show error message
			jQuery('#notification').append('<div class="warning">' + bfLang.vqm_error + '</div>');
		}
	},
	
	

	addCross: function(checkbox) {
		var parent = checkbox.parents('.bf-attr-filter').eq(0);
		if(checkbox[0].checked) { 
			if (checkbox.is("input[type='radio']")) {
				checkbox.parents('.bf-attr-block').find('.bf-cross').remove();
			}
			var cross = jQuery('<span class="bf-cross"></span>');
			cross.click(function(){
				jQuery(this).parents('.bf-attr-filter').find('input').removeAttr('checked');
				BrainyFilter.currentSendMethod();
				jQuery(this).hide();
				setTimeout(function(){jQuery(this).remove();}, 500);
			});
			parent.find('.bf-c-3').html(cross);
		} else {
			parent.find('.bf-cross').remove();
		}

	},
	
	reset: function() {
		if (jQuery('.bf-buttonclear').is('[disabled]')) {
			return;
		}
		jQuery(this.sliderId).slider("option", "values", [this.min, this.max]);
		jQuery(this.filterFormId + ' #bf-price-container [name=lower]').val(this.min);
		jQuery(this.filterFormId + ' #bf-price-container [name=higher]').val(this.max);
		jQuery(this.filterFormId + ' input').filter(':checkbox, :radio').removeAttr('checked');
		jQuery(this.filterFormId + ' option').removeAttr('selected');
		jQuery(this.filterFormId + ' option.bf-default').attr('selected', 'true');
		jQuery(this.filterFormId).find('.bf-cross').remove();
		this.sendRequest();
		var disableBtn = function() {
			jQuery('.bf-buttonclear').attr('disabled', 'disabled');
			jQuery(document).ajaxStop(enableBtn);
		}
		var enableBtn  = function() {
			jQuery(document).unbind('ajaxStart', disableBtn);
			jQuery(document).unbind('ajaxStop', enableBtn);
			jQuery('.bf-buttonclear').removeAttr('disabled');
		}
		jQuery(document).ajaxStart(disableBtn);
	},
	
	currentSendMethod: function(){
		switch(BrainyFilter.submitType) {
			case 'auto':
				BrainyFilter.sendRequest();
				break;
			case 'delay':
				if (BrainyFilter.timeout) {
					clearTimeout(BrainyFilter.timeout);
				}
				BrainyFilter.timeout = setTimeout(BrainyFilter.sendRequest, BrainyFilter.submitDelay);
				break;
			default:
				break;
		}
	},
	
	sendRequest: function() {
		if (!isIE()) {
   		
			if (BrainyFilter.hidePanel) {
				// hide results until response will be recieved
				jQuery('#brainyfilter-panel').css('opacity', '0.3');
				jQuery('#brainyfilter-panel').append('<div class="ajax-shadow"></div>');
				jQuery('#brainyfilter-panel .ajax-shadow').fadeIn(200);
			}
			if(this.ajaxHandler && this.ajaxHandler.readystate != 4){
				this.ajaxHandler.abort();
			}
			
			this.ajaxHandler = jQuery.ajax({
				url: 'index.php' + BrainyFilter.prepareFilterData(false, 'module/brainyfilter/ajaxfilter'),
				dataType:'json',
				type: 'get',
				success: function(res) {
					if (res) {
						jQuery('#brainyfilter-product-container').replaceWith(res.products);
						if (BrainyFilter.productCounts) {
							BrainyFilter.changeTotalNumbers(res.brainyfilter);
						}
						display(jQuery.totalStorage('display'));
						if (BrainyFilter.priceFilter) {
							BrainyFilter.changeSlider(res.min, res.max);
						};
					}
					jQuery(BrainyFilter.filterFormId).find('input[type="checkbox"], input[type="radio"]').each(function(i,v){
						BrainyFilter.addCross(jQuery(v));
					});
					var curRoute = jQuery(BrainyFilter.filterFormId).find('[name="route"]').val();
					var newUrl   = BrainyFilter.prepareFilterData(true, curRoute);
					
					window.history.pushState({"html": res['products'], "pageTitle": jQuery('title').text()}, "", newUrl);
					window.onpopstate = function(e){
					    if(e.state){
					        document.getElementById("brainyfilter-product-container").innerHTML = e.state.html;
					        document.title = e.state.pageTitle;
					    }
					};
					
					BrainyFilter.selectionCache = jQuery(BrainyFilter.filterFormId).serialize();
				},
				complete: function() {
					if (BrainyFilter.hidePanel) {
						jQuery('#brainyfilter-panel .ajax-shadow').remove();
						jQuery('#brainyfilter-panel').animate({opacity: 1}, 200);
					}
				}
			}); 
		}else{
			var curRoute = jQuery(BrainyFilter.filterFormId).find('[name="route"]').val();
			var newUrl   = BrainyFilter.prepareFilterData(true, curRoute);
			window.location = newUrl;
		}
	}, 
	
	prepareFilterData: function(full, route) {
		var path, query, params;
		path  = window.location.pathname;
		query = window.location.search.replace(/[\&\?]((lower)|(higher)|(attribute_value)|(route)|(path)|(page)|(manufacturer)|(bfilter)|(stock_status)|(filter)|(bfrating)|(bfoption))[^&]+/g, '');
		
		jQuery(this.filterFormId).find('.bf-disabled input').removeAttr('disabled');
		
		var form  = jQuery(this.filterFormId).serialize();
		
		jQuery(this.filterFormId).find('.bf-disabled input').attr('disabled', 'disabled');
		form = '&' + form;
		form = form.replace(/(([\&]?attribute_value|bfoption)[^\=]+\=)(($)|(&))/g, '$3')
				   .replace(/\&route=[^\&]+/g, '').replace(/[\&]+$/, '');
		
		form = '&route=' + route + form;
		if (jQuery(BrainyFilter.minFieldId).val() == BrainyFilter.min) {
			form = form.replace(/\&lower\=[^\&]+/g, '');
		}
		if (jQuery(BrainyFilter.maxFieldId).val() == BrainyFilter.max) {
			form = form.replace(/\&higher\=[^\&]+/g, '');
		}

		var str= window.location.toString();
		str = str.match(/[\?\&]route\=/);
		if (full == true && !str) {
			form = form.replace(/[\&\?]((route)|(path))[^&]+/g, '')
		}
		params = query + form;
		params = BrainyFilter.generateSeoUrl(params);
		params = params.replace(/(\?)|(^[\&])/, '').replace(/[\&]+/, '&');
		params = '?' + params;
		
		

		return (full) ? path + params : params;
	},
	
	getTotalByAttr: function() {
		jQuery.ajax({
			url: 'index.php' + BrainyFilter.prepareFilterData(false, 'module/brainyfilter/ajaxcountattributes'),
			dataType:'json',
			type: 'get',
			success: this.changeTotalNumbers
		}); 
	},
	
	changeTotalNumbers: function(data) {
		jQuery(BrainyFilter.filterFormId).find('.bf-count').remove();
		jQuery(BrainyFilter.filterFormId).find('option span').remove();
		jQuery(BrainyFilter.filterFormId).find('select').removeAttr('disabled');
		jQuery('.bf-attr-filter').not('#bf-price-container').find('input, option')
			.attr('disabled', 'disabled')
			.parents('.bf-attr-filter')
			.addClass('bf-disabled');
		if (data && data.length) {
			for (var i = 0; i < data.length; i ++) {
				jQuery('.bf-attr-' + data[i].id + ' .bf-attr-val').each(function(ii, v) {
					var curVal = (jQuery(v).attr('value')) ? jQuery(v).attr('value') : jQuery(v).text();
					data[i].val = $('<div/>').html(data[i].val).text();
                    if (curVal.replace(/[\r\n]/g, '') == data[i].val.replace(/[\r\n]/g, '')) {
						var parent = jQuery(v).parents('.bf-attr-filter').eq(0);
						var isOption = jQuery(v).prop('tagName') == 'OPTION' ;
						var selected = false;
						if (isOption) {
							jQuery(v).removeAttr('disabled');
							selected = jQuery(v)[0].selected;
						} else {
							parent.find('input').removeAttr('disabled');
							selected = parent.find('input')[0].checked;
						}
						parent.removeClass('bf-disabled');
						if (!selected) {
							if (!isOption) {
								parent.find('.bf-cell').last().append('<span class="bf-count">' + data[i].c + '</span>');
							} else {
								jQuery(v).append('<span> (' + data[i].c + ')</span>');
							}
						}
					}
				});
			}
		
			jQuery('.bf-attr-filter input[type=checkbox]').filter(':checked')
				.parents('.bf-attr-block').find('.bf-count').each(function(i, v){
				var t = '+' + jQuery(v).text();
				jQuery(v).text(t);
			});
			// since opencart standard filters use logical OR, all the filter groups
			// should have '+' if any filter was selected
			if (jQuery('.bf-opencart-filters input[type=checkbox]:checked').size()) {
				jQuery('.bf-opencart-filters .bf-count').each(function(i, v){
					var t = '+' + jQuery(v).text().replace('+', '');
					jQuery(v).text(t);
				});
			}
		}
		// disable select box if it hasn't any active option
		jQuery(BrainyFilter.filterFormId).find('select').each(function(i, v){
			if (jQuery(v).find('option').not('.bf-default,[disabled]').size() == 0) {
				jQuery(v).attr('disabled', 'true');
			}
		});
	},
	
	changeSlider: function(min, max) {
		var vals = jQuery(this.sliderId).slider('option', 'values');
		var curMin = jQuery(this.sliderId).slider('option', 'min');
		var curMax = jQuery(this.sliderId).slider('option', 'max');
		min = parseFloat(min);
		max = parseFloat(max);
		
		this.max = max;
		this.min = min;
		jQuery(this.sliderId).slider('option', 'min', min);
		jQuery(this.sliderId).slider('option', 'max', max);
		if (vals[0] == curMin || vals[0] < min) {
			vals[0] = min;
		}
		if (vals[1] == curMax || vals[1] > max) {
			vals[1] = max;
		}
		if (vals[0] > vals[1]) {
			vals[1] = vals[0];
		}
		jQuery(this.sliderId).slider('option', 'values', vals);
		
		jQuery(BrainyFilter.minFieldId).val(vals[0]);
		jQuery(BrainyFilter.maxFieldId).val(vals[1]);
	},
	
	generateSeoUrl: function(query) {
		if (this.urlSeparators == null) {
			return query;
		}
		var params = query.split('&');
		var attrObj = {};
		var price = ['na', 'na'];
		var brands = [];
		var stockSt = [];
		var filter = [];
		var bfrating = [];
		var option = [];
		if (params.length) {
			for (var i = 0; i < params.length; i ++) {
				
				var parts = params[i].replace(/\+/g, ' ').split('=');
				var param = decodeURIComponent(parts[0]);
				var value = decodeURIComponent(parts[1]);

				if (param.match(/attribute_value\[[\d]+\]\[\]/)) {
					var attrId = param.replace(/([\&]?attribute_value\[)([\d]+)(\]\[\])/, '$2');
					var attrNm = jQuery('.bf-attr-header.bf-attr-' + attrId).text();
					if (typeof attrObj[attrId] == 'undefined') {
						attrObj[attrId] = {name: attrNm, values: [encodeURIComponent(value)]};
					} else {
						attrObj[attrId].values.push(encodeURIComponent(value));
					}
				} else if (param == 'lower') {
					price[0] = value;
				} else if (param == 'higher') {
					price[1] = value;
				} else if (param.match(/manufacturer\[\]/)) {
					brands.push(value);
				} else if (param.match(/stock_status\[\]/)) {
					stockSt.push(value);
				} else if (param.match(/filter\[\]/)) {
					filter.push(value);
				} else if (param.match(/bfrating\[\]/)) {
					bfrating.push(value);
				} else if (param.match(/bfoption\[\]/)) {
					option.push(value);
				}
			}
		}
		
		var arr = [];
		if (price[0] !== 'na' || price[1] !== 'na') {
			arr.push('price' 
				+ this.urlSeparators.valsOpen 
				+ price[0] + '-' + price[1] 
				+ this.urlSeparators.valsClose);
		}
		if (brands.length) {
			arr.push('brand'
				+ this.urlSeparators.valsOpen
				+ brands.join(this.urlSeparators.val)
				+ this.urlSeparators.valsClose);
		}
		if (stockSt.length) {
			arr.push('status'
				+ this.urlSeparators.valsOpen
				+ stockSt.join(this.urlSeparators.val)
				+ this.urlSeparators.valsClose);
		}
		if (bfrating.length) {
			arr.push('rating'
				+ this.urlSeparators.valsOpen
				+ bfrating.join(this.urlSeparators.val)
				+ this.urlSeparators.valsClose);
		}
		if (option.length) {
			arr.push('option'
				+ this.urlSeparators.valsOpen
				+ option.join(this.urlSeparators.val)
				+ this.urlSeparators.valsClose);
		}
		for (var id in attrObj) {
			arr.push(id + '-' + encodeURIComponent(attrObj[id].name) 
				+ this.urlSeparators.valsOpen 
				+ attrObj[id].values.join(this.urlSeparators.val) 
				+ this.urlSeparators.valsClose);
		}
		query = query.replace(/[\&\?]((attribute_value)|(bfilter)|(higher)|(lower)|(manufacturer)|(stock_status)|(filter)|(bfrating)|(bfoption))[^&]+/g, '');
		if (filter.length) {
			query += '&filter=' + filter.join(',');
		}
		if (arr.length) {
			query += '&bfilter=' + arr.join(this.urlSeparators.attr);
		}
		return query;
	},
	
	floatSubmit: function() {
		var btn      = jQuery('.bf-buttonsubmit');
		var closeBtn = jQuery('<div class="bf-close-btn"></div>');
		var tick     = jQuery('<div class="bf-tick"></div>');
		var panel    = jQuery('<div class="bf-float-submit"></div>').prepend(tick)
				.append(btn).append(closeBtn);
		jQuery('body').append(panel);
		panel.css('display', 'none');
		
		var timer = null;
		var hideBtn = function(){
			jQuery('.bf-float-submit').fadeOut(400);
		}
		closeBtn.click(hideBtn);
		var showBtn = function(){
			var form = jQuery(BrainyFilter.filterFormId).serialize();
			if (BrainyFilter.selectionCache == form) {
				hideBtn();
				return;
			}
			var outBlockOffset = jQuery('#brainyfilter-panel').eq(0).offset();
			var blockOffset = jQuery(this).parents('.bf-attr-filter').eq(0).offset();
			var blockHeight = jQuery(this).parents('.bf-attr-filter').eq(0).outerHeight();
			var panelHeight = panel.outerHeight();
			if (panel.css('display') == 'block') {
				panel.animate({top: blockOffset.top + (blockHeight - panelHeight) / 2}, 300);
			} else {
				panel.css('display', 'block');
				var blockWidth  = jQuery('#brainyfilter-panel').eq(0).outerWidth();
				panel.offset({top: blockOffset.top, left: outBlockOffset.left + blockWidth - 4});
				panel.css({top: blockOffset.top + (blockHeight - panelHeight) / 2});
			}
			if (timer) {
				clearTimeout(timer);
			}
			timer = setTimeout(hideBtn, 10000);
		};
		jQuery(BrainyFilter.filterFormId).find('input, select').not('[type="text"]').change(showBtn);
		jQuery(BrainyFilter.filterFormId).find('input[type="text"]').keyup(showBtn);
		jQuery(BrainyFilter.filterFormId + ' .bf-c-3').on('click', '.bf-cross', showBtn);
		jQuery(BrainyFilter.sliderId).on( "slidestop", showBtn);
	},
	
	loadingAnimation: function() {
		jQuery('.bf-tick').addClass('bf-loading');
		var stopSpin = function(){
			jQuery('.bf-tick').removeClass('bf-loading');
			jQuery(document).unbind('ajaxComplete', stopSpin);
			if (BrainyFilter.submitType == 'button' && BrainyFilter.submitBtnType == 'float') {
				jQuery('.bf-float-submit').css('display', 'none');
			}
		};
		jQuery(document).ajaxComplete(stopSpin);
	},

	initSliding: function() {
		jQuery(this.filterFormId).find('.bf-attr-block-cont').each(function(i, v) {
			jQuery(v).wrap('<div class="bf-sliding"></div>');
			jQuery(v).parent().wrap('<div class="bf-sliding-cont"></div>');
			var wrapper = jQuery(v).parent();
			wrapper.addClass('bf-expanded');
			if (BrainyFilter.enableSliding) {
				var count = jQuery(v).find('.bf-attr-filter').size() - BrainyFilter.visibleItems;
				if ( count > 0 && count >= BrainyFilter.hideItems) {
					BrainyFilter.shrinkBlock(v, BrainyFilter.visibleItems);
				}
			}else if(BrainyFilter.limitHeight) {
				if ( jQuery(v).parents('.bf-sliding').height() > BrainyFilter.limitHeightOpts) {
					jQuery(v).parents('.bf-sliding').css({'height':BrainyFilter.limitHeightOpts, 'overflow-x': 'hidden', 'overflow-y': 'auto'});
				};
			}
			
			jQuery(v).parents('.bf-attr-block').find('.bf-attr-header').addClass('bf-clickable');
			jQuery(v).parents('.bf-attr-block').find('.bf-attr-header').click(function(){
				if ( wrapper.hasClass('bf-expanded') ) {
					BrainyFilter.shrinkBlock(v, 0);
				} else {
					BrainyFilter.expandBlock(v)
				}
			});
		});
	},
	
	shrinkBlock: function(block, items) {
		var count   = jQuery(block).find('.bf-attr-filter').size() - this.visibleItems;
		var height  = 0;
		var wrapper = jQuery(block).parents('.bf-sliding-cont');
		var showMore = wrapper.find('.bf-sliding-show').hide();
		if (items) {
			jQuery(block).find('.bf-attr-filter').each(function(j, vv){
				if (j < items) {
					height += jQuery(vv).height();
				}
			});
			if (!showMore.size()) {
				wrapper.append('<div class="bf-sliding-show" ></div>'); 
			}
			wrapper.find('.bf-sliding-show')
				.text(bfLang.show_more + ' (' + count + ')')
				.unbind('click')
				.show()
				.click(function() {
					BrainyFilter.expandBlock(block);
				})
		}


		jQuery(block).parents('.bf-sliding').stop().animate({height: height}, 300);
		if (!items) {
			jQuery(block).parent().removeClass('bf-expanded');
			jQuery(block).parents('.bf-attr-block').find('.bf-arrow').css('background-position', '50% -128px');
		}
	},
	
	expandBlock: function(block) {
		var fullHeight = jQuery(block).height();
		var wrapper    = jQuery(block).parent();
		if(BrainyFilter.limitHeight && BrainyFilter.limitHeightOpts < fullHeight) {
			fullHeight = BrainyFilter.limitHeightOpts;
		}
		wrapper.stop().animate({height : fullHeight}, 300);
		wrapper.parent().find('.bf-sliding-show')
			.text(bfLang.show_less)
			.unbind('click')
			.show()
			.click(function() {
				BrainyFilter.shrinkBlock(block, BrainyFilter.visibleItems);
			});
		wrapper.addClass('bf-expanded');
		jQuery(block).parents('.bf-attr-block').find('.bf-arrow').css('background-position', '50% -153px');
	},

	collapse: function() {
		var height  = 0;
		jQuery('.collapse').parents('.bf-attr-block').find('.bf-sliding').removeClass('bf-expanded');
		jQuery('.collapse').parents('.bf-attr-block').find('.bf-sliding-show').hide();
		jQuery('.collapse').parents('.bf-attr-block').find('.bf-arrow').css('background-position', '50% -128px');
		jQuery('.collapse').parents('.bf-attr-block').find('.bf-sliding').stop().animate({height: height}, 300);
	
	}

}
function isIE(){
	if ((document.all && document.querySelector && !document.addEventListener) 
	 || (document.all && !document.querySelector) 
	 || (document.all && document.querySelector && document.addEventListener && 
	!window.atob)) {
		return true;
	}else{
		return false;
	}
}