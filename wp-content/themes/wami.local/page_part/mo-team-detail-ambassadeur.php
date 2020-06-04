<?php $ambassadeur_id = get_query_var('page');

if(user_id_exists($ambassadeur_id)) : 
    global $wp_roles;
    $user = get_userdata( $ambassadeur_id ); ?>

    <div class="single-ambassador middle_office"> 

        <section id="ambassador-profil">        
            <div class="bg-overlay"></div>         
            
            <div class="w_grid limited-content"> 

                <div class="grid-col col_size-4 tablet_size-6 mobile_size-12">
                    <div class="ambassador-profil-content">
                        <div id="ambassador-avatar" class="profil-container">
                            <div class="ambassador-cover">
                                <?php $image = get_field('ambassadeur_photo', 'user_'.$ambassadeur_id); 
                                if($image) : 
                                    echo '<img src="'.$image['sizes']['carre_medium'].'" alt="'.$image['alt'].'" title="'.$image['title'].'">';     
                                ; else : echo '<img src="http://placehold.it/190x190">'; 
                                endif; ?>                                   
                            </div>
                            <h1><?php echo $user->data->display_name; ?></h1>
                            <?php echo ($user->roles && is_array($user->roles)) ? '<p>'.$wp_roles->roles[$user->roles[0]]['name'].'</p>' : ""; ?>
                            <p><?php $region_amb = get_field('ambassadeur_region', 'user_'.$ambassadeur_id);
                            echo (isset($region_amb) && is_array($region_amb)) ? $region_amb['label']: ""; ?>
                            <?php //echo get_field('ambassadeur_region', 'user_'.$ambassadeur_id) ? get_field('ambassadeur_region', 'user_'.$ambassadeur_id) : ""; ?>                                
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid-col col_size-4 tablet_size-6 mobile_size-12">
                    <div class="ambassador-profil-content">
                        <div id="ambassador-info-primary" class="profil-container">
                            <p><b><?php echo $user->data->display_name; ?></b></p>
                            <p><?php echo get_field('ambassadeur_mail', 'user_'.$ambassadeur_id) ? get_field('ambassadeur_mail', 'user_'.$ambassadeur_id) : ""; ?></p>
                            <p><?php echo get_field('ambassadeur_telephone', 'user_'.$ambassadeur_id) ? return_tel_french_format(get_field('ambassadeur_telephone', 'user_'.$ambassadeur_id)) : ""; ?></p>
                            <ul class="ambassador-rs">
                                <?php if(get_field('ambassadeur_lien_facebook', 'user_'.$ambassadeur_id)) 
                                    echo '<li><a href="'.get_field('ambassadeur_lien_facebook', 'user_'.$ambassadeur_id).'" class="rs-item rs-fb"><span class="fa fa-facebook"></span></a></li>'; ?>
                                <?php if(get_field('ambassadeur_lien_twitter', 'user_'.$ambassadeur_id))
                                    echo '<li><a href="'.get_field('ambassadeur_lien_twitter', 'user_'.$ambassadeur_id).'" class="rs-item rs-twitter"><span class="fa fa-twitter"></span></a></li>'; ?>  
                                <?php if(get_field('ambassadeur_lien_linkedin', 'user_'.$ambassadeur_id))
                                    echo '<li><a href="'.get_field('ambassadeur_lien_linkedin', 'user_'.$ambassadeur_id).'" class="rs-item rs-linkedin"><span class="fa fa-linkedin"></span></a></li>'; ?>  
                                <?php if(get_field('ambassadeur_lien_pinterest', 'user_'.$ambassadeur_id))
                                    echo '<li><a href="'.get_field('ambassadeur_lien_pinterest', 'user_'.$ambassadeur_id).'" class="rs-item rs-pinterest"><span class="fa fa-pinterest"></span></a></li>'; ?>
                                <?php if(get_field('ambassadeur_lien_instagram', 'user_'.$ambassadeur_id))
                                    echo '<li><a href="'.get_field('ambassadeur_lien_instagram', 'user_'.$ambassadeur_id).'" class="rs-item rs-instagram"><span class="fa fa-instagram"></span></a></li>'; ?>  
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if(get_field('ambassadeur_description', 'user_'.$ambassadeur_id)) : ?>
                <div class="grid-col col_size-4 tablet_size-12">
                    <div class="ambassador-profil-content">
                        <div id="ambassador-info-secondary" class="profil-container">
                            <?php the_field('ambassadeur_description', 'user_'.$ambassadeur_id); ?>
                        </div>
                    </div>
                </div>  
                <?php endif; ?>  

            </div>   

        </section>  

        <?php echo get_template_part('page_part/mo', 'ambassadeur-stat'); ?>

    </div>

<?php else : 
  get_template_part('page_part/mo', 'team-management');

endif; ?>