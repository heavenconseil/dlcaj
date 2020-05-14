<?php

if( !defined('ABSPATH') ) die();

class Wami_Socialwall_Model{

	public $plugin;
	public $table;
	public $charset;
	public $howMuchSocialPosts;

	public function __construct($plugin=null){
		global $wpdb;
		$this->plugin = $plugin;
		$this->table = $wpdb->base_prefix.'wami_socialwall';
		$this->charset = $wpdb->get_charset_collate();
		$this->howMuchSocialPosts = 15;
	}

	public function activate(){
		global $wpdb;

		$sql = "CREATE TABLE $this->table (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`created` DATETIME NOT NULL,
			`network` VARCHAR(20) NOT NULL,
			`identifier` VARCHAR(255) NOT NULL,
			`published` INT(11) NOT NULL,
			`info` VARCHAR(255) NULL,
			`postid` INT NULL,
			UNIQUE KEY id (id)
		) $this->charset;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
	}

	public function deactivate(){

	}

	public function get_settings(){
		$accounts = get_option('wami_socialwall_settings');
		if($accounts){
			return $accounts;
		}
		return false;
	}

	public function get_setting($network, $setting){
		$settings = $this->get_settings();
		if(isset($settings[$network])){
			if(isset($settings[$network][$setting]) && !empty($settings[$network][$setting])){
				return $settings[$network][$setting];
			}
		}
		if(isset($this->plugin->settings[$network]['fields'][$setting]['default'])){
			return $this->plugin->settings[$network]['fields'][$setting]['default'];
		}
		return false;
	}

	public function get_account_link($network){
		$settings = $this->get_settings();
		$return = '';
		if( isset($settings[$network]['account']) ){
			if( isset($this->plugin->settings[$network]['fields']['account']['prefix']) ){
				$return .= $this->plugin->settings[$network]['fields']['account']['prefix'];
			}
			$return .= $settings[$network]['account'];
		}
		return $return;
	}

	public function social_exists($network, $identifier){
		global $wpdb;
		$social_exists = $wpdb->get_var("
			SELECT id 
			FROM $this->table 
			WHERE network='".esc_sql($network)."' 
			AND identifier='".esc_sql($identifier)."' 

		");
		return $social_exists;
	}

	public function create_links($string){
		$string = preg_replace("/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i", "$1http://$2",$string);
		$string = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","<a target=\"_blank\" href=\"$1\">$1</a>",$string);
		$string = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<a href=\"mailto:$1\">$1</a>",$string);
		return $string;
	}

	/*public function get_facebook_posts(){
		require_once( $this->plugin->path.'lib/facebook/facebook.php' );
		$fb_appid 		= $this->get_setting('facebook', 'appid');
		$fb_appsecret 	= $this->get_setting('facebook', 'appsecret');
		$fb_token 		= $this->get_setting('facebook', 'apptoken'); debug($fb_token);
		if( $fb_appid && $fb_appsecret ){
			$facebook = new Facebook(array(
				'appId'       => $fb_appid,
				'secret'      => $fb_appsecret,
			  	'accessToken' => $fb_token,
				'fileUpload'  => false,
			));
			debug($facebook);
		}else{
			return false;
		}

		$fb_datas = $facebook->api( "/".$this->get_setting('facebook', 'account') );
		debug($fb_datas);
		//$nb_followers = $fb_datas['likes'];
		$tax = get_term_by( 'slug', 'facebook', 'network');
		//update_field('field_57c54dbfb5633', $nb_followers, 'network_'.$tax->term_id);

		$pageFeed = $facebook->api("/".$this->get_setting('facebook', 'account')."/posts?limit=".$this->howMuchSocialPosts);		
		debug($pageFeed ); exit;
		//https://www.facebook.com/20531316728/posts/10154009990506729/

		if(isset($pageFeed['data']) &&  !empty($pageFeed['data'])){
			foreach($pageFeed['data'] as $post){
				//unset($post['comments']);
				//unset($post['likes']);
				$postObj 	= (object) $post;
				$network    = 'facebook';
				$identifier = $postObj->id;

				// si le post n'a pas déjà été récupéré
				if(!$this->social_exists($network, $identifier)){

					debug($postObj); 

					$published  = strtotime($postObj->updated_time);

					$title      = (isset($postObj->message) ? $postObj->message : '');
					$content    = (isset($postObj->description) ? $postObj->description : $title);
					$image      = '';
					$link       = $postObj->link;
					$info       = $postObj->type;

					// Si c'est un lien externe à celui de facebook on va chercher celui de fb !
					if( $postObj->status_type == "shared_story" && isset($postObj->actions[0]['link']) ){
						$link   = $postObj->actions[0]['link'];
					}

					$detailPhoto    = $facebook->api("/".$postObj->id);
					$detailPhotoObj = (object) $detailPhoto;
					$image          = $detailPhotoObj->images[0]['source'];	

					debug($detailPhoto);

					// Si c'est une photo et pas un chagment de de photo de profil
					if($postObj->type=='photo'){						

						$rep = $this->wami_sw_insert_post($network, $identifier, $published, $title, $content, $image, $link, $info);

					}
					// Ou si c'est une video	
					elseif($postObj->type=='video'){

						$image          = $postObj->picture;
						$rep = $this->wami_sw_insert_post($network, $identifier, $published, $title, $content, $image, $link, $info); 
					}
					
				}

				exit;	
			}
		}
	}*/

	public function get_facebook_posts(){

		require_once( $this->plugin->path.'lib/php-graph-sdk-5.0.0/src/Facebook/autoload.php' );
		
		$fb_appid 		= $this->get_setting('facebook', 'appid');
		$fb_appsecret 	= $this->get_setting('facebook', 'appsecret');
		$fb_token 		= $this->get_setting('facebook', 'apptoken');
		
		if( $fb_appid && $fb_appsecret && $fb_token ){
			$fb = new Facebook\Facebook([
			  'app_id' => $fb_appid,
			  'app_secret' => $fb_appsecret,
			  'default_access_token' => $fb_token,
			  'default_graph_version' => 'v2.10',
			]);
			debug($fb); 
			try {
			  // Returns a `Facebook\FacebookResponse` object
			  $response = $fb->get('/delacouraujardinofficiel/feed', $fb_token);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
			  echo 'Graph lala returned an error: ' . $e->getMessage();
			  exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
			  echo 'Facebook SDK returned an error: ' . $e->getMessage();
			  exit;
			}
			$graphNode = $response->getGraphNode();
			debug($graphNode, 'graphNode'); exit;
		} else return false; 

		$token = $fb->get('/oauth/access_token?client_id='.$fb_appid.'&client_secret='.$fb_appsecret.'&grant_type=client_credentials');
		
		try {
		  // Get the \Facebook\GraphNodes\GraphUser object for the current user.
		  // If you provided a 'default_access_token', the '{access-token}' is optional.
		  $response = $fb->get($this->get_setting('facebook', 'account').'/posts?fields=id,object_id,message,created_time,link,description,type&limit='.$this->howMuchSocialPosts, $fb_token);
		} catch(\Facebook\Exceptions\FacebookResponseException $e) {
			debug($token, 'lala');
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
			debug($token, 'plip');
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}

		$network  = 'facebook';
		$tax = get_term_by( 'slug', 'facebook', 'network');
		 

		//'EAAFZBnPsHsTIBABeFLCjTTWhHZAXcxEly7oZC80tMlvkRC3c7K34skRzV7gGQhqNFEl6oGhGmjrNx5QZAfSp2ZBX4gcmQGyInvOeQzq15sqa2xyjkfZCEv2ReT19m1XGeBjmHO6qmXiIp74lA165jBUyZCixx7HxK6KEecCpUQmrENC8gnxZBmHjN87rkCToQmsZD'
		$pageFeed = $fb->get('/'.$this->get_setting('facebook', 'account').'/posts?fields=id,object_id,message,created_time,link,description,type&limit='.$this->howMuchSocialPosts, $fb_token);		
		debug($pageFeed); exit;

		$pageFeed = $pageFeed->getDecodedBody();

		if(isset($pageFeed['data']) &&  !empty($pageFeed['data'])){
			foreach($pageFeed['data'] as $post){	
				
				$identifier = $post['id'];
				$info       = $post['type'];

				// si le post n'a pas déjà été récupéré
				if( !$this->social_exists($network, $identifier) && $post['type']=='photo' ){

					$published  = strtotime($post['created_time']);
					$title      = (isset($post['message']) ? $post['message'] : '');
					$content    = (isset($post['description']) ? $post['description'] : $title);
					$link       = $post['link'];	
					$image 		= '';

					$postObj 	= $fb->get('/'.$post["object_id"].'?fields=images', $fb_token);
					$postObj 	= $postObj->getDecodedBody(); 
					if( isset($postObj['images']) &&  !empty($postObj['images']) ){
						$image  = $postObj['images'][0]['source'];
					}	

					$rep = $this->wami_sw_insert_post($network, $identifier, $published, $title, $content, $image, $link, $info); 
					
				}	
			}
		}
	}

	/*public function get_facebook_posts(){

		require_once( $this->plugin->path.'lib/php-graph-sdk-5.0.0/src/Facebook/autoload.php' );
		
		$fb_appid = $this->get_setting('facebook', 'appid');
		$fb_appsecret = $this->get_setting('facebook', 'appsecret');
		$fb_token = $this->get_setting('facebook', 'apptoken');
		
		if( $fb_appid && $fb_appsecret && $fb_token ){
			$fb = new Facebook\Facebook([
			  'app_id' 					=> $fb_appid,
			  'app_secret' 				=> $fb_appsecret,
			  'default_graph_version' 	=> 'v2.9',
			]); 
			$fb->setDefaultAccessToken($fb_token);
		} else return false; 

		$requestUserName = $fb->request('GET', '/me?fields=id,name');

		debug($requestUserName); exit;

		$network  = 'facebook';
		$tax = get_term_by( 'slug', 'facebook', 'network');

		$pageFeed = $fb->get('/'.$this->get_setting('facebook', 'account').'/posts?fields=id,object_id,message,created_time,link,description,type,photo&limit=10', $fb_token);
		$pageFeed = $pageFeed->getDecodedBody();

		if(isset($pageFeed['data']) &&  !empty($pageFeed['data'])){
			foreach($pageFeed['data'] as $post){	
				
				$identifier = $post['id'];
				$info       = $post['type'];

				// si le post n'a pas déjà été récupéré
				if( !$this->social_exists($network, $identifier) && $post['type']=='photo' ){

					$published  = strtotime($post['created_time']);
					$title      = (isset($post['message']) ? $post['message'] : '');
					$content    = (isset($post['description']) ? $post['description'] : $title);
					$link       = $post['link'];	
					$image 		= '';

					$postObj 	= $fb->get('/'.$post["object_id"].'?fields=images', $fb_token);
					$postObj 	= $postObj->getDecodedBody(); 
					if( isset($postObj['images']) &&  !empty($postObj['images']) ){
						$image  = $postObj['images'][0]['source'];
					}	

					$this->insert_post($network, $identifier, $published, $title, $content, $image, $link, $info); 					
				}
			}
		}
	}*/
	

	private function upload_by_url($post_id, $image_url){
		$upload_dir = wp_upload_dir();
	    $opts = array(
	      'http'=>array(
	        'method'=>"GET",
	        'header'=>"Accept-language: fr\r\n" .
	                  "User-Agent:    Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:51.0) Gecko/20100101 Firefox/51.0".
	                  "Cookie: foo=bar\r\n"
	      )
	    );
	    $context = stream_context_create($opts);
			$image_data = file_get_contents($image_url, false, $context);
			$image_url_sans_params = strtok($image_url, '?');
			$ext = pathinfo($image_url_sans_params, PATHINFO_EXTENSION);
			if( $ext == 'php' ){
				$image_url_sans_params = $image_url;
				$ext = pathinfo($image_url_sans_params, PATHINFO_EXTENSION);
				$ext = strtok($ext, '&');
			}

			$filename = $post_id.'_'.time().'.'.$ext;
			if(wp_mkdir_p($upload_dir['path'])){
				$file = $upload_dir['path'].'/'.$filename;
			}else{
				$file = $upload_dir['basedir'].'/'.$filename;
			}
			file_put_contents($file, $image_data);

			$wp_filetype = wp_check_filetype($filename, null);
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => sanitize_file_name($filename),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'post_parent'    => $post_id,
			);
			$attach_id = wp_insert_attachment($attachment, $file, $post_id);
			require_once(ABSPATH.'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata($attach_id, $file);
			wp_update_attachment_metadata( $attach_id, $attach_data );

			return $attach_id;
	}

	public function generateRandomString(){
		global $wpdb;
		$length = 9;
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		$exists = $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE post_name='{$randomString}' LIMIT 1");
		if($exists){
			return $this->generateRandomString();
		}
		return $randomString;
	}

	public function wami_sw_insert_post($network, $identifier, $published, $title, $content=null, $image=null, $link=null, $info=null, $update=false ){
		global $wpdb;

		$debug = false;

		// on cherche si l'entrée existe dans la custom table
		$social_exists = $this->social_exists($network, $identifier);
		if($debug) $social_exists = false;
		
		if(!$social_exists){
			
			// on cherche si le post n'existe pas déjà (créé par un autre blog) 
			$post_exists = $wpdb->get_var("
				SELECT p.ID
				FROM $wpdb->posts AS p
				INNER JOIN $wpdb->postmeta AS pm
					ON pm.post_id = p.ID
				WHERE p.post_type='socialpost'
				AND meta_key = 'wami_social_identifier'
				AND meta_value = '".esc_sql($identifier)."'
			");
			if($debug) $post_exists = false;

			// si non, on créé le post correspondant avec titre/texte/image
			if( !$post_exists ){
					
				if(!$debug){
					$post_exists = wp_insert_post(array(
						'post_content' => $content,
						'post_title'   => $title,
						'post_name'    => $this->generateRandomString(),
						//'post_status'  => 'pending',
						'post_status'  => 'publish',
						'post_type'    => 'socialpost',
						'post_date'    => date('Y-m-d H:i:s', $published),
					));
				}else{
					$post_exists = 320;
				}
				
				if($post_exists){
					update_post_meta($post_exists, 'wami_social_identifier', esc_sql($identifier));

					$tax_network = get_term_by('slug', $network, 'network');
					update_field('field_5901eb7c4fe75', $tax_network->term_id, $post_exists); // reseau
					wp_set_post_terms($post_exists, array($tax_network->term_id), 'network');

					update_field('field_5901ebeb4fe78', $identifier, $post_exists); //identifier
					update_field('field_5901ec034fe79', $link, $post_exists); // lien

					if($network=='youtube'){
						update_field('field_5901ebae4fe76', 'medium', $post_exists); // format
					}
					
					if($image){
            			update_field('field_5901ec114fe7a', $image, $post_exists); // lien image
						$image_id = $this->upload_by_url($post_exists, $image);
						update_field('field_5901ebd24fe77', $image_id, $post_exists); //image
						set_post_thumbnail($post_exists, $image_id);
					}
				}
			}
			
			// et enfin la ligne dans la custom table avec l'id créé
			if(!$social_exists && $post_exists && !$debug){
				$entry = array(
					'created'    => date('Y-m-d H:i:s'),
					'network'    => $network,
					'identifier' => $identifier,
					'published'  => $published,
					'info'       => $info,
					'postid'     => $post_exists
				);
				//debug($entry);
				$wpdb->insert($this->table, $entry);
			} 
			
		}
	}

}

