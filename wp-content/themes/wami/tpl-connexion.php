<?php
/*
Template Name: TPL Connexion
*/
?>

<?php // Si l'utilisateur est connecté on l'envoi direct au TDB
if( is_user_logged_in() ) : ?>


<?php // Sinon il peut se connecter
; else : ?>                    

    <?php get_header(); ?>

        <div class="tpl-annonces-list">
            <section id="connection">

                <div class="w_grid limited-content">                
                    <div class="grid-col col_size-12">   

                        <div class="connexion connect">
                            <?php 
                            if(isset($_REQUEST['error']) && $_REQUEST['error'] == 'login') echo "<div class='popin popin_log'><div class='popin_content'><h4>Email et/ou mot de passe erroné(s), merci de réessayer.</h4><div class='close_popin'>X</div></div></div>"; 
                            ?>
                            <h4>Se connecter</h4>
                            <?php $args = array(
                                'redirect'       => site_url( '/tableau-de-bord/ ' ),
                                'label_username' => __( 'Email :' ),
                                'label_password' => __( 'Mot de passe :' ),
                                'label_remember' => __( 'Se souvenir de moi' ),
                                'label_log_in'   => __( 'Connexion' ),
                                'value_remember' => true
                            );
                            wp_login_form( $args ); ?>          
                            <p class="lost_password"><a href="#">Mot de passe oublié ?</a></p>
                        </div>

                            
                        <div class="connexion motdepasseperdu undisplay">
                            <h4>Mot de passe oublié ?</h4>  
                            <div class="message notice notice-success is-dismissible"></div>        
                            <form name="lostpasswordform" id="lostpasswordform" action="" method="post">
                                <p class="infos">Rentrez l'adresse mail de votre compte, un lien vous sera envoyé pour réinitialisez votre mot de passe.</p>
                                <p class="lost-password">
                                    <label>Email :<br>
                                    <input type="text" name="user_login" id="user_login" class="input" value="" size="20" tabindex="10" placeholder=" monadresse@mail.com"></label>
                                </p>
                                <input type="hidden" name="redirect_to" value="<?php echo $redirect; ?>">
                                <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Confirmer" tabindex="100"></p>
                                <p class="retour_connexion"><a href="#">Retour à la page de connexion</a></p>
                            </form>
                        </div>                      

                    </div>
                </div>         

            </section>
        </div>


    <?php get_footer(); ?>

<?php endif; ?>