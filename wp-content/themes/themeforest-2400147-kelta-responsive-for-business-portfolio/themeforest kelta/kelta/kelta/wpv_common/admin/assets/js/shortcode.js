var wpv_shortcode_last_update = 0;

(function($, undefined) {

var shortcode = {
	preview: function() {
		if(Date.parse(new Date()) - wpv_shortcode_last_update > 1000) {
			wpv_shortcode_last_update = Date.parse(new Date());
				
			$('#shortcode_preview_content').val(shortcode.generate()).parent().submit();
		}
	},
	
	init: function() {
		$('#shortcodes :checkbox').iButton();
		$('#shortcodes .wpv-range-input').uirange();
		
		$('.shortcode_send').click(function() {
			$.colorbox.close();
			shortcode.sendToEditor();
			return false;
		});
		
		$('.shortcode_content *').change(function(e) {
			shortcode.preview();
		}).change();
		
		$('a[href="#shortcode_preview"]').click(function() {
			shortcode.preview();
		});
		
		$('.shortcode_sub_selector select').val('');
		$('.shortcode_sub_selector select').change(function() {
			$('.sub_shortcode_wrap').hide();
			if($(this).val() !='')
				$("#sub_shortcode_" + $(this).val()).show();
		});
		
		$('[name="sc_tabs_number"]').change(function() {
			var tabs_number = $(this).val();
			$('#shortcode_tabs .wpv-config-row').each(function(i){
				if(i > tabs_number*2 +3)
					$(this).hide();
				else
					$(this).show();
			});
		}).change();
		
		$('[name="sc_accordion_number"]').change(function() {
			var acc_number = $(this).val();
			$('#shortcode_accordion .wpv-config-row').each(function(i){
				if(i > acc_number*2 + 1)
					$(this).hide();
				else
					$(this).show();
			});
		}).change();
		
		$('[name="sc_slider_number"]').change(function() {
			var slides_number = parseInt($(this).val(), 10)-1;
			$('#shortcode_slider .wpv-config-row.slide-config').show();
			$('#shortcode_slider .wpv-config-row.slide-config:gt('+slides_number+')').hide();
		}).change();
		
		$('[name="sc_showcase_slides"], [name="sc_showcase_columns"], [name="sc_showcase_annotation"]').change(function() {
			var slides_number = parseInt($('[name="sc_showcase_slides"]').val(), 10) * 
									(
										$('[name="sc_showcase_annotation"]').val().length>0 ?
											parseInt($('[name="sc_showcase_columns"]').val(), 10)-1 :
											parseInt($('[name="sc_showcase_columns"]').val(), 10)
									);
									
			$('#shortcode_showcase .wpv-config-row').show();
			$('#shortcode_showcase .wpv-config-row:nth-child(n+'+(slides_number+12)+')').hide();
		}).change();
		
		$('.slide-type').change(function() {
			$(this).closest('.wpv-config-row').attr('data-slide-type', $(this).val());
		});

		if(window.tinyMCE && tinyMCE.activeEditor) {
			var selection = tinyMCE.activeEditor.selection.getContent();
			if($('body').attr('data-wpvshortcode') == 'table') {
				shortcode.table_element = $(tinyMCE.activeEditor.selection.getNode()).parents('table.mceItemTable:first');
				if(shortcode.table_element.length > 0) {
					shortcode.table_element.wrap('<div />');
					selection = shortcode.table_element.parent().html();
					shortcode.table_element.unwrap();
				}
			}

			var fill_with_selection = [
				'styled_boxes_framed_box_content',
				'styled_boxes_messageboxes_content',
				'styled_boxes_note_box_content',
				'tooltip_content',
				'typography_dropcap1_text',
				'typography_dropcap2_text',
				'typography_dropcap3_text',
				'typography_dropcap4_text',
				'typography_blockquote_content',
				'typography_styledlist_content',
				'typography_icon_text',
				'typography_highlight_content',
				'toggle_content',
				'table_content',
				'text_divider_text',
				'outer_text_divider_text'
			];
				
			for(i in fill_with_selection) {
				$('#sc_'+fill_with_selection[i]).val(selection).change();
			}
		}
	},

	fields: {
		blog: {
			atts: ['count', 'posts', 'cat', 'image', 'img_style', 'meta', 'full', 'nopaging', 'width', 'news', 'split']
		},
		button: {
			atts: ['id', 'class', 'size', 'align', 'link', 'linkTarget', 'color', 'bgColor'],
			content: 'text'
		},
		chart: {
			atts: ['data', 'labels', 'colors', 'bg', 'size', 'title', 'type', 'advanced']
		},
		image: {
			atts: ['title', 'align', 'lightbox', 'group', 'width', 'height', 'autoHeight', 'link', 'frame', 'underline', 'link_class'],
			content: 'src'
		},
		outer_text_divider: {
			atts: ['background', 'icon'],
			content: 'text'
		},
		price: {
			atts: ['text_align', 'price', 'price_size', 'price_background', 'price_color', 'title', 'title_size', 'title_background', 'title_color', 'description_color', 'description_background', 'button_text', 'button_link', 'currency', 'duration', 'summary', 'featured'],
			content: 'description'
		},
		services: {
			atts: ['icon', 'text_align', 'title', 'title_size', 'description_size', 'button_text', 'button_link', 'no_button', 'fullimage', 'class', 'image_height'],
			content: 'description'
		},
		services_expandable: {
			atts: ['image', 'title', 'class', 'background'],
			content: 'content'
		},
		slider: {
			atts: ['width', 'height', 'caption_opacity', 'effect', 'animspeed', 'pausetime', 'pauseonhover', 'style', 'annotation', 'highlight'],
			content: {
				code: 'slide',
				number: 'number',
				atts: ['image'],
				content: 'html'
			}
		},
		slogan: {
			atts: ['button_text', 'link', 'nopadding', 'carved', 'background', 'text_color', 'font'],
			content: 'text'
		},
		team_member: {
			atts: ['name', 'position', 'phone', 'url', 'picture'],
			content: 'description'
		},
		text_divider: {
			atts: ['more'],
			content: 'text'
		},
		toggle: {
			atts: ['title', 'hidden', 'counter'],
			content: 'content'
		},
		tooltip: {
			atts: ['tooltip_content'],
			content: 'content'
		}
	},

	generate: function() {
		var type = $('body').attr('data-wpvshortcode');

		if(shortcode.fields[type])
			return shortcode.build(type, shortcode.fields[type]);

		switch(type) {
			
		case 'column':
			var type = shortcode.getVal('column', 'type');
			if(type != '')
				return '\n['+type+']'+shortcode.getVal('column', 'content')+'[/'+type+']';
				
			return '';
		break;
		
		case 'columns':
			var sub_type = shortcode.getVal('columns','selector');
			switch(sub_type) {
			
			case 'one_half_layout':
				return '\n[one_half]'+shortcode.getVal('columns', 'one_half_layout', '1')+'[/one_half]' +
					   '\n[one_half_last]'+shortcode.getVal('columns', 'one_half_layout', '2')+'[/one_half_last]';
				
			case 'one_third_layout':
				return '\n[one_third]'+shortcode.getVal('columns', 'one_third_layout', '1')+'[/one_third]' +
					   '\n[one_third]'+shortcode.getVal('columns', 'one_third_layout', '2')+'[/one_third]' +
					   '\n[one_third_last]'+shortcode.getVal('columns', 'one_third_layout', '3')+'[/one_third_last]';
					   
			case 'one_fourth_layout':
				return '\n[one_fourth]'+shortcode.getVal('columns', 'one_fourth_layout', '1')+'[/one_fourth]' +
					   '\n[one_fourth]'+shortcode.getVal('columns', 'one_fourth_layout', '2')+'[/one_fourth]' +
					   '\n[one_fourth]'+shortcode.getVal('columns', 'one_fourth_layout', '3')+'[/one_fourth]' +
					   '\n[one_fourth_last]'+shortcode.getVal('columns', 'one_fourth_layout', '4')+'[/one_fourth_last]';

			case 'one_fifth_layout':
				return '\n[one_fifth]'+shortcode.getVal('columns', 'one_fifth_layout', '1')+'[/one_fifth]' +
					   '\n[one_fifth]'+shortcode.getVal('columns', 'one_fifth_layout', '2')+'[/one_fifth]' +
					   '\n[one_fifth]'+shortcode.getVal('columns', 'one_fifth_layout', '3')+'[/one_fifth]' +
					   '\n[one_fifth]'+shortcode.getVal('columns', 'one_fifth_layout', '4')+'[/one_fifth]' +
					   '\n[one_fifth_last]'+shortcode.getVal('columns', 'one_fifth_layout', '5')+'[/one_fifth_last]';

			case 'one_sixth_layout':
				return '\n[one_sixth]'+shortcode.getVal('columns', 'one_sixth_layout', '1')+'[/one_sixth]' +
					   '\n[one_sixth]'+shortcode.getVal('columns', 'one_sixth_layout', '2')+'[/one_sixth]' +
					   '\n[one_sixth]'+shortcode.getVal('columns', 'one_sixth_layout', '3')+'[/one_sixth]' +
					   '\n[one_sixth]'+shortcode.getVal('columns', 'one_sixth_layout', '4')+'[/one_sixth]' +
					   '\n[one_sixth]'+shortcode.getVal('columns', 'one_sixth_layout', '5')+'[/one_sixth]' +
					   '\n[one_sixth_last]'+shortcode.getVal('columns', 'one_sixth_layout', '6')+'[/one_sixth_last]';

			case 'one_third_two_thirds':
				return '\n[one_third]'+shortcode.getVal('columns', 'one_third_two_thirds', '1')+'[/one_third]' +
					   '\n[two_thirds_last]'+shortcode.getVal('columns', 'one_third_two_thirds', '2')+'[/two_thirds_last]';

			case 'two_thirds_one_third':
				return '\n[two_thirds]'+shortcode.getVal('columns', 'two_thirds_one_third', '1')+'[/two_thirds]' +
					   '\n[one_third_last]'+shortcode.getVal('columns', 'two_thirds_one_third', '2')+'[/one_third_last]';

			case 'one_fourth_three_fourths':
				return '\n[one_fourth]'+shortcode.getVal('columns', 'one_fourth_three_fourths', '1')+'[/one_fourth]' +
					   '\n[three_fourths_last]'+shortcode.getVal('columns', 'one_fourth_three_fourths', '2')+'[/three_fourths_last]';

			case 'three_fourths_one_fourth':
				return '\n[three_fourths]'+shortcode.getVal('columns', 'three_fourths_one_fourth', '1')+'[/three_fourths]' +
					   '\n[one_fourth_last]'+shortcode.getVal('columns', 'three_fourths_one_fourth', '2')+'[/one_fourth_last]';

			case 'one_fourth_one_fourth_one_half':
				return '\n[one_fourth]'+shortcode.getVal('columns', 'one_fourth_one_fourth_one_half', '1')+'[/one_fourth]' +
					   '\n[one_fourth]'+shortcode.getVal('columns', 'one_fourth_one_fourth_one_half', '2')+'[/one_fourth]' +
					   '\n[one_half_last]'+shortcode.getVal('columns', 'one_fourth_one_fourth_one_half', '3')+'[/one_half_last]';

			case 'one_fourth_one_half_one_fourth':
				return '\n[one_fourth]'+shortcode.getVal('columns', 'one_fourth_one_half_one_fourth', '1')+'[/one_fourth]' +
					   '\n[one_half]'+shortcode.getVal('columns', 'one_fourth_one_half_one_fourth', '2')+'[/one_half]' +
					   '\n[one_fourth_last]'+shortcode.getVal('columns', 'one_fourth_one_half_one_fourth', '3')+'[/one_fourth_last]';

			case 'one_half_one_fourth_one_fourth':
				return '\n[one_half]'+shortcode.getVal('columns', 'one_half_one_fourth_one_fourth', '1')+'[/one_half]' +
					   '\n[one_fourth]'+shortcode.getVal('columns', 'one_half_one_fourth_one_fourth', '2')+'[/one_fourth]' +
					   '\n[one_fourth_last]'+shortcode.getVal('columns', 'one_half_one_fourth_one_fourth', '3')+'[/one_fourth_last]';

			case 'four_fifths_one_fifth':
				return '\n[four_fifths]'+shortcode.getVal('columns', 'four_fifths_one_fifth', '1')+'[/four_fifths]' +
					   '\n[one_fifth_last]'+shortcode.getVal('columns', 'four_fifths_one_fifth', '2')+'[/one_fifth_last]';

			case 'one_fifth_four_fifths':
				return '\n[one_fifth]'+shortcode.getVal('columns', 'one_fifth_four_fifths', '1')+'[/one_fifth]' +
					   '\n[four_fifths_last]'+shortcode.getVal('columns', 'one_fifth_four_fifths', '2')+'[/four_fifths_last]';

			case 'two_fifths_three_fifths':
				return '\n[two_fifths]'+shortcode.getVal('columns', 'two_fifths_three_fifths', '1')+'[/two_fifths]' +
					   '\n[three_fifths_last]'+shortcode.getVal('columns', 'two_fifths_three_fifths', '2')+'[/three_fifths_last]';

			case 'three_fifths_two_fifths':
				return '\n[three_fifths]'+shortcode.getVal('columns', 'three_fifths_two_fifths', '1')+'[/three_fifths]' +
					   '\n[two_fifths_last]'+shortcode.getVal('columns', 'three_fifths_two_fifths', '2')+'[/two_fifths_last]';

			case 'one_sixth_five_sixths':
				return '\n[one_sixth]'+shortcode.getVal('columns', 'one_sixth_five_sixths', '1')+'[/one_sixth]' +
					   '\n[five_sixths_last]'+shortcode.getVal('columns', 'one_sixth_five_sixths', '2')+'[/five_sixths_last]';

			case 'five_sixths_one_sixth':
				return '\n[five_sixths]'+shortcode.getVal('columns', 'five_sixths_one_sixth', '1')+'[/five_sixths]' +
					   '\n[one_sixth_last]'+shortcode.getVal('columns', 'five_sixths_one_sixth', '2')+'[/one_sixth_last]';

			case 'one_sixth_one_sixth_one_sixth_one_half':
				return '\n[one_sixth]'+shortcode.getVal('columns', 'one_sixth_one_sixth_one_sixth_one_half', '1')+'[/one_sixth]' +
					   '\n[one_sixth]'+shortcode.getVal('columns', 'one_sixth_one_sixth_one_sixth_one_half', '2')+'[/one_sixth]' +
					   '\n[one_sixth]'+shortcode.getVal('columns', 'one_sixth_one_sixth_one_sixth_one_half', '3')+'[/one_sixth]' +
					   '\n[one_half_last]'+shortcode.getVal('columns', 'one_sixth_one_sixth_one_sixth_one_half', '4')+'[/one_half_last]';
			}
		break;
		
		case 'typography':
			var sub_type = shortcode.getVal('typography','selector');
			switch(sub_type){
			case 'push':
				var height = shortcode.getVal('typography', 'push', 'h');
				height = ' h="'+height+'"';
				
				return '[push'+height+']';
			case 'dropcap1':
			case 'dropcap2':
			case 'dropcap3':
			case 'dropcap4':
				return shortcode.build(sub_type, {
					'atts': ['color'],
					'content': 'text'
				}, 'typography');
				break;
			case 'blockquote':
				return shortcode.build('blockquote', {
					'atts': ['align', 'cite'],
					'content': 'content'
				}, 'typography');
				break;
			case 'pre_code':
				var s = shortcode.getVal('typography','pre_code','type');
				s = this.def(s=='', 'code', s);
				
				return '['+s+']'+shortcode.getVal('typography','pre_code','content')+'[/'+s+']';
			case 'styledlist':
				var style = shortcode.getVal('typography','styledlist','style');
				var color = shortcode.getVal('typography','styledlist','color');
				
				style = ' style="'+style+'"';
				color = ' color="'+color+'"';
				
				return '[list'+style+color+']'+shortcode.getVal('typography','styledlist','content')+'[/list]';
			case 'icon':
				var style = shortcode.getVal('typography','icon','style');
				var color = shortcode.getVal('typography','icon','color');
				var size = shortcode.getVal('typography','icon','size');
				
				style = ' style="'+style+'"';
				color = ' color="'+color+'"';
				size = ' size="'+size+'"';
				
				return '[icon'+style+color+size+'] '+shortcode.getVal('typography','icon','text')+'[/icon]';
			case 'highlight':
				var t = shortcode.getVal('typography','highlight','type');
				t = ' type="'+t+'"';
				
				return '[highlight'+t+']'+shortcode.getVal('typography','highlight','content')+'[/highlight]';
			}
		break;
		
		case 'styled_boxes':
			var sub_type = shortcode.getVal('styled_boxes','selector');
			switch(sub_type){
			case 'messageboxes':
				var t = shortcode.getVal('styled_boxes','messageboxes','type');
				if(t == '')
					t='info';
				
				return '['+t+']'+shortcode.getVal('styled_boxes','messageboxes','content')+'[/'+t+']';
			break;
			case 'framed_box':
				return shortcode.build(sub_type, {
					'atts': ['width', 'height', 'bgColor', 'textColor', 'rounded'],
					'content': 'content'
				}, 'styled_boxes');
			break;
			case 'note_box':
				return shortcode.build(sub_type, {
					'atts': ['width', 'align', 'title'],
					'content': 'content'
				}, 'styled_boxes');
			break;
			}
		break;
		
		case 'table':
			return '[styled_table]'+shortcode.getVal('table','content')+'[/styled_table]';
		break;
		
		case 'tabs':
			var number = shortcode.getVal('tabs', 'number');
			var style = shortcode.getVal('tabs', 'style');
			var delay = shortcode.getVal('tabs', 'delay');
			var vertical = shortcode.getVal('tabs', 'vertical');
			
			style = ' style="'+style+'"';
			delay = ' delay="'+delay+'"';
			vertical = ' vertical="'+!!vertical+'"';
			
			var ret = '[tabs'+style+delay+vertical+']';
			for(var i=1; i<=number; i++)
				ret +='\n[tab title="'+shortcode.getVal('tabs','title_'+i)+'"]'+shortcode.getVal('tabs','content_'+i)+'[/tab]\n';
			
			ret +='[/tabs]';
			return ret;
		break;
		
		case 'accordion':
			var number = shortcode.getVal('accordion','number');
			var mini = shortcode.getVal('accordion','mini');
			var ret = '[accordions mini="'+mini+'"]';
			
			for(var i=1; i<=number; i++)
				ret += '\n[accordion title="'+
							shortcode.getVal('accordion','title_'+i)+'"]'+
							shortcode.getVal('accordion','content_'+i)+
						'[/accordion]\n';
			
			ret += '[/accordions]';
			return ret;
		break;
		
		case 'divider':
			var type = shortcode.getVal('divider','type');
			
			switch(type) {
				case 'underline-5':
					return '[underline t="-5"]';
				case 'underline-10':
					return '[underline t="-10"]';
				case 'underline_dark-5':
					return '[underline_dark t="-5"]';
				case 'underline_dark-10':
					return '[underline_dark t="-10"]';
				default:
					return '['+type+']';
			}
		break;
		
		case 'iframe':
			var src = shortcode.getVal('iframe','src');
			var width = shortcode.getVal('iframe','width');
			var height = shortcode.getVal('iframe','height');
			
			src = ' src="'+src+'"';
			width = ' width="'+width+'"';
			height = ' height="'+height+'"';
			
			return '[iframe'+src+width+height+']';
		break;
		
		case 'gmap':
			var width = shortcode.getVal('gmap','width');
			var height = shortcode.getVal('gmap','height');
			var address = shortcode.getVal('gmap','address');
			var latitude = shortcode.getVal('gmap','latitude');
			var longitude = shortcode.getVal('gmap','longitude');
			var zoom = shortcode.getVal('gmap','zoom');
			var marker = shortcode.getVal('gmap','marker');
			var html = shortcode.getVal('gmap','html');
			var popup = shortcode.getVal('gmap','popup');
			var controls = shortcode.getVal('gmap','controls');
			var scrollwheel = shortcode.getVal('gmap','scrollwheel');
			var maptype = shortcode.getVal('gmap','maptype');

			width = ' width="'+width+'"';
			height = ' height="'+height+'"';
			address = ' address="'+address+'"';
			latitude = ' latitude="'+latitude+'"';
			longitude = ' longitude="'+longitude+'"';
			zoom = ' zoom="'+zoom+'"';
			marker = ' marker="'+!!marker+'"';
			popup = ' popup="'+popup+'"';
			html = ' html="'+html+'"';
			controls = ' controls="'+controls+'"';
			scrollwheel = ' scrollwheel="'+!!scrollwheel+'"';
			
			maptype = ' maptype="'+maptype+'"';

			return '[gmap'+width+height+address+latitude+longitude+zoom+marker+popup+html+controls+scrollwheel+maptype+']';
		break;
		
		case 'widget':
			var sub_type = shortcode.getVal('widget','selector');
			switch(sub_type) {
			case 'contactform':
				var email = shortcode.getVal('widget','contactform','email');
				email = ' email="'+email+'"';
				
				var content = shortcode.getVal('widget','contactform','content');
				if(content != "")
					return '[contactform'+email+']'+content+'[/contactform]';
				return '[contactform'+email+']';
			break;
			case 'twitter':
				var username = shortcode.getVal('widget','twitter','username');
				var count = shortcode.getVal('widget','twitter','count');
				var avatarSize = shortcode.getVal('widget','twitter','avatarSize');
				var query = shortcode.getVal('widget','twitter','query');
				
				username = ' username="'+username+'"';
				count = ' count="'+count+'"';
				
				avatarSize = ' avatarSize="'+avatarSize+'"';
				
				query = ' query="'+query+'"';
				
				return '[twitter'+username+count+avatarSize+query+']';
			break;
			case 'flickr':
				var id = shortcode.getVal('widget','flickr','id');
				var type = shortcode.getVal('widget','flickr','type');
				var count = shortcode.getVal('widget','flickr','count');
				var display = shortcode.getVal('widget','flickr','display');
				
				id = ' id="'+id+'"';
				type = ' type="'+type+'"';
				count = ' count="'+count+'"';
				display = ' display="'+display+'"';
				
				return '[flickr'+id+type+count+display+']';
			break;
			case 'contact_info':
				var color = shortcode.getVal('widget','contact_info','color');
				var phone = shortcode.getVal('widget','contact_info','phone');
				var cellphone = shortcode.getVal('widget','contact_info','cellphone');
				var email = shortcode.getVal('widget','contact_info','email');
				var address = shortcode.getVal('widget','contact_info','address');
				var city = shortcode.getVal('widget','contact_info','city');
				var state = shortcode.getVal('widget','contact_info','state');
				var zip = shortcode.getVal('widget','contact_info','zip');
				var name = shortcode.getVal('widget','contact_info','name');

				color = ' color="'+color+'"';
				phone = ' phone="'+phone+'"';
				cellphone = ' cellphone="'+cellphone+'"';
				email = ' email="'+email+'"';
				address = ' address="'+address+'"';
				city = ' city="'+city+'"';
				state = ' state="'+state+'"';
				zip = ' zip="'+zip+'"';
				name = ' name="'+name+'"';
				
				return '[contact_info'+color+phone+cellphone+email+address+city+state+zip+name+']';
			break;
			}
		break;
		
		case 'video':
			var sub_type = shortcode.getVal('video','selector');
			switch(sub_type){
				case 'html5':
					var poster = shortcode.getVal('video','html5','poster');
					var mp4 = shortcode.getVal('video','html5','mp4');
					var webm = shortcode.getVal('video','html5','webm');
					var ogg = shortcode.getVal('video','html5','ogg');
					var width = shortcode.getVal('video','html5','width');
					var height = shortcode.getVal('video','html5','height');
					var preload = shortcode.getVal('video','html5','preload');
					var autoplay = shortcode.getVal('video','html5','autoplay');

					poster = ' poster="'+poster+'"';
					mp4 = ' mp4="'+mp4+'"';
					webm = ' webm="'+webm+'"';
					ogg = ' ogg="'+ogg+'"';
					width = ' width="'+width+'"';
					height = ' height="'+height+'"';
					autoplay = ' autoplay="'+!!autoplay+'"';
					preload = ' preload="'+!!preload+'"';
					
					return '[video type="html5"'+poster+mp4+webm+ogg+width+height+preload+autoplay+']';
				break;
				
				case 'flash':
					var src = shortcode.getVal('video','flash','src');
					var width = shortcode.getVal('video','flash','width');
					var height = shortcode.getVal('video','flash','height');
					var play = shortcode.getVal('video','flash','play');
					var flashvars = shortcode.getVal('video','flash','flashvars');

					src = ' src="'+src+'"';
					width = ' width="'+width+'"';
					height = ' height="'+height+'"';
					play = ' play="'+!!play+'"';
					flashvars = ' flashvars="'+flashvars+'"';
					
					return '[video type="flash"'+src+width+height+play+flashvars+']';
				break;
				
				case 'youtube':
					var src = shortcode.getVal('video','youtube','src');
					var width = shortcode.getVal('video','youtube','width');
					var height = shortcode.getVal('video','youtube','height');

					src = ' src="'+src+'"';
					width = ' width="'+width+'"';
					height = ' height="'+height+'"';
					
					return '[video type="youtube"'+src+width+height+']';
				break;
				
				case 'vimeo':
					var src = shortcode.getVal('video','vimeo','src');
					var width = shortcode.getVal('video','vimeo','width');
					var height = shortcode.getVal('video','vimeo','height');
					var title = shortcode.getVal('video','vimeo','title');
					
					src = ' src="'+src+'"';
					width = ' width="'+width+'"';
					height = ' height="'+height+'"';
					title = ' title="'+!!title+'"';

					return '[video type="vimeo"'+src+width+height+title+']';
				break;
				
				case 'dailymotion':
					var src = shortcode.getVal('video','dailymotion','src');
					var width = shortcode.getVal('video','dailymotion','width');
					var height = shortcode.getVal('video','dailymotion','height');

					src = ' src="'+src+'"';
					width = ' width="'+width+'"';
					height = ' height="'+height+'"';
					
					return '[video type="dailymotion"'+src+width+height+']';
					break;
			};
		break;
		
		case 'lightbox':
			var href = shortcode.getVal('lightbox','href');
			var title = shortcode.getVal('lightbox','title');
			var group = shortcode.getVal('lightbox','group');
			var width = shortcode.getVal('lightbox','width');
			var height = shortcode.getVal('lightbox','height');
			var c = shortcode.getVal('lightbox','class');
			var iframe = shortcode.getVal('lightbox','iframe');
			var inline = shortcode.getVal('lightbox','inline');
			var inline_id = shortcode.getVal('lightbox','inline_id');
			var inline_html = shortcode.getVal('lightbox','inline_html');
			var photo = shortcode.getVal('lightbox','photo');
			var close = shortcode.getVal('lightbox','close');
			
			href = ' href="'+href+'"';
			title = ' title="'+title+'"';
			group = ' group="'+group+'"';
			width = ' width="'+width+'"';
			height = ' height="'+height+'"';
			c = ' class="'+c+'"';
			iframe = ' iframe="'+!!iframe+'"';
			
			if(inline===true) {
				inline = ' inline="true"';
				inline_html = '<div class="hidden"><div id="'+inline_id+'">'+inline_html+'</div></div>';
				href = ' href="#'+inline_id+'"';
			} else {
				inline = '';
				inline_html = '';
			}
			
			photo = ' photo="'+!!photo+'"';
			close = ' close="'+!!close+'"';
			
			return '[lightbox'+title+group+href+width+height+c+iframe+inline+photo+close+']'+shortcode.getVal('lightbox','content')+'[/lightbox]'+inline_html;
		break;
		
		case 'portfolio':
			var column = shortcode.getVal('portfolio','column');
			var width = shortcode.getVal('portfolio','width');
			var nopaging = shortcode.getVal('portfolio','nopaging');
			var max = shortcode.getVal('portfolio','max');
			var sortable = shortcode.getVal('portfolio','sortable');
			var cat = shortcode.getVal('portfolio','cat');
			var ids = shortcode.getVal('portfolio','ids');
			var title = shortcode.getVal('portfolio','title');
			var desc = shortcode.getVal('portfolio','desc');
			var more = shortcode.getVal('portfolio','more');
			var moreText = shortcode.getVal('portfolio','moreText');
			var group = shortcode.getVal('portfolio','group');
			var height = shortcode.getVal('portfolio','height');
			var is_long = shortcode.getVal('portfolio','long');

			if(nopaging===true) {
				nopaging = ' nopaging="true"';
				max = '';
			} else {
				nopaging = ' nopaging="false"';
			}
			
			if(sortable===true) {
				sortable = ' sortable="true"';
				nopaging = ' nopaging="true"';
				max = '';
			} else {
				sortable = ' sortable="false"';
			}
			
			cat = (cat !== null && cat !== undefined) ? cat : '';
			ids = (ids !== null && ids !== undefined) ? ids : '';
			
			column = ' column="'+column+'"';
			width = ' width="'+width+'"';
			cat = ' cat="'+cat+'"';
			max = ' max="'+max+'"';
			ids = ' ids="'+ids+'"';
			title = ' title="'+!!title+'"';
			desc = ' desc="'+!!desc+'"';
			more = ' more="'+!!more+'"';
			moreText = ' moreText="'+moreText+'"';
			group = ' group="'+!!group+'"';
			is_long = ' is_long="'+!!is_long+'"';
			height = ' height="'+height+'"';

			return '[portfolio'+column+nopaging+sortable+max+cat+ids+title+desc+more+moreText+group+height+is_long+width+']';
		break;
		
		case 'sitemap':
			var sub_type = shortcode.getVal('sitemap','selector');
			switch(sub_type) {
				case 'all':
					var shows = shortcode.getVal('sitemap','all','shows');
					var number = shortcode.getVal('sitemap','all','number');

					shows = ' shows="'+shows+'"';
					number = ' number="'+number+'"';
					
					return '[sitemap type="all"'+shows+number+']';
				break;
				
				case 'pages':
					var depth = shortcode.getVal('sitemap','pages','depth');
					var number = shortcode.getVal('sitemap','pages','number');

					depth = ' depth="'+depth+'"';
					number = ' number="'+number+'"';

					return '[sitemap type="pages"'+depth+number+']';
				break;
				
				case 'categories':
					var show_count = shortcode.getVal('sitemap','categories','show_count');
					var show_feed = shortcode.getVal('sitemap','categories','show_feed');
					var depth = shortcode.getVal('sitemap','categories','depth');
					var number = shortcode.getVal('sitemap','categories','number');
					
					show_count = ' show_count="'+!!show_count+'"';
					show_feed = ' show_feed="'+!!show_feed+'"';
					depth = ' depth="'+depth+'"';
					number = ' number="'+number+'"';
					
					return '[sitemap type="categories"'+show_count+show_feed+depth+number+']';
				break;
				
				case 'posts':
					var show_comment = shortcode.getVal('sitemap','posts','show_comment');
					var number = shortcode.getVal('sitemap','posts','number');
					var posts = shortcode.getVal('sitemap','posts','posts');
					var cat = shortcode.getVal('sitemap','posts','cat');
					
					show_comment = ' show_comment="'+!!show_comment+'"';
					cat = ' cat="'+cat+'"';
					number = ' number="'+number+'"';
					posts = ' posts="'+posts+'"';
					
					return '[sitemap type="posts"'+show_comment+number+posts+cat+']';
				break;
				
				case 'portfolios':
					var show_comment = shortcode.getVal('sitemap','portfolios','show_comment');
					var number = shortcode.getVal('sitemap','portfolios','number');
					var cat = shortcode.getVal('sitemap','portfolios','cat');
					
					show_comment = ' show_comment="'+!!show_comment+'"';
					cat = ' cat="'+cat+'"';
					number = ' number="'+number+'"';
					
					return '[sitemap type="portfolios"'+show_comment+number+cat+']';
				break;
			}
			break;
			
			case 'sequence':
				return shortcode.build('slider', {
					atts: ['width', 'height', 'caption_opacity', 'effect', 'animspeed', 'pausetime', 'pauseonhover', 'style', 'annotation', 'highlight'],
					content: {
						code: 'slide',
						number: 'number',
						atts: ['image'],
						content: 'html'
					}
				});
			break;
			
			case 'showcase':
				var columns = shortcode.getVal('showcase', 'columns');
				var slides = shortcode.getVal('showcase', 'slides');
				var ret = '[showcase ';

				fields = [
					'width',
					'height',
					'effect',
					'animspeed',
					'pausetime',
					'pauseonhover',
					'columns',
					'annotation'
				];
						
				for(i in fields) {
					ret += fields[i] + '="';
					ret += shortcode.getVal('showcase', fields[i]);
					ret += '" ';
				}
						
				ret += ']';
				
				var images = slides * (shortcode.getVal('showcase', 'annotation').length>0 ? (columns-1) : columns);
					
				for(var i=1; i<=images; i++) {
					ret += '\n[image]'+shortcode.getVal('showcase', i+'_image')+'[/image]\n';
				}
				
				ret += '[/showcase]';
						
				return ret;
			break;
		}
		return '';
	},
	
	def: function(condition, on_true, on_false) {
		if(condition)
			return on_true;
		return on_false || '';
	},
	
	getVal: function(a,b,c) {
		var name = $.grep([a,b,c], function(n){return n!==undefined}).join('_');

		var target = $('[name="sc_'+name+'"]');
		if(target.is('.toggle-button'))
			return target.is(':checked');
			
		if(target.size() == 0) {
			var in_target = $('[name*="sc_'+name+'"]');
			if(in_target.size() === 1) {
				return in_target.val();
			} else if(in_target.size() > 1) {
				var data = [];
				in_target.each(function() {
					var sub = {
						name: $(this).attr('name').replace('sc_'+name+'-', ''),
					 	val: $(this).val().replace(/'/, "&#39;")
					};

					data.push("'"+sub.name+"':'"+sub.val+"'");
				});

				return data.join(',');
			}
		}
		return target.val();
	},
	
	sendToEditor: function() {
		if($('body').attr('data-wpvshortcode') == 'table' && shortcode.table_element.length > 0) {
			shortcode.table_element.replaceWith(shortcode.generate());
		} else {
			send_to_editor("\n" + shortcode.generate() + "\n");
		}
	},

	build: function(id, fields, parent) {
		function getAttr(attr, prefix) {
			if(prefix === undefined)
				prefix = '';

			var val = shortcode.getVal(parent, id, prefix+attr);
			if(val !== undefined) {
				if(val === null)
					val = '';
				
				return attr+'="'+val+'"';
			}

			return '';
		}

		var open = [id];
		for(i in fields.atts) {
			open.push(getAttr(fields.atts[i]));
		}
		open = open.join(' ');

		var content = '';
		var close = '';
		if(fields.content !== undefined) {
			if(typeof fields.content === 'string') {
				content = shortcode.getVal(parent, id, fields.content);
			} else if(typeof fields.content === 'object') {

				var number = shortcode.getVal(parent, id, fields.content.number);

				for(var sub=1; sub<=number; sub++) {

					var sub_open = [fields.content.code];
					for(i in fields.content.atts) {
						sub_open.push(getAttr(fields.content.atts[i], sub+'_'));
					}
					sub_open = sub_open.join(' ');

					var sub_close = '';

					if(fields.content.content !== undefined) {
						var sub_content = shortcode.getVal(parent, id, sub+'_'+fields.content.content);
						var sub_close = sub_content+'[/'+fields.content.code+']\n';
					}

					content += '\n['+sub_open+']'+sub_close;
				}
			}

			close = (fields.content!==undefined ? (content+'[/'+id+']') : '');
		}
		
		return '['+open+']' + close;
	}
		
}

$(function() {
	shortcode.init();
});

$(window).bind('wpv_shortcodes_loaded', function() {
	shortcode.init();
});

})(jQuery);
