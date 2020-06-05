<?php $user = wp_get_current_user();

// Si on est admin ou ambassadeur (sinon redirect to home)
if( in_array('administrator', $user->roles) || in_array('ambassadeur', $user->roles) || in_array('ambassadeur_responsable_de_region', $user->roles) ) : ?>


    <?php get_header('middle-office'); ?>

        <div class="tpl-demandes-list middle_office">
            
            <?php  $args = array(
                'post_author__in'   => array( $user->ID ),
                'status'            => 'hold',
                'hierarchical'      => true,
             );
            $comments_query = new WP_Comment_Query;
            $comments = $comments_query->query( $args );   ?>            
            <section id="demandes_en_attentes">                              
                <?php if(is_array($comments)) : ?> 
                    <div class="w_grid limited-content">                
                        <div class="grid-col col_size-12">   
                            <h2>Demandes en attente <span class="bulle"><?php echo count($comments); ?></span></h2>  
                        </div>
                    </div>  
                    <div class="w_grid limited-content">    
                        <ul class="contact-list tpl-tripple">  
                        <?php foreach($comments as $comment):
                            echo get_template_part('page_part/mo', 'demande-rappel-list');  
                        endforeach; ?>
                        </ul>
                    </div>
                <?php else : ?>
                    <div class="w_grid limited-content">                
                        <div class="grid-col col_size-12">   
                            <h2>Aucune demande en attente</h2>  
                        </div>
                    </div> 
                <?php endif; ?>    
            </section> 

            <?php  $args = array(
                'post_author__in'   => array( $user->ID ),
                'status'            => 'approve',
                'hierarchical'      => true,
             );
            $comments_query = new WP_Comment_Query;
            $comments = $comments_query->query( $args );   ?>  
            <section id="demandes">                              
                <?php if(is_array($comments)) : ?> 
                    <div class="w_grid limited-content">                
                        <div class="grid-col col_size-12">   
                            <h2>Historique de vos demandes</h2>  
                        </div>
                    </div>  
                    <div class="w_grid limited-content">    
                        <ul class="contact-list tpl-tripple">  
                        <?php foreach($comments as $comment):
                            echo get_template_part('page_part/mo', 'demande-rappel-list');  
                        endforeach; ?>
                        </ul>
                    </div>
                <?php else : ?>
                    <div class="w_grid limited-content">                
                        <div class="grid-col col_size-12">   
                            <h2>Aucune demande</h2>  
                        </div>
                    </div> 
                <?php endif; ?>    
            </section>                       
        

        </div>

    <?php get_footer(); ?>


<?php else : ?>
    <?php wp_redirect( home_url() ); exit; ?>


<?php endif; ?>