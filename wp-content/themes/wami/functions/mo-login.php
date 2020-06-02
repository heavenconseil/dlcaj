<?php

/* lIENS :
 - https://pippinsplugins.com/change-password-form-short-code/ 
 - http://www.sutanaryan.com/how-to-create-custom-reset-or-forget-password-in-wordpress/
 - https://digwp.com/2010/12/login-register-password-code/
 - http://www.hongkiat.com/blog/wordpress-custom-loginpage/
 */

// On ajoute dans la login head de wordpress la redirection vers le front 
    add_action( 'login_head', 'wp_custom_login_header' );
    function wp_custom_login_header() {
        if( (isset($_REQUEST['log']) && $_REQUEST['log'] == '') || (isset($_REQUEST['pwd']) && $_REQUEST['pwd'] == '') ){
            wp_redirect( get_bloginfo('url').'/connexion/?error=login' );
        } 
        elseif( !isset($_REQUEST['action']) && !isset($_REQUEST['error']) ){
            wp_redirect( get_bloginfo('url').'/connexion/' );
        }        
        wp_enqueue_style( 'wp-custom-login' );
        do_action('wp_custom_login_header_before');
        get_header('wplog');
        do_action('wp_custom_login_header_after');
    }
    add_action( 'login_footer', 'wp_custom_login_footer' );
    function wp_custom_login_footer() {
        do_action('wp_custom_login_footer_before');
        get_footer('wplog');
        do_action('wp_custom_login_footer_after');
    }


// Reste sur la page en cas d'erreur de connexion + ajoute un get dans URL (hook failed login)
    add_action( 'wp_login_failed', 'my_front_end_login_fail' ); 
    function my_front_end_login_fail( $username ) {
       $referrer = $_SERVER['HTTP_REFERER']; 
       if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
        wp_redirect( get_bloginfo('url').'/connexion/?error=login' );
        exit;
       }
    }

// On gere les envoie de mail : format & co
    add_filter('wp_mail_content_type', 'wami_mail_content_type');
    function wami_mail_content_type(){
        return "text/html";
    }

    add_filter('wp_mail_from', 'wami_mail_from');
    function wami_mail_from(){
        if( get_field('mail_expediteur', 'option') )
            return get_field('mail_expediteur', 'option');
        else 
            return "noreply@delacouraujardin.com";
    }

    add_filter('wp_mail_from_name', 'wami_mail_from_name');
    function wami_mail_from_name($old=''){
        if( get_field('mail_expediteur', 'option') )
            return get_field('nom_expediteur', 'option');
        else 
            return "delacouraujardin";
    }

    add_filter('wp_mail', 'wami_wp_mail', 10, 1);
    function wami_wp_mail($data){
        $modified_data = $data;
        $_subject = $data['subject'];
       // $modified_data['subject'] = "Provadys"." - ".$_subject; 
       if(get_field("nom_expediteur", 'option')) $modified_data['subject'] = get_field("nom_expediteur", 'option')." - ".$_subject; 
       else $modified_data['subject'] = $_subject; 
        $_message  = wami_mail_header($_subject);
        $_message .= $data['message'];
        $_message .= wami_mail_footer();
        $modified_data['message'] = $_message;
        return $modified_data;
    }
    function wami_mail_header($subject=''){
        $html = '<html>
                    <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                    </head>
                    <body bgcolor="#eeeeee" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
        return $html;
    }
    function wami_mail_footer(){
        $html = '</body>
        </html>';
        return $html;
    }


// Renvoi le mot de passe
    add_action('wp_ajax_nopriv_wami_reset_password', 'wami_reset_password');
    add_action('wp_ajax_wami_reset_password', 'wami_reset_password');
    function wami_reset_password(){

        if( isset($_REQUEST['data']) ) {
            $mail = array();
            parse_str($_REQUEST['data'], $mail);     
 
            $email = sanitize_text_field( $mail['user_login'] );
   
            if( is_null($email) ) die('Merci de saisir votre email.');
            if( !filter_var($email, FILTER_VALIDATE_EMAIL) ) die('Merci de vérifier votre email.');
            if( email_exists($email)==false ) die('Aucun compte ne correspond a cette adresse mail, merci de saisir votre email.');

            wami_retrieve_password($mail['user_login']);  

            die("ok");

            /*if (wami_retrieve_password($mail['user_login'])) {
                die("ok");
            } else {
                die("Une erreur est survenue merci de réésayer");
            }*/           
        }
    }

    function wami_retrieve_password($user_login) {
        global $wpdb, $wp_hasher;
     
        $errors = new WP_Error();
     
        if ( empty( $user_login ) ) {
            $errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or email address.'));
        } elseif ( strpos( $user_login, '@' ) ) {
            $user_data = get_user_by( 'email', trim( $user_login ) );
            if ( empty( $user_data ) )
                $errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
        } else {
            $login = trim($_POST['user_login']);
            $user_data = get_user_by('login', $login);
        }

        do_action( 'lostpassword_post', $errors );
     
        if ( $errors->get_error_code() )
            return $errors;
     
        if ( !$user_data ) {
            $errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or email.'));
            return $errors;
        }
     
        // Redefining user_login ensures we return the right case in the email.
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $key = get_password_reset_key( $user_data );         
        $logo_client = get_field('logo_client', 'option');
        $logo_client = is_array($logo_client) ? $logo_client['url'] : "";
        $logo_client_h = is_array($logo_client) ? $logo_client['height'] : "";
        $logo_client_w = is_array($logo_client) ? $logo_client['width'] : "";
        $max_height = 74;
       if($logo_client_h == "" || $logo_client_h > $max_height){
            $logo_client_h = $max_height;
            $logo_client_w = 'auto';
        }
        if ( is_wp_error( $key ) ) return $key;
        
        $message ='
                <table id="Tableau_01" width="600" height="352" border="0" cellpadding="0" bgcolor="#fff" cellspacing="0" align="center" >
                    <tr><td>
                        <center><table width="560" height="320" border="0" cellpadding="0" bgcolor="#fff">
                            <tr><td>
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr height="'.$max_height.'">
                                        <td width="'.$logo_client_w.'"><img src="'.$logo_client.'" width="'.$logo_client_w.'" height="'.$logo_client_h.'" alt=""></td> 
                                        <td width="150"><img src="'.get_template_directory_uri().'/img/boots.png'.'" width="133" height="'.$max_height.'" alt=""></td>                                
                                    </tr>
                                </table>
                            </td></tr>
                            <tr height="20"><td>Cher <span style="color:#979d5d;">' . $user_data->display_name . '</span>,</td></tr>
                            <tr><td>Cliquez sur ce lien ci-dessous pour r&eacute;initialiser votre mot de passe.</td></tr>
                            <tr width="600" height="120"><td>
                                <table width="600" height="120" border="0" cellpadding="0">
                                    <tr>
                                        <td width="150" height="60"></td>
                                        <td width="300" height="60" bgcolor="#979d5d"><center><a style="color:#fff;" href="'. network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') .'">R&eacute;initialiser maintenant</a></center></td>
                                        <td width="150" height="60"></td>
                                    </tr>
                                </table>
                            </td></tr>
                            <tr height="120"><td>
                                    Si vous n\'&ecirc;tes pas l\'auteur de cette demande, il est probable qu\'un autre utilisateur ait saisi votre adresse e-mail par erreur et qu\'en cons&eacute;quence la s&eacute;curit&eacute; de votre compte soit toujours intacte. Si vous pensez qu\'une personne a eu acc&egrave;s &agrave; votre compte sans votre autorisation, vous devriez changer votre mot de passe dans les plus brefs d&eacute;lais.
                            </td></tr>        
                        </table></center>
                    </td></tr>       
                </table>';
     
        if ( is_multisite() ) $blogname = $GLOBALS['current_site']->site_name;
        else $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
     
        //$title = sprintf( __('[%s] Password Reset'), $blogname ); 
        $title = "Réinitialisation de votre mot de passe";   
        $title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );  

        $message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );        
     
        if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) )
            wp_die( __('The email could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.') );
     
        return true;
    }



// Change de mot de passe
    add_action('wp_ajax_nopriv_wami_change_password', 'wami_change_password');
    add_action('wp_ajax_wami_change_password', 'wami_change_password');
    function wami_change_password(){

        if( isset($_REQUEST['data']) ) {
            $passwords = array();
            parse_str($_REQUEST['data'], $passwords);
            $user = get_user_by('login', $passwords['username']);
            
            if( wp_check_password(strval($passwords['pass_old']), $user->data->user_pass, $user->ID) ) {
                if( $passwords['pass_new1'] == '' ){
                    die('Merci de bien vouloir renseigner votre nouveau mot de passe.');
                } else if( $passwords['pass_new2'] == '' ){
                    die('Merci de bien vouloir confirmer votre nouveau mot de passe.');
                } else if( $passwords['pass_new1'] != $passwords['pass_new2'] ){
                    die('Vos nouveaux mots de passe ne sont pas identique, merci de réésayer.');
                } else {
                    //$hash = wp_hash_password( $passwords['pass_new1'] );
                    $pass = $passwords['pass_new1'];
                    wp_set_password($pass, $user->ID );
                    die('ok');
                }
            } else {
                die('Votre mot de passe est erroné, merci de bien vouloir saisir votre ancien mot de passe.');
            }
           
        }
    }