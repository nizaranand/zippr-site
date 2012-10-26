<?php
/*
 * generates a sitemap
 */

function wpv_shortcode_sitemap($atts, $content = null, $code) {
	$sitemap = new shortcode_sitemap;
	return '<div class="sitemap">'.$sitemap->get($atts).'</div>';
}

add_shortcode('sitemap', 'wpv_shortcode_sitemap');

class shortcode_sitemap {
	public function get($atts) {
		if(method_exists($this, $atts['type']))
			return $this->$atts['type']($atts);
		return '';
	}
	
	private function pages($atts){
		extract(shortcode_atts(array(
			'number' => '0',
			'depth' => '0',
		), $atts));
		
		return '<ul>'.wp_list_pages(array(
			'depth' => 0,
			'sort_column' => 'menu_order',
			'echo' => 0,
			'title_li' => '',
			'depth' => $depth,
			'number' => $number,
		)).'</ul>';
	}
	
	private function categories($atts){
		extract(shortcode_atts(array(
			'number' => '0',
			'depth' => '0',
			'show_count' => true,
			'show_feed' => true,
		), $atts));
		
		if($show_count === 'false')
			$show_count = false;
		
		$feed = ($show_feed === true || $show_feed == 'true') ? __( 'RSS', 'wpv' ) : '';	
		
		return '<ul>'.wp_list_categories(array(
			'feed' => $feed,
			'show_count' => $show_count,
			'use_desc_for_title' => false,
			'title_li' => false,
			'echo' => 0
		)).'</ul>';
	}
	
	private function posts($atts){
		extract(shortcode_atts(array(
			'show_comment' => true,
			'number' => '0',
			'cat' => '',
			'posts' => '',
		), $atts));
		
		if($number == 0)
			$number = 1000;
		
		if($show_comment === 'false')
			$show_comment = false;
		
		$query = array(
			'showposts' => (int)$number,
			'post_type'=>'post',
		);
		
		if($cat)
			$query['cat'] = $cat;
			
		if($posts)
			$query['post__in'] = explode(',',$posts);
		
		$archive_query = new WP_Query( $query );
		$output = '';
		while ($archive_query->have_posts()) {
			$archive_query->the_post();
			$output .= 
				'<li>
					<a href="'.get_permalink().'" rel="bookmark" title="'.sprintf( __("Permanent Link to %s", 'wpv'), get_the_title() ).'">'.
						 get_the_title().
					'</a>'.
					($show_comment?' ('.get_comments_number().')':'').
				'</li>';
		}
		wp_reset_query();
		
		return '<ul>'.$output.'</ul>';
	}

	private function portfolios($atts){
		extract(shortcode_atts(array(
			'show_comment' => false,
			'number' => '0',
			'cat' => '',
		), $atts));
		
		if($number == 0)
			$number = 1000;
		
		$show_comment = ($show_comment === 'true'  && wpv_get_option('portfolio_enable_comment'));
		
		$query = array(
			'showposts' => (int)$number,
			'post_type'=>'portfolio',
		);
		
		if($cat) {
			$query['taxonomy'] = 'portfolio_category';
			$query['term'] = $cat;
		}
			
		query_posts( $query );
		$output = '';
		while (have_posts()) {
			the_post();
			$output .= 
				'<li>
					<a href="'.get_permalink().'" rel="bookmark" title="'.sprintf( __("Permanent Link to %s", 'wpv'), get_the_title() ).'">'.
						get_the_title().
					'</a>'.
					($show_comment?' ('.get_comments_number().')':'').
				'</li>';
		}
		wp_reset_query();

		return '<ul>'.$output.'</ul>';
	}
	
	private function all($atts){
		extract(shortcode_atts(array(
			'number' => '0',
			'shows' => 'pages,categories,posts,portfolios',
		), $atts));
		
		$shows = explode(',', $shows);
		if(empty($shows))
			return '';
		
		$types = array(
			'pages' => __('Pages','wpv'),
			'categories' => __('Categories','wpv'),
			'posts' => __('Posts','wpv'),
			'portfolios' => __('Portfolios','wpv'),
		);
		
		$output = '';
		foreach($types as $type=>$name)
			if(in_array($type, $shows)) {
				$output .= "<h2>$name</h2>";
				$output .= "<hr class='light top-5' />";
				$output .= $this->$type($atts);
				$output .= '<div class="divider top"><a href="#">'.__('Top','wpv').'</a></div> ';
			}

		return $output;
	}
}
