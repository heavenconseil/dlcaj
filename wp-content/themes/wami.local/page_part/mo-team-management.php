<?php 
$user = wp_get_current_user();
$ambassadeur_id = $user->ID;
global $wp_roles;

if(user_id_exists($ambassadeur_id)) : 
    
    $region_amb                         = get_field('ambassadeur_region', "user_".$ambassadeur_id);
    $region_slug                        = (isset($region_amb) && is_array($region_amb)) ? $region_amb['value']: "";
    $region                             = get_term_by('slug', $region_slug, 'lieu');

    if(in_array('administrator', $user->roles)) :
        $ventes_effectuees_sur_la_region    = ventes_effectuees_sur_la_region($ambassadeur_id);    
        $prevision_des_ventes_sur_la_region = prevision_des_ventes_sur_la_region($ambassadeur_id);    
        $mandats_en_cours_sur_la_region     = mandats_en_cours_sur_la_region($ambassadeur_id);    
        $collaborateurs_sur_la_region       = collaborateurs_sur_la_region($ambassadeur_id);        

    ; else :
        $ventes_effectuees_sur_la_region    = ventes_effectuees_sur_la_region($ambassadeur_id, $region_slug, $region);    
        $prevision_des_ventes_sur_la_region = prevision_des_ventes_sur_la_region($ambassadeur_id, $region_slug, $region);    
        $mandats_en_cours_sur_la_region     = mandats_en_cours_sur_la_region($ambassadeur_id, $region_slug, $region);    
        $collaborateurs_sur_la_region       = collaborateurs_sur_la_region($ambassadeur_id, $region_slug, $region);
    endif;
    ?>

    <div class="management_team middle_office"> 

        <section id="ambassador-stat">    
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
                            <p><?php echo (isset($region_amb) && is_array($region_amb)) ? $region_amb['label']: ""; ?></p>
                            <?php echo ($user->roles && is_array($user->roles)) ? '<p>'.$wp_roles->roles[$user->roles[0]]['name'].'</p>' : ""; ?>

                        </div>
                    </div>
                </div>
                <div class="grid-col col_size-8 tablet_size-6 mobile_size-12">
                    <div class="ambassador-profil-stat">
                        <div class="container">
                            <h4><?php if(in_array('administrator', $user->roles)) echo get_bloginfo('name'); else echo $region->name; ?></h4> 
                            <p><?php echo $collaborateurs_sur_la_region->total.' '.$collaborateurs_sur_la_region->titre; ?></p>
                            <p><?php echo $mandats_en_cours_sur_la_region->total.' '.$mandats_en_cours_sur_la_region->titre; ?></p> 
                            <p><?php echo $prevision_des_ventes_sur_la_region->total.' '.$prevision_des_ventes_sur_la_region->titre; ?></p>
                            <p><?php echo number_format($ventes_effectuees_sur_la_region->ca, 0, '', ' ').' '.$ventes_effectuees_sur_la_region->titre; ?></p>
                        </div>
                    </div>
                </div>
            </div>   
        </section>  

     
        <section id="statistiques"> 
            <div class="w_grid limited-content">  
                <div class="stat-bloc">
                    <div class="w_grid limited-content">
                        <div class="grid-col col_size-8 tablet_size-12">
                            <div class="stat-bloc-with-filtre"><?php echo $ventes_effectuees_sur_la_region->bloc; ?></div>
                        </div>
                        <div class="grid-col col_size-4 tablet_size-12">
                            <div class="stat-bloc-with-filtre"><?php echo $prevision_des_ventes_sur_la_region->bloc; ?></div>
                        </div>
                    </div>
                </div>
                <div class="stat-bloc">
                    <div class="w_grid limited-content">
                        <?php echo $mandats_en_cours_sur_la_region->bloc; ?>
                    </div>
                </div>

            </div>
        </section>

    </div>
    <div class="statistique_collaborateur middle_office">
        <section id="statistiques"> 
            <div class="w_grid limited-content">
                <div class="stat-bloc">
                    <div class="w_grid limited-content">
                        <?php echo $collaborateurs_sur_la_region->bloc; ?>
                    </div>
                </div>            
            </div>
        </section>        
    </div>


<?php else : 
  //get_template_part('page_part/mo', 'team-management');

endif; ?>