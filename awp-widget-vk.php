<?php

/*
Plugin Name: AWP VK Widget
Plugin URI: http://wordpress.org
Description: AWP VK Widget
Version: 1.0
Author URI: Andrii Hnatyshyn
*/

add_action( 'widgets_init' , 'awp_vk' );

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

	function widget ($args , $instance)
	{
		extract($args);
		extract($instance);
		$title = '1234354';
		$count = 3;

		$this->title = $title;
		$this->count = $count;

		$data = $this->awp_get_posts_vk();
		var_dump($data);
	}

	private function awp_get_posts_vk()
	{
		if(is_numeric($this->title) ){
			$id = "owner_id={$this->title}";
		}else{
			$id = "domain={$this->title}";
		}

		if(!(int)$this->count ) $this->count = 3;
		return $url = "http://api.vk.com/method/wall.get?{$id}&filter=owner$count={$this->count}";
	}
}