<?php 
/*add_action( 'comment_form_defaults', 'change_comment_form_defaults');
function change_comment_form_defaults( $default ) {
    $commenter = wp_get_current_commenter();
    $default[ 'fields' ][ 'author' ] .= '<div class="forminput"><input id="firstname" class="inputtext" name="firstname" type="text" value="" size="30" placeholder="Nom*" aria-required="true"></div>';
    return $default;
}*/


//Add our field to the comment form
add_action( 'comment_form_logged_in_after', 'wami_comment_fields');
add_action( 'comment_form_after_fields', 'wami_comment_fields');
function wami_comment_fields(){?>  
    <!-- <div class="forminput">
        <input id="firstname" class="inputtext" name="firstname" type="text" value="" placeholder="Prénom*" aria-required="true">
    </div>  --> 
    <div class="forminput">
        <input id="annonce" name="annonce" type="hidden" value="<?= get_the_permalink(); ?>" />
    </div>
    <div class="forminput">
        <input id="telephone" class="inputtext" name="telephone" type="text" value="" size="30" placeholder="Votre numéro*" aria-required="true" />
    </div>
    <div class="forminput">
        <label><span class="wpcf7-form-control-wrap rappeler">
            <input type="checkbox" name="rappeler" value="rappeler" class="wpcf7-form-control wpcf7-acceptance">
        </span>Je souhaite être rappelé</label>
    </div>
    <div class="forminput">
        <span class="wpcf7-form-control-wrap horaire">
            <span class="wpcf7-form-control wpcf7-radio">
                <span class="wpcf7-list-item first">
                    <input name="horaire" value="11h - 12h30" type="radio">
                    <span class="wpcf7-list-item-label">11h - 12h30</span>
                </span>
                <span class="wpcf7-list-item">
                    <input name="horaire" value="13h - 15h30" type="radio">
                        <span class="wpcf7-list-item-label">13h - 15h30</span>
                </span>
                <span class="wpcf7-list-item last">
                    <input name="horaire" value="16h - 18h30" type="radio">
                    <span class="wpcf7-list-item-label">16h - 18h30</span>
                </span>
            </span>
        </span>
    </div>    
    <!-- <div class="forminput captcha">
        <p id="griwpc-container-id" class="google-recaptcha-container recaptcha-align-left" style="display: block;"><span id="griwpc-widget-id" class="g-recaptcha" data-forced="1"><div style="width: 304px; height: 78px;"><div><iframe src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LfRBEQUAAAAALQJYMODXIRmnaB9ZeXapHTBIqDd&amp;co=aHR0cHM6Ly93d3cuZGVsYWNvdXJhdWphcmRpbi5jb206NDQz&amp;hl=fr&amp;type=image&amp;v=0bBqi43w2fj-Lg1N3qzsqHNu&amp;theme=light&amp;size=normal&amp;cb=sx07y4n2v0ne" role="presentation" name="a-82k0hapzq73i" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox allow-storage-access-by-user-activation" width="304" height="78" frameborder="0"></iframe></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div></span><span class="plugin-credits" style="font-size: 0.62rem; display: none;"><a target="_blank" href="http://www.joanmiquelviade.com/plugin/google-recaptcha-in-wp-comments-form/" title="reCAPTCHA plugin homepage" rel="nofollow">Get reCAPTCHA plugin</a></span></p>
    </div> -->
    <?php
}


//Add the title to our admin area, for editing, etc
add_action( 'add_meta_boxes_comment', 'wami_comment_add_meta_boxes' );
function wami_comment_add_meta_boxes(){
    add_meta_box( 'wami-comment-rappel', __( 'Rappel' ), 'wami_comment_meta_box_rappel', 'comment', 'normal', 'high' );
}
function wami_comment_meta_box_rappel( $comment ){
    $firstname      = get_comment_meta( $comment->comment_ID, 'firstname', true );
    $annonce        = get_comment_meta( $comment->comment_ID, 'annonce', true );
    $rappel         = get_comment_meta( $comment->comment_ID, 'rappeler', true );
    $checked_rappel = $rappel=="rappeler" ? 'checked=checked' : '';
    $horaire        = get_comment_meta( $comment->comment_ID, 'horaire', true );
    $telephone      = get_comment_meta( $comment->comment_ID, 'telephone', true );
    wp_nonce_field( 'wami_comment_update', 'wami_comment_update', false );
    ?> 
    <p>
        <label for="firstname"><?php _e( 'Prénom' ); ?></label>
        <input type="text" name="firstname" value="<?php echo esc_attr( $firstname ); ?>" class="widefat" />
    </p>  
    <p>
        <label for="telephone"><?php _e( 'Téléphone' ); ?></label>
        <input type="text" name="telephone" value="<?php echo esc_attr( $telephone ); ?>" class="widefat" />
    </p>
    <p>
        <label for="rappel"><?php _e( 'Rappeler' ); ?></label>
        <input type="checkbox" name="rappeler" value="rappeler" class="wpcf7-form-control wpcf7-acceptance" <?php echo $checked_rappel; ?>>
    </p>
    <?php if($rappel=="rappeler") : ?>
        <p>
            <label for="horaire"><?php _e( 'Plage horaire' ); ?></label>
            <input type="text" name="horaire" value="<?php echo esc_attr( $horaire ); ?>" class="widefat" />
        </p>
    <?php endif;  ?>
    <p>
        <label for="annonce"><?php _e( 'Annonce URL' ); ?></label>
        <input type="text" name="annonce" value="<?php echo esc_attr( $annonce ); ?>" class="widefat" />
    </p> <?php 
}


//Save our comment (from the admin area)
add_action( 'edit_comment', 'wami_comment_edit_comment' );
function wami_comment_edit_comment( $comment_id ){
    if( !isset( $_POST['wami_comment_update'] ) || !wp_verify_nonce($_POST['wami_comment_update'], 'wami_comment_update') ) return;
    if( isset( $_POST['annonce'] ) )
        update_comment_meta( $comment_id, 'annonce', esc_attr( $_POST['annonce'] ) );
    if( isset( $_POST['firstname'] ) )
        update_comment_meta( $comment_id, 'firstname', esc_attr( $_POST['firstname'] ) );
    if( isset( $_POST['telephone'] ) )
        update_comment_meta( $comment_id, 'telephone', esc_attr( $_POST['telephone'] ) );
    if( isset( $_POST['rappeler'] ) )
        update_comment_meta( $comment_id, 'rappeler', esc_attr( $_POST['rappeler'] ) );
    else 
        update_comment_meta( $comment_id, 'rappeler', 0 );
    if( isset( $_POST['horaire'] ) )
        update_comment_meta( $comment_id, 'horaire', esc_attr( $_POST['horaire'] ) );
}


// Save our title (from the front end)
add_action( 'comment_post', 'wami_comment_insert_comment', 10, 1 );
function wami_comment_insert_comment( $comment_id ){
    if( isset( $_POST['annonce'] ) )
        update_comment_meta( $comment_id, 'annonce', esc_attr( $_POST['annonce'] ) );
    if( isset( $_POST['firstname'] ) )
        update_comment_meta( $comment_id, 'firstname', esc_attr( $_POST['firstname'] ) );
    if( isset( $_POST['telephone'] ) )
        update_comment_meta( $comment_id, 'telephone', esc_attr( $_POST['telephone'] ) );
    if( isset( $_POST['rappeler'] ) )
        update_comment_meta( $comment_id, 'rappeler', esc_attr( $_POST['rappeler'] ) );
    if( isset( $_POST['horaire'] ) )
        update_comment_meta( $comment_id, 'horaire', esc_attr( $_POST['horaire'] ) );    
}


// add our headline to the comment text / Hook in way late to avoid colliding with default / WordPress comment text filters
/*add_filter( 'comment_text', 'wami_comment_add_title_to_text', 99, 2 );
function wami_comment_add_title_to_text( $text, $comment ){
    if( is_admin() ) return $text;
    if( $title = get_comment_meta( $comment->comment_ID, 'wami_comment_title', true ) )
    {
        $title = '<h3>' . esc_attr( $title ) . '</h3>';
        $text = $title . $text;
    }
    return $text;
}*/

/*
// update 2012-09-12 to show how to put the title in the comments list table
add_action('load-edit-comments.php', 'wpse64973_load');
function wpse64973_load(){
    $screen = get_current_screen();
    add_filter("manage_{$screen->id}_columns", 'wpse64973_add_columns');
}

function wpse64973_add_columns($cols){
    $cols['title'] = __('Comment Title', 'wpse64973');
    return $cols;
}


add_action('manage_comments_custom_column', 'wpse64973_column_cb', 10, 2);
function wpse64973_column_cb($col, $comment_id){
    // you could expand the switch to take care of other custom columns
    switch($col)  {
        case 'title':
            if($t = get_comment_meta($comment_id, 'wami_comment_title', true)){
                echo esc_html($t);
            }
            else{
                esc_html_e('No Title', 'wpse64973');
            }
            break;
    }
}*/


// Update statut au clic (ajax)
add_action('wp_ajax_nopriv_wami_update_rappel_statut', 'wami_update_rappel_statut');
add_action('wp_ajax_wami_update_rappel_statut', 'wami_update_rappel_statut');
function wami_update_rappel_statut(){
    if( isset($_REQUEST['rappel_id']) ) {
        $data = array(
            'comment_ID'        => $_REQUEST['rappel_id'],
            'comment_approved'  => 1
        );   
        wp_update_comment($data);
    }    
    die();
} 