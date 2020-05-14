<?php

if( !defined('ABSPATH') ) die();

class Wami_Socialwall_Controller{

	public $plugin;
	private $model;
	private $view;

	function __construct($plugin=null){
		$this->plugin = $plugin;
		$this->model  = $this->plugin->model;
		$this->view   = $this->plugin->view;

		add_action('init', array($this,'init'));
		add_action('admin_menu', array($this,'admin_menu'));

		add_filter('cron_schedules', array($this,'cron_add_recurrence'));
		if(!wp_next_scheduled('wami_socialwall_cron')){
			wp_schedule_event(time(), 'socialtime', 'wami_socialwall_cron');
		}
		add_action('wami_socialwall_cron', array($this, 'get_accounts_posts'));
	}

	public function cron_add_recurrence($schedules){
		$schedules['socialtime'] = array(
			'interval' => 15*MINUTE_IN_SECONDS,
			'display'  => __('Four times per hour')
		);
		return $schedules;
	}

	public function init(){

		register_post_type( 'Social post', array(
			'labels' => array(
				'name' => __( 'Socials posts' ),
				'singular_name' => __( 'Social post' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'socialpost'),
			'menu_position' => 25,
			'menu_icon' => 'dashicons-facebook'
		));

		register_taxonomy('network', array('socialpost'), array(
			'labels' => array(
				'name'         	=> 'Networks',
				'singular_name' => 'Network',
			),
			'public'            => true,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => false,
			'show_admin_column' => true,
			'hierarchical'      => true,
		));	

		$action = Wami_Socialwall_View::request('wami_socialwall_action', '');
		if(!empty($action) && method_exists($this, $action)){
			$this->$action();
		}		

		// on va tout chercher à chaque chargement de page -> !cron
		//$this->get_accounts_posts();
		if(isset($_GET['force_facebook'])) $this->model->get_facebook_posts();
	}

	public function admin_menu(){
		add_menu_page( $this->plugin->title, $this->plugin->title, $this->plugin->capability, $this->plugin->slug, array($this->view, 'dashboard'), 'dashicons-share', 101);
	}

	public function save_accounts(){
		$post_socialwall = Wami_Socialwall_View::request('socialwall', array());
		if(!empty($post_socialwall)){
			//debug($post_socialwall);
			update_option('wami_socialwall_settings', $post_socialwall);
			$this->view->set_message('Comptes enregistrés');
		}
	}

	public function get_accounts_posts(){
		//debug('CRON!');
		//wp_mail( 'fxflandin@wami-concept.com', 'CRON Colas SW', 'Maintenant dans get_accounts_posts : '.date("d/m/Y H:i:s") );
		$this->model->get_facebook_posts();
	}

}