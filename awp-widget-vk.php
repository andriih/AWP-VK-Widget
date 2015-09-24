<?php

/*
Plugin Name: AWP VK Widget
Plugin URI: http://wordpress.org
Description: AWP VK Widget
Version: 1.0
Author URI: Andrii Hnatyshyn
*/

add_action( 'widgets_init' , 'awp_vk' );
add_action( 'wp_enqueue_scripts' , 'awp_styles_scripts' );

function awp_styles_scripts ()
{
	wp_enqueue_style('awp-style' , plugins_url( 'awp-widget-vk.css' ,__FILE__));
}

function awp_vk ()
{
	register_widget( 'AWP_Vk' );
}

class AWP_Vk extends WP_Widget
{
	public $title , $count ;

	function __construct ()
	{
		$args = array(
			'name' 		  => 'VK Widget',
			'description' => 'AWP VK Widget',
		);

		return parent::__construct('awp_vk','AWP VK Widget',$args);
	}

	function form ($instance)
	{
		$title = '';
		$count = '';
		extract($instance);
		$title = !empty($title) ? esc_attr($title) : '';
		$count = !isset($count) ? $count : 3 ;
		//var_dump($instance);
		?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">VK page (ID or short adress):</label>
				<input type = "text" class="widefat"
					   name = "<?php echo $this->get_field_name('title'); ?>" 
					   id   = "<?php echo $this->get_field_id('title'); ?>" 
					   value= "<?php echo $title; ?>"></input>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('count'); ?>">VK pages count:</label>
				<input type = "text" class="widefat"
					   name = "<?php echo $this->get_field_name('count'); ?>" 
					   id   = "<?php echo $this->get_field_id('count'); ?>" 
					   value= "<?php echo $count; ?>"></input>
			</p>

		<?php
	}

	function widget ($args , $instance)
	{
		extract($args);
		extract($instance);
		$title = 'andrii.hnatyshyn';
		$count = 5;

		$this->title = $title;
		$this->count = $count;

		$data = $this->awp_get_posts_vk();

		if ($data === false)
		{
			return $data = "ERROR!!!";
		}
		elseif(empty($data))
		{
			return $data = 'No posts on the page';
		}

		echo $before_widget;
		echo $before_title.'Posts from the wall {$title}'.$after_title;
		echo $data;
		echo $after_widget;	
	}

	function update ($old_instance,$new_instance)
	{
		$new_instance['title'] = !empty($new_instance['title']) ? strip_tags($new_instance['title']) : '';
		$new_instance['count'] = ( (int)$new_instance['count']) ? $new_instance['count'] : 5;

		return  $new_instance;
	}

	private function awp_get_posts_vk()
	{
		if(is_numeric($this->title) ){
			$id = "owner_id={$this->title}";
			$this->title = "id{$this->title}";
		}else{
			$id = "domain={$this->title}";
		}

		if( !(int)$this->count ) $this->count = 3;
		$url = "http://api.vk.com/method/wall.get?{$id}&filter=owner&count={$this->count}";
		$vk_posts = wp_remote_get($url);
		$vk_posts = json_decode($vk_posts['body']);

		if(isset($vk_posts->error)) return false;

		$html  = '<div class="awp-vk">';
			foreach ( $vk_posts->response as $item )
			{
				if( !empty( $item->text ))
				{
					$text = $this->awp_substr($item->text);
					$html .= "<div><a href='https://vk.com/{$this->title}'>{$text}</div>";
				}
				elseif( !empty( $item->attachment->photo->src_small ))
				{
					$html .= "<div><a href='https://vk.com/{$this->title}'><img src='{$item->attachment->photo->src_small}'' alt='' /></div>";
				}
			}
		$html .= '</div>';

		return $html;
	}

	private function awp_substr ( $str )
	{
		$str_arr  = explode(' ', $str);
		$str_arr2 = array_slice( $str_arr,0,3 );
		$str      = implode(' ', $str_arr2);

		if(count($str_arr) > 3 )
		{
			$str .= "..."; 
		}
		return $str;
	}
}