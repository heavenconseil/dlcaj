<?php 
$user = get_currentuserinfo();
$fields = array( 
    //'', //Prénom
    //'', //Nom
    //'', //Mot de passe
    //'', //Confirmer le mot de passe
    'field_59ae778bb5a39', //type_ambassadeur
    'field_59aeb08893312', // spécialité (juste pour les négociateurs)
    'field_59ae77d5b5a3a', //type_ambassadeur_agent_rsac_ville
    'field_59ae784bff842', //type_ambassadeur_agent_rsac_num
    'field_59085d327deef', //photo
    'field_59085d617def1', //Email professionnel
    'field_598443fc9c0e2', //Email personnel
    'field_59085d197deed', //Téléphone professionnel
    'field_598444209c0e3', //Téléphone personnel
    'field_59085e077defa', //Linkedin
    'field_59085d6c7def2', //Facebook
    'field_59085de07def7', //Google +
    'field_59085df97def9', //Instagram
    'field_59085dd47def6', //Twitter 
    'field_59085d257deee', // region
    //'field_59844494a5bb1', //Région d'action
    'field_59085d007deec', //Présentez-vous
    'field_5984439ae6b8c', //Votre définition de la maison idéale
);
$html_before = '
<div class="acf-field">
  <div class="acf-label"><label for="first_name">Prénom</label></div>
  <div class="acf-input"><input name="first_name" id="first_name" value="'.$user->user_firstname.'" class="regular-text" type="text"></div>
</div> 
<div class="acf-field">
  <div class="acf-label"><label for="last_name">Nom</label></div>
  <div class="acf-input"><input name="last_name" id="last_name" value="'.$user->user_lastname.'" class="regular-text" type="text"></div>
</div> 
<div class="acf-field full">
  <a href="" class="button btn-primary annuler open_popin" data-openpopin="change_password">Modifier mon mot de passe</a>
</div>
';
?>

    <div class="grid-col col_size-3 tablet_size-6 mobile_size-12 asside"> 
        <div class="conteneur">
          <p class="display_name"><?php echo $user->display_name; ?></p>
          <p class="role"><?php echo $user->roles[0]; ?></p>
        </div>
    </div>


    <div class="grid-col col_size-9 tablet_size-6 mobile_size-12 annonce-add">
      <p class="info_compte">Un compte est requis afin de proposer une annonce et accéder à son suivi. Modifier si besoin les champs ci-dessous afin que votre profil soit complet.</p>
      <?php $args = array(
          'post_id'             => 'user_'.$user->ID,
          //'field_groups'        => $fields,
          'fields'              => $fields,
          'form'                => true, 
          'return'              => add_query_arg( 'updated', 'true', get_permalink() ), 
          'html_before_fields'  => $html_before,
          'submit_value'        => 'Enregistrer' 
      ); ?>
      <?php acf_form($args); ?>
    </div>


    <div id="change_password" class="popin-layer <?php echo $etat; ?> close">
        <div class="popin changepassword">
            <h4>Modifiez votre mot de passe</h4>
            <form name="ressetpassform" id="ressetpassform" action="" method="post" autocomplete="off">
              <input type="hidden" name="username" id="username" class="input" value="<?php echo $user->data->user_login; ?>" autocomplete="off" />
              <p class="change-password">
                      <label for="pass_old"><?php _e( 'Mot de passe', 'wami' ) ?></label>
                      <input type="password" name="pass_old" id="pass_old" class="input" size="20" value="" autocomplete="off" data-msg-erreur="Merci de renseigner votre ancien mot de passe." />
                  </p>             
                  <p class="change-password">
                      <label for="pass_new1"><?php _e( 'Nouveau mot de passe', 'wami' ) ?></label>
                      <input type="password" name="pass_new1" id="pass_new1" class="input" size="20" value="" autocomplete="off" data-msg-erreur="Merci de renseigner votre nouveau mot de passe." />
                  </p>
                  <p class="change-password">
                      <label for="pass_new2"><?php _e( 'Confirmez le nouveau mot de passe', 'wami' ) ?></label>
                      <input type="password" name="pass_new2" id="pass_new2" class="input" size="20" value="" autocomplete="off" data-msg-erreur="Merci de confirmer votre nouveau mot de passe." />
                  </p>
                   
                  <p class="resetpass-submit">
                      <input type="submit" name="submit" id="resetpass-button" class="button" value="<?php _e( 'Modifier mon mot de passe', 'wami' ); ?>" />
                  </p>
                  <a href="" class="button btn-primary annuler close_popin" data-closepopin="change_password">Annuler</a>
            </form>
        </div>
    </div>



   
