<?php
/*
Plugin Name: WAMI Social Wall
Description: 
Version: 1.0
Author: WAMI Concept
Author URI: http://www.wami-concept.com
*/

if( !defined('ABSPATH') ) die();
//if( class_exists('Wami_Stores') ) die();

class Wami_Socialwall{

	var $title,
		$slug,
		$url,
		$path,
		$capability,
		$message,
		$settings;

	public function __construct(){
		$this->title      = 'Social Wall';
		$this->slug       = 'wami-socialwall';
		$this->url        = plugins_url('/', __FILE__);
		$this->path       = plugin_dir_path(__FILE__);
		$this->capability = 'publish_posts';
		$this->message    = 'message';

		$this->settings = array(
			'facebook' => array(
				'label'  => 'Facebook',
				'icon'   => 'dashicons-facebook',
				'fields' => array(
					'account' => array(
						'label'   => 'Compte',
						'default' => '', 
						'prefix'  => 'https://www.facebook.com/',
					),
					'appid' => array(
						'label'   => 'App ID',
					),
					'appsecret' => array(
						'label'   => 'App Secret',
					),
					'apptoken' => array(
						'label'   => 'App Token',
					),
				),
			),
		);

		require_once('wami-socialwall-model.php');
		require_once('wami-socialwall-view.php');
		require_once('wami-socialwall-controller.php');
		
		$this->model      = new Wami_Socialwall_Model($this);
		$this->view       = new Wami_Socialwall_View($this);
		$this->controller = new Wami_Socialwall_Controller($this);
	}

	public function activate(){
		$this->model->activate();
	}

	public function deactivate(){
		$this->model->deactivate();
	}

}

$Wami_Socialwall = new Wami_Socialwall();

register_activation_hook(__FILE__, array($Wami_Socialwall, 'activate'));
register_deactivation_hook(__FILE__, array($Wami_Socialwall, 'deactivate'));
