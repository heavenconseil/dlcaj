<?php $ambassadeur_id = get_query_var('page');
if( !user_id_exists($ambassadeur_id) ) {    
    $user = wp_get_current_user(); 
    $ambassadeur_id = $user->ID;
}  
?>

<section id="statistiques">  

    <div class="w_grid limited-content">                        
        <div class="grid-col col_size-12">       
            <h3>Statistiques individuelles</h3>                       
        </div>
    </div>

    <?php // MANDATS EN COURS
     $args = array(
        'post_type'     => 'biens',
        //'post_status'   => 'publish',
        'author__in'    => array( $ambassadeur_id ),
        'meta_query'    => array( 
            'relation'  => 'AND', 
            array(
                'key'       => 'lien_mandat',
                'value'     => '',
                'compare'   => '!='
            ),           
            array(
                'relation' => 'OR',
                array(
                    'key'       => 'bien_vente_vendu',
                    'value'     => 0,
                    'compare'   => '='
                ),
                array(
                  'key' => 'bien_vente_vendu',
                  'compare' => 'NOT EXISTS'
                ), 
            )        
        ),
        'posts_per_page'=> -1
    );
    $query_biens = new WP_Query( $args );
    if($query_biens->have_posts()) :
        $total = $query_biens->post_count; 
        $i = 0;?>
        <div class="stat-bloc">
            <div class="w_grid limited-content"> 
                <h4><?php echo $total; ?> Mandat<?php if($total>1) echo 's'; ?> en cours</h4>
                <div class="grid-col col_size-4 tablet_size-6 mobile_size-12">
                    <form role="search" method="get" class="single-field search_form_stat" id="search_mandat_encours">        
                        <input type="text" placeholder="<?php esc_attr_e("Rechercher", 'wami'); ?>" value="" />
                        <input type="submit" value="ok" class="right-arrow<?php echo $class; ?>">
                    </form>
                </div>
                <div class="grid-col col_size-4 tablet_size-6 mobile_size-12">
                    <div class="liste">
                        <h5>liste des mandats en cours</h5>
                        <?php while($query_biens->have_posts()) :
                            $query_biens->the_post(); ?>
                               <?php if($i == 0 ) echo '<div>'; 
                                     if($i == 3 ) echo '<div class="liste_deroulante hide">'; ?>                               
                                    <p><a href="<?php echo wami_get_page_link('ajouter-annonce').'/'.$post->ID; ?>" class="dossier dossier_<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?>">Dossier n°<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?></a></p>
                               <?php if($i == 2 || $i == $total-1) {echo '</div>'; } ?>
                            <?php $i++; ?>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="grid-col col_size-4 tablet_size-6 mobile_size-12">
                    <p class="resultat_recherche_titre">Résultat de votre recherche : </p><div class="resultat_recherche"></div>
                </div>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php // VENTES EN COURS
    $args = array(
        'post_type'     => 'biens',
        'post_status'   => 'publish',
        'author__in'    => array( $ambassadeur_id ),
        'meta_query'    => array( 
            'relation'  => 'AND', 
            array(
                'key'       => 'lien_mandat',
                'value'     => '',
                'compare'   => '!='
            ),            
            array(
                'key'       => 'bien_vente_date_signature_promesse',
                'value'     => '',
                'compare'   => '!='
            ),
            array(
                'relation' => 'OR',
                array(
                    'key'       => 'bien_vente_vendu',
                    'value'     => 0,
                    'compare'   => '='
                ),
                array(
                  'key' => 'bien_vente_vendu',
                  'compare' => 'NOT EXISTS'
                ), 
            )               
        ),
        'posts_per_page'=> -1
    );
    $query_biens = new WP_Query( $args );
    if($query_biens->have_posts()) :
        $total = $query_biens->post_count; 
        $i = 0;?>
        <div class="stat-bloc">
            <div class="w_grid limited-content"> 
                <h4><?php echo $total; ?> Vente<?php if($total>1) echo 's'; ?> en cours</h4>
                <div class="grid-col col_size-4 tablet_size-12 mobile_size-12">
                    <form role="search" method="get" class="single-field search_form_stat" id="search_vente_encours">
                        <input type="text" placeholder="<?php esc_attr_e("Rechercher", 'wami'); ?>" value="" />
                        <input type="submit" value="ok" class="right-arrow<?php echo $class; ?>">
                    </form>
                </div>
                <div class="grid-col col_size-4 tablet_size-12 mobile_size-12">
                    <div class="liste">
                        <h5>liste des ventes en cours</h5>
                        <?php while($query_biens->have_posts()) :
                            $query_biens->the_post(); ?>
                               <?php if($i == 0 ) echo '<div>'; 
                                     if($i == 3 ) echo '<div class="liste_deroulante hide">'; ?>                               
                                    <p><a href="<?php echo wami_get_page_link('ajouter-annonce').'/'.$post->ID; ?>" class="dossier dossier_<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?>">Dossier n°<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?></a></p>
                               <?php if($i == 2 || $i == $total-1) {echo '</div>'; } ?>
                            <?php $i++; ?>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="grid-col col_size-4 tablet_size-12 mobile_size-12">
                    <p class="resultat_recherche_titre">Résultat de votre recherche : </p><div class="resultat_recherche"></div>
                </div>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php // VENTE ANNEE
    $args = array(
        'post_type'     => 'biens',
        'post_status'   => 'publish',
        'author__in'    => array( $ambassadeur_id ),
        'meta_query'    => array( 
            'relation'  => 'AND', 
            array(
                'key'       => 'lien_mandat',
                'value'     => '',
                'compare'   => '!='
            ),              
            array(
                'key'       => 'bien_vente_vendu',
                'value'     => 1,
                'compare'   => '='
            ),                 
        ),
        'date_query' => array(
            array(
              'column' => 'post_date_gmt',
              'after'  => '1 year ago',
            ),
         ),
        'posts_per_page'=> -1
    );
    $query_biens = new WP_Query( $args );
    if($query_biens->have_posts()) :
        $total = $query_biens->post_count; 
        $i = 0;?>
        <div class="stat-bloc">
            <div class="w_grid limited-content"> 
                <h4><?php echo $total; ?> Vente<?php if($total>1) echo 's'; ?> cette année</h4>
                <div class="grid-col col_size-4 tablet_size-12 mobile_size-12">
                    <form role="search" method="get" class="single-field search_form_stat" id="search_vente_annee">
                        <input type="text" placeholder="<?php esc_attr_e("Rechercher", 'wami'); ?>" value="" />
                        <input type="submit" value="ok" class="right-arrow<?php echo $class; ?>">
                    </form>
                </div>
                <div class="grid-col col_size-4 tablet_size-12 mobile_size-12">
                    <div class="liste">
                        <h5>liste des ventes cette année</h5>
                        <?php while($query_biens->have_posts()) :
                            $query_biens->the_post(); ?>
                               <?php if($i == 0 ) echo '<div>'; 
                                     if($i == 3 ) echo '<div class="liste_deroulante hide">'; ?>                               
                                    <p><a href="<?php echo wami_get_page_link('ajouter-annonce').'/'.$post->ID; ?>" class="dossier dossier_<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?>">Dossier n°<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?></a></p>
                               <?php if($i == 2 || $i == $total-1) {echo '</div>'; } ?>
                            <?php $i++; ?>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="grid-col col_size-4 tablet_size-12 mobile_size-12">
                    <p class="resultat_recherche_titre">Résultat de votre recherche : </p><div class="resultat_recherche"></div>
                </div>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php //MANDATS ANNEE
    $args = array(
        'post_type'     => 'biens',
        //'post_status'   => 'publish',
        'author__in'    => array( $ambassadeur_id ),
        'meta_query'    => array( 
            'relation'  => 'AND', 
            array(
                'key'       => 'lien_mandat',
                'value'     => '',
                'compare'   => '!='
            ),             
            array(
                'relation' => 'OR',
                array(
                    'key'       => 'bien_vente_vendu',
                    'value'     => 0,
                    'compare'   => '='
                ),
                array(
                  'key' => 'bien_vente_vendu',
                  'compare' => 'NOT EXISTS'
                ), 
            )         
        ),
        'date_query' => array(
            array(
              'column' => 'post_date_gmt',
              'after'  => '1 year ago',
            ),
         ),
        'posts_per_page'=> -1
    );
    $query_biens = new WP_Query( $args );
    if($query_biens->have_posts()) :
        $total = $query_biens->post_count; 
        $i = 0;?>
        <div class="stat-bloc">
            <div class="w_grid limited-content"> 
                <h4><?php echo $total; ?> Mandat<?php if($total>1) echo 's'; ?> cette année</h4>
                <div class="grid-col col_size-4 tablet_size-12 mobile_size-12">
                    <form role="search" method="get" class="single-field search_form_stat" id="search_mandat_annee">
                        <input type="text" placeholder="<?php esc_attr_e("Rechercher", 'wami'); ?>" value="" />
                        <input type="submit" value="ok" class="right-arrow<?php echo $class; ?>">
                    </form>
                </div>
                <div class="grid-col col_size-4 tablet_size-12 mobile_size-12">
                    <div class="liste">
                        <h5>liste des mandats cette année</h5>
                        <?php while($query_biens->have_posts()) :
                            $query_biens->the_post(); ?>
                               <?php if($i == 0 ) echo '<div>'; 
                                     if($i == 3 ) echo '<div class="liste_deroulante hide">'; ?>                               
                                    <p><a href="<?php echo wami_get_page_link('ajouter-annonce').'/'.$post->ID; ?>" class="dossier dossier_<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?>">Dossier n°<?php echo sprintf("%06d", get_field('bien_ref', $post->ID)); ?></a></p>
                               <?php if($i == 2 || $i == $total-1) {echo '</div>'; } ?>
                            <?php $i++; ?>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="grid-col col_size-4 tablet_size-12 mobile_size-12">
                    <p class="resultat_recherche_titre">Résultat de votre recherche : </p><div class="resultat_recherche"></div>
                </div>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    <?php endif; ?> 

</section> 