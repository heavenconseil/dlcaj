<?php 
include_add_action_for_acf_form();
$user       = wp_get_current_user();
$user_id    = isset($_GET['wamiforceuid']) ? $_GET['wamiforceuid'] : $user->ID;
$tpl = 'annonces';

// Si on est admin ou ambassadeur (sinon redirect to home)
if( in_array('administrator', $user->roles) || in_array('ambassadeur', $user->roles) || in_array('ambassadeur_responsable_de_region', $user->roles)  ) : ?>


    <?php get_header('middle-office'); ?>


        <div class="tpl-annonces-list middle_office">
            <section id="annonces_middle_office">

                
                <?php get_template_part('page_part/mo', 'titre-et-filtres'); ?>


                <div class="w_grid limited-content annonces_triees" data-tpl="<?php echo $tpl; ?>">       
                    <?php    
                    // TOUS LES BIENS (tous y compris les brouillons) SAUF LES BIENS AVEC PROMESSE DE VENTE OU VENDUS   
                    $user        = wp_get_current_user();
                    $user_id     = isset($_GET['wamiforceuid']) ? $_GET['wamiforceuid'] : $user->ID;  
                    //$paged       = 1;
                    $qte_post    = 3; 
                    $trie_par    = 'mandat';
                    $query_biens = wami_mo_request_annonces($user_id, '0', $qte_post, $trie_par);
                    
                    if($query_biens && is_array($query_biens)) :
                        foreach($query_biens as $k=>$b) :
                            echo $k<3 ? '<div class="grid-col col_size-12 all-annonces ligne-'.$k.'">' : '<div class="grid-col col_size-12 all-annonces ligne-'.$k.'" style="display:none;">';
                                wami_mo_request_annonces_display( get_post($b->ID) );
                                //get_template_part('page_part/mo-biens-loop-'.$tpl); 
                            echo '</div>';
                        endforeach; ?>
                        <div class="pagination">
                            <a class="button btn-secondary show-more-article" data-from="0" data-to="<?php echo $qte_post; ?>"  data-qte-post="<?php echo $qte_post; ?>" data-total="<?php echo count($query_biens); ?>">Voir plus de biens</a>
                        </div>
                    <?php else : ?>
                        <p>Aucune annonce publiée.</p>
                    <?php endif;  ?>      
                </div>

            </section>
        </div>
    

    <?php acf_enqueue_uploader(); ?>    

    <?php get_footer(); ?>


<?php else : ?>
    <?php wp_redirect( home_url() ); exit; ?>


<?php endif; ?>