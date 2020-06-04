<?php
/*
Template Name: TPL Tableau de bord (Ambassadeur)
*/
?>
<?php $user = wp_get_current_user();

// Si on est admin ou ambassadeur (sinon redirect to home)
if( in_array('administrator', $user->roles) || in_array('ambassadeur', $user->roles) || in_array('ambassadeur_responsable_de_region', $user->roles) ) : ?>


    <?php get_header('middle-office'); ?>

        <div class="tpl-demandes-attente middle_office">
            
            <?php  $args = array(
                'post_author__in'   => array( $user->ID ),
                'status'            => 'hold',
                'hierarchical'      => true,
             );
            $comments_query = new WP_Comment_Query;
            $comments = $comments_query->query( $args ); ?>

            <section id="demandes">            
                <div class="w_grid limited-content">
                    <div class="grid-col col_size-8 tablet_size-6 mobile_size-12 annonce-add">
                        <?php if(is_array($comments) && !empty($comments) ) : 
                            echo '<h2>Demandes en attente<span class="bulle compteur_to_clone_in_menu"> '.count($comments).'</span></h2>'; ?>
                            <?php $bien_id = $comments[0]->comment_post_ID;
                            $auteur = $comments[0]->comment_author; 
                            $email = $comments[0]->comment_author_email;
                            $horaire = get_comment_meta($comments[0]->comment_ID, 'horaire', true) ? get_comment_meta($comments[0]->comment_ID, 'horaire', true) : '';
                            $telephone = get_comment_meta($comments[0]->comment_ID, 'telephone', true) ? get_comment_meta($comments[0]->comment_ID, 'telephone', true) : ''; ?>
                            <a class="titre" href="<?php echo get_the_permalink($bien_id); ?>"><?php echo get_the_title($bien_id); ?></a>
                            <p><?php echo wami_get_villedubien($bien_id); ?> <span><?php echo wami_get_cpdubien($bien_id); ?></span></p>
                            <div class="personne">
                                <p class="titre">Personne à contacter</p>
                                <p><?php echo $auteur; ?></p>
                                <p><?php echo return_tel_french_format($telephone); ?> | <?php echo $email; ?></p>
                            </div>
                            <div class="creneau">
                                <p class="titre">Créneaux pour être contacté</p>
                                <p><?php echo $horaire; ?></p>
                            </div>
                            <div class="big_chevron">
                                <a href="<?php echo wami_get_page_link('demandes-en-attente'); ?>" class="button btn-accent"></a>
                            </div>
                        <?php else :
                            echo '<h2>Aucune demande en attente</h2>'; 
                        endif; ?>
                        <a href="<?php echo wami_get_page_link('demandes-en-attente'); ?>" class="button btn-accent">Voir toutes les demandes</a>
                    </div>
                    <div class="grid-col col_size-4 tablet_size-6 mobile_size-12 asside">
                        <?php if(is_array($comments) && !empty($comments) ) : 
                            $max_comment = count($comments)>2 ? 2 : count($comments);
                            echo '<h2>Historique de vos demandes</h2>'; ?>
                            <?php for($i=0; $i<$max_comment; $i++):
                                $bien_id = $comments[$i]->comment_post_ID;
                                $auteur = $comments[$i]->comment_author; 
                                $email = $comments[$i]->comment_author_email;
                                $telephone = get_comment_meta($comments[$i]->comment_ID, 'telephone', true) ? get_comment_meta($comments[$i]->comment_ID, 'telephone', true) : ''; ?>
                                <a class="titre" href="<?php echo get_the_permalink($bien_id); ?>"><?php echo get_the_title($bien_id); ?></a>
                                <p><?php echo $auteur; ?></p>
                                <p><?php echo return_tel_french_format($telephone); ?> | <?php echo $email; ?></p>                                
                            <?php endfor; ?>
                        <?php else :
                            echo '<h2>Aucune demande</h2>'; 
                        endif; ?>
                    </div>                                          
                </div>                
            </section>


            <section id="annonces">
                <?php 
                $args = array(
                    'post_type'     => 'biens',
                    'post_status'   => 'publish',
                    'author__in'    => array( $user->ID ),
                    'meta_query'    => array(                   
                        array(
                            'key'       => 'bien_disponible',
                            'value'     => 1,
                            'compare'   => '='
                        )
                    ),
                    'posts_per_page'=> 6
                );
                $query_biens = new WP_Query( $args );
                if($query_biens->have_posts()) : ?>                
                    
                    <div class="w_grid limited-content">                        
                        <div class="grid-col col_size-12">       
                            <h3>Vos annonces en résumé</h3>                       
                        </div>
                    </div>
                    <div class="w_grid limited-content">
                        <ul class="annonce-list tpl-tripple">                           
                            <?php while($query_biens->have_posts()) :
                                $query_biens->the_post(); ?>
                                 <li class="grid-col col_size-4 tablet_size-6 mobile_size-12 bottom-gutter">
                                        <div class="annonce">
                                            <?php echo get_template_part('page_part/loop', 'annonces_medium'); ?>   
                                        </div>
                                    </li>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        </ul>
                    </div>

                <?php endif; ?>

                <?php $args = array(
                    'post_type'     => 'biens',
                    'post_status'   => 'publish',
                    'author__in'    => array( $user->ID ),
                    'meta_query'    => array(
                        'relation'  => 'AND',
                        array(
                            'key'       => 'bien_coup_de_coeur',
                            'value'     => 1,
                            'compare'   => '='
                        ),                  
                        array(
                            'key'       => 'bien_disponible',
                            'value'     => 1,
                            'compare'   => '='
                        )
                    ),                          
                    'posts_per_page'=> 6
                );
                $query_biens = new WP_Query( $args );
                if($query_biens->have_posts()) : ?>
                    <div class="w_grid limited-content">                        
                        <div class="grid-col col_size-12">       
                            <h3>Vos coup de coeur</h3>                       
                        </div>
                    </div>
                    <div class="w_grid limited-content">    
                        <ul class="annonce-list tpl-tripple">
                            <?php while($query_biens->have_posts()) :
                                $query_biens->the_post(); ?>                                                            
                                <li class="grid-col col_size-4 tablet_size-6 mobile_size-12 bottom-gutter annonce_bien_trouve">
                                    <div class="annonce">
                                        <?php get_template_part('page_part/loop', 'annonces_medium'); ?>
                                    </div>
                                </li>
                            <?php endwhile; ?>                    
                            <?php wp_reset_postdata(); ?>                            
                        </ul>
                    </div>
                <?php endif; ?>               

            </section> 


            <?php echo get_template_part('page_part/mo', 'ambassadeur-stat'); ?> 
        

        </div>

    <?php get_footer(); ?>


<?php else : ?>
    <?php wp_redirect( home_url() ); exit; ?>


<?php endif; ?>