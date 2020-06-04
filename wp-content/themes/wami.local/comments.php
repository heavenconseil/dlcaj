<?php
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">	

	<h2>Envoyez-nous une demande de rappel</h2>

	<?php 
	$content 	= $commenter && is_array($commenter) && array_key_exists("comment_content", $commenter) ? esc_textarea($commenter['comment_content']) : '';
	$author  	= $commenter && is_array($commenter) && array_key_exists("comment_author", $commenter) ? esc_attr($commenter['comment_author']) : '';
	$firstname  = $commenter && is_array($commenter) && array_key_exists("comment_firstname", $commenter) ? esc_attr($commenter['comment_firstname']) : '';
	$email 	 	= $commenter && is_array($commenter) && array_key_exists("comment_author_email", $commenter) ? esc_attr($commenter['comment_author_email']) : '';
	$comment_form_params = array(
		'fields' => array('author', 'email', 'firstname'),
		'comment_notes_after' => "<a href='#annuler' class='button btn-primary annuler close_popin' data-closepopin='demande_rappel'>+</a>",
		'comment_notes_before' => '',
		'title_reply' => '',
		'title_reply_to' => '',
		'label_submit' => 'Envoyer',
		'comment_field' => '<div class="forminputforminputlast"><span class="info_req">* Champ obligatoire</span><textarea id="commenttextarea" name="comment" aria-required="true" placeholder="Votre message">'.$content.'</textarea></div>',
		'fields' => array(
			'author' =>
				'<div class="forminput"><input id="author" class="inputtext" name="author" type="text" value="'.$author.'" size="30" placeholder="Nom*" aria-required="true" /></div>',
			'firstname' => 
				'<div class="forminput"><input id="firstname" class="inputtext" name="firstname" type="text" value="'.$firstname.'" size="30" placeholder="Prénom*" aria-required="true" /></div>',
			'email' =>
				'<div class="forminput"><input id="email" class="inputtext" name="email" type="text" value="'.$email.'" size="30" placeholder="Email*" aria-required="true" /></div>'			
		),
	);
	comment_form($comment_form_params); ?>

</div><!-- /#comments -->
