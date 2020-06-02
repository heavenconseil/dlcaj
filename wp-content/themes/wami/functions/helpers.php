<?php

/* Fonction de débug (à utiliser à la place du var_dump/print_r) */
if(!function_exists('debug')){
	function debug($var, $info=''){
		echo '<div style="padding:5px 10px; margin-bottom:8px; font-size:13px; background:#FACFD3; color:#8E0E12; line-height:16px; border:1px solid #8E0E12; text-transform:none; overflow:auto;">';
		if( !empty($info) ){
		    echo '<h3 style="color:#8E0E12; font-size:16px; padding:5px 0;">'.$info.'</h3>';
		}
		echo '<pre style="white-space:pre-wrap;">'.print_r($var,true).'</pre>
		</div>';
	}
}


function remove_session($session){
	if(!isset($_SESSION[$session])) return false;
	unset($_SESSION[$session]);
}


function post($key){
	return (isset($_POST[$key]))? $_POST[$key] : false;
}


function get($key){
	return (isset($_GET[$key]))? $_GET[$key] : false;
}


function request($key){
	return (isset($_REQUEST[$key]))? $_REQUEST[$key] : false;
}


function check_nounce($key, $value){
	return (isset($_POST[$key]) && wp_verify_nonce($_POST[$key], $value));
}


function custom_field_excerpt($title, $excerpt_length = 30) {
	global $post;
	$text = get_field($title);
	if ( '' != $text ) {
		$text = strip_shortcodes( $text );
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]>', $text);
		$excerpt_more = apply_filters('excerpt_more', ' ' . ' ...');
		$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
	}
	return apply_filters('the_excerpt', $text);
}


function random_string($car = 10) {
	$string = "";
	$chaine = "abcdefghijklmnpqrstuvwxy";
	srand((double)microtime()*1000000);
		for($i=0; $i<$car; $i++) {
		$string .= $chaine[rand()%strlen($chaine)];
	}
	return $string;
}



function slugify($text){ 
  // replace non letter or digits by -
  $text = preg_replace('~[^\\pL\d]+~u', '-', $text); 
  // trim
  $text = trim($text, '-');
  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  // lowercase
  $text = strtolower($text);
  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);
  if (empty($text))
    return 'n-a';
  return $text;
}


function get_the_slug($postID="") {
	global $post;
	$postID = ( $postID != "" ) ? $postID : $post->ID;
	$post_data = get_post($postID, ARRAY_A);
	$slug = $post_data['post_name'];
	return $slug;
}


function is_mobile(){
	$detect = new Mobile_Detect;
	if($detect->isTablet()) return false;
	else if($detect->isMobile()) return true;
	else return false;
}


function normalize_string($str) {
	$str = strtolower($str);
	$str = preg_replace('#ç#', 'c', $str);
	$str = preg_replace('#è|é|ê|ë#', 'e', $str);
	$str = preg_replace('#à|á|â|ã|ä|å#', 'a', $str);
	$str = preg_replace('#ì|í|î|ï#', 'i', $str);
	$str = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $str);
	$str = preg_replace('#ù|ú|û|ü#', 'u', $str);
	$str = preg_replace('#ý|ÿ#', 'y', $str);
	$str = str_replace(' ', '-', $str);
	return $str;
}

function normalize_ref($str) {
	$str = strtoupper($str);
	//$str = preg_replace('#ç#', 'c', $str);
	$str = preg_replace('#Ç#', 'C', $str);
	//$str = preg_replace('#è|é|ê|ë#', 'e', $str);
	$str = preg_replace('#È|É|Ê|Ë#', 'E', $str);
	//$str = preg_replace('#à|á|â|ã|ä|å#', 'a', $str);
	$str = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $str);
	//$str = preg_replace('#ì|í|î|ï#', 'i', $str);
	$str = preg_replace('#Ì|Í|Î|Ï#', 'I', $str);
	//$str = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $str);
	$str = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $str);
	//$str = preg_replace('#ù|ú|û|ü#', 'u', $str);
	$str = preg_replace('#Ù|Ú|Û|Ü#', 'U', $str);
	//$str = preg_replace('#ý|ÿ#', 'y', $str);
	$str = preg_replace('#Ý#', 'Y', $str);
	$str = str_replace(' ', '', $str);
	return $str;
}

function return_tel_french_format($num){
	$tel = "";
    for($i=0; $i<=strlen($num); $i++){
    	if ($i % 2 == 0) $tel .= substr($num, $i, 2) .'&nbsp;';
    }
    return $tel;
}