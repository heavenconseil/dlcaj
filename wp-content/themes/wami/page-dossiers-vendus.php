<?php $user = wp_get_current_user();
$user_id    = isset($_GET['wamiforceuid']) ? $_GET['wamiforceuid'] : $user->ID;
$tpl = 'dossiers-vendus';

// Si on est admin ou ambassadeur (sinon redirect to home)
if( in_array('administrator', $user->roles) || in_array('ambassadeur', $user->roles) || in_array('ambassadeur_responsable_de_region', $user->roles) ) : ?>


    <?php get_header('middle-office'); ?>


        <div class="tpl-annonces-list middle_office">
            <section id="annonces_middle_office">

                
                <?php get_template_part('page_part/mo', 'titre-et-filtres'); ?>


                <div class="w_grid limited-content annonces_triees" data-tpl="<?php echo $tpl; ?>">       
                    <?php      
                    // TOUS LES BIENS VENDUS           
                    $paged    = 1;
                    $qte_post = 3;
                    $args = array(
                        'post_type'     => 'biens',
                        'post_status'   => 'publish',
                        'author__in'    => array( $user_id ),
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
                                    'value'     => 1,
                                    'compare'   => '='
                                ),
                                array(
                                    'key'       => 'bien_vente_date_signature_acte',
                                    'value'     => '',
                                    'compare'   => '!='
                                ), 
                            ),                 
                        ),
                        'meta_key'      => 'bien_ref',
                        'orderby'       => 'meta_value_num',
                        'order'         => 'DESC',
                        'paged'         => $paged,
                        'posts_per_page'=> $qte_post
                    );
                    $query_biens = new WP_Query( $args );
                    if($query_biens->have_posts()) : ?>
                        <?php while($query_biens->have_posts()) :
                            $query_biens->the_post(); ?>
                                <div class="grid-col col_size-12">
                                    <?php get_template_part('page_part/mo-biens-loop-'.$tpl); ?>
                                </div>
                        <?php endwhile; ?>                        
                        <?php //wami_pagination($query_biens); ?>                        
                        <div class="pagination">
                            <a class="button btn-secondary more-article" data-paged="<?php echo $paged; ?>" data-qte-post="<?php echo $qte_post; ?>">Voir plus de biens</a>
                        <?php wp_reset_postdata(); ?>
                    <?php else : ?>
                        <p>Aucun bien vendu.</p>
                    <?php endif; ?>      
                </div>

            </section>
        </div>

    <?php get_footer(); ?>


<?php else : ?>
    <?php wp_redirect( home_url() ); exit; ?>


<?php endif; ?>