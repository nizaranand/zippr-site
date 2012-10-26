<?php
define('WPV_RESPONSIVE', true);
define('WPV_MAX_WIDTH', 1180); // the max content width the css is built for
							   // should equal the actual content width,
							   // for example, the width of the text of a page without sidebars

global $wpv_hsidebars_widths, $wpv_slider_shortcode_styles;

include 'shortcode-support.php';

add_theme_support('wpv-icon-fonts');
add_theme_support('wpv-reduced-ajax-single-response');
add_theme_support('wpv-ajax-siblings');
add_theme_support('wpv-simple-grid');
add_theme_support('wpv-page-title-style');
add_theme_support('wpv-apple-slider');
add_theme_support('wpv-enabled-widgets',
	'authors',
	'advertisement',
	'flickr',
	'icon-link',
	'subpages',
	'contactinfo',
	'gmap',
	'posts',
	'contactform',
	'post-formats'
);

$wpv_hsidebars_widths = array(
	'full' => 'Full',
	'one_half' => '1/2',
	'one_third' => '1/3',
	'one_fourth' => '1/4',
	'one_fifth' => '1/5',
	'one_sixth' => '1/6',
	'two_thirds' => '2/3',
	'three_fourths' => '3/4',
	'two_fifths' => '2/5',
	'three_fifths' => '3/5',
	'four_fifths' => '4/5',
	'five_sixths' => '5/6',
);

$wpv_slider_shortcode_styles = array(
	'gallery' => __('Gallery', 'wpv') ,
	'showcase' => __('Showcase', 'wpv') ,
);

add_filter('wpv_slider_effects', 'std_slider_effects');
function std_slider_effects($styles) {
	unset($styles['shrink']);
	unset($styles['peek']);
	unset($styles['gridWaveBL2TR']);
	unset($styles['gridRandomSlideDown']);
	$styles['gridFadeQueue'] = __('Grid', 'wpv');
	$styles['slideAndFade'] = __('Slide and fade', 'wpv');
	$styles['apple'] = __('Apple', 'wpv');

	return $styles;
}

function klt_slider_resizing($resizing, $effect=null) {
	if($resizing == 'cropTop')
		return 'crop';

	return $resizing;
}
add_filter('wpv_slider_resizing', 'klt_slider_resizing', 10, 2);

function klt_sidebar_class($class, $layout_type) {
	if($layout_type == 'left-only' || $layout_type == 'right-only') {
		$class .= ' single';
	} elseif ($layout_type == 'left-right') {
		$class .= ' double';
	}

	return $class;
}
add_filter('wpv_left_sidebar_class', 'klt_sidebar_class', 10, 2);
add_filter('wpv_right_sidebar_class', 'klt_sidebar_class', 10, 2);

function zen_posts_widget_img_size($img_size) {
	return 72;
}
add_filter('wpv_posts_widget_img_size', 'zen_posts_widget_img_size');

function klt_lmbtn_class($class) {
	return $class.' button full clearboth';
}
add_filter('wpv_lmbtn_class', 'klt_lmbtn_class');

function get_slider_design($animation) {
	$groups = array(
		'fade' => 'navigation-preview',
		'fadeMultipleCaptions' => 'navigation-preview',
		'slide' => 'navigation-preview',
		'slideMultipleCaptions' => 'navigation-preview',
		'slideAndFade' => 'navigation-preview',
		'gridFadeQueue' => 'face',
		'zoomIn' => 'caption-center',
		'apple' => 'apple',
//		'shrink' => 'side-caption',
//		'peek' => 'peek',
	);
	
	return $groups[$animation];
}

function po_post_header($meta, $news='false') {
	global $post;

	$tag = 'h2';
	if($news == 'true') {
		$tag = 'h6';
	}

	$show = !has_post_format('aside') && !has_post_format('quote');
	$has_date = !!wpv_get_option('meta_posted_on');
	
	if($show || ($has_date && $news != 'true')):
		$link = has_post_format('link') ? 
					get_post_meta($post->ID, 'post-link', true) :
					get_permalink();
		?>
			<header>
				<?php if($has_date): ?>
					<span class="entry-date">
						<span class="entry-month"><?php the_time('M')?></span>
						<span class="entry-day"><?php the_time('d')?></span>
					</span>
				<?php endif ?>
				<?php if($show): ?>
					<<?php echo $tag?>>
						<a href="<?php echo $link ?>" title="<?php the_title()?>"><?php the_title(); ?></a>
					</<?php echo $tag?>>
				<?php endif ?>
			</header>
		<?php
	endif;
}

// Produces an avatar image
function po_get_gravatar() {
	$avatar_email = get_comment_author_email();
	$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 73 ) );
	echo $avatar;
}

// Custom callback to list comments
function po_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $depth;
  ?>
  	<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
  		<div class="comment-author">
  			<?php po_get_gravatar(); ?>
			<div class="clearfix"></div>
			<?php edit_comment_link('Edit') ?>
  		</div>
          <div class="comment-content">
          	<div class="comment-meta">
		  		<?php printf(__('by %s', 'wpv'), get_comment_author_link()); ?>
				<span title="<?php comment_time(); ?>" class="comment-time"><?php comment_date(); ?></span>
		  		<?php
					if($args['type'] == 'all' || get_comment_type() == 'comment') :
						comment_reply_link(array_merge($args, array(
							'reply_text' => __('Reply','shape'), 
							'login_text' => __('Log in to reply.','shape'),
							'depth' => $depth,
							'before' => '<div class="comment-reply-link">', 
							'after' => '</div>'
						)));
					endif;
				?>
          	</div>
			<?php if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n", 'shape') ?>
      		<?php comment_text() ?>
  		</div>
  		<div class="clearfix"></div>
<?php } // end po_comments

// Menu descriptions
class description_walker extends Walker_Nav_Menu {
	public function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="'. esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= (!empty( $item->url ) && $item->url != '#') ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$prepend = '<strong>';
		$append = '</strong>';
		$description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

		if($depth != 0) {
			$description = $append = $prepend = "";
		}

		$item_output = '';

		if(is_object($item) && isset($item->title)) {
			$item_output = (is_object($args) ? $args->before : '').
							'<a'. $attributes .'>'.
		    				(is_object($args) ? $args->link_before : '') .
		    				$prepend.
		    				apply_filters( 'the_title', $item->title, $item->ID ).
		    				$append.
		    				$description.
		    				(is_object($args) ? $args->link_after : '') .
		    				'</a>'.
		    				(is_object($args) ? $args->before : '');
		}

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

function std_excerpt_more($more) {
	return ' <span class="excerpt-more">&rarr;</span>';
}
add_filter('excerpt_more', 'std_excerpt_more');

function std_excerpt_length($length) {
	global $wpv_loop_vars;

	if(isset($wpv_loop_vars) && isset($wpv_loop_vars['news']) && $wpv_loop_vars['news'] == 'true') {
		return 15;
	}

	return $length;
}
add_filter('excerpt_length', 'std_excerpt_length');

function stm_map_header_menu() {
	$menu_name = 'menu-header';
	$map = array(
		'items' => array(),
		'translation' => array(),
	);

	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
		$menu_items = wp_get_nav_menu_items($menu->term_id);
		$orders = array();

		foreach($menu_items as $item) {
			if($item->menu_item_parent == 0)
				$orders[] = array($item->menu_order, $item->ID);
			
			$map['items'][$item->ID] = array($item->menu_item_parent, null, $item->object_id);
			$map['translation'][$item->object_id] = $item->ID;
		}

		sort($orders);

		foreach($orders as $order=>$data) {
			$map['items'][$data[1]][1] = $order+1;
		}
	}

	wpv_update_option('stm-menu-map', $map);
}

add_action('wp_update_nav_menu', 'stm_map_header_menu');
add_action('wpv_update_generated_css', 'stm_map_header_menu');
add_action('theme_mods_nav_menu_locations', 'stm_map_header_menu');

function stm_get_header_menu_map() {
	return wpv_get_option('stm-menu-map');
}

function stm_get_page_accent() {
	if(wpv_get_option('disable_accent_override'))
		return wpv_get_option('accent-color');

	global $post;

	if(!isset($post->wpv_accent)) {
		$map = stm_get_header_menu_map();
		if(!isset($map['translation'][$post->ID]))
			return $post->wpv_accent = wpv_get_option('accent-color');

		$id = $map['translation'][$post->ID];

		while(isset($map['items'][$id]) && $map['items'][$id][0]) {
			$id = $map['items'][$id][0];
		}

		$order = $map['items'][$id][1];

		$post->wpv_accent = ($order > 6) ? wpv_get_option('accent-color') :
		                                   wpv_get_option("accent-override-$order");

		if(empty($post->wpv_accent))
			$post->wpv_accent = wpv_get_option('accent-color');
	}

	return $post->wpv_accent;
}

function stm_override_accent() {
	if(wpv_get_option('disable_accent_override'))
		return;

	global $wpv_original_accent;
	$wpv_original_accent = wpv_get_option('accent-color');
	$page_accent = stm_get_page_accent();

	add_filter('pre_option_wpv_accent-color', create_function('$a', 'return "'.$page_accent.'";'));
}
add_action('wp_head', 'stm_override_accent');