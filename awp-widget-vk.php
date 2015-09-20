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
	function __construct ()
	{
		$args = array(
			'name' 		  => 'VK Widget',
			'description' => 'AWP VK Widget',
		);

		return parent::__construct('awp_vk','AWP VK Widget',$args);
	}

	
}