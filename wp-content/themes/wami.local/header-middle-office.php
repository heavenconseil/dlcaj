<?php get_template_part('header-part', 'head'); ?>

<?php $user = wp_get_current_user(); ?>

<header id="header" class="header-middle-office">
       
    <div class="full_width_bg">        
        <div class="w_grid limited-content"> 
            <div class="grid-col col_size-12">

                <div id="responsive_header">
                    <a href="<?php bloginfo('url'); ?>" id="logo"><?php bloginfo('name'); ?></a>
                    <a href="<?php bloginfo('url'); ?>" class="button btn-md-office ico-left arrow-left desktop-and-tablet">Retour au site</a>
                    <a href="#" id="responsive-show-menu"><span></span></a>
                </div>
                
                <div id="header-container">
                    <a href="<?php bloginfo('url'); ?>" id="logo"><?php bloginfo('name'); ?></a>
                    
                    <a href="<?php bloginfo('url'); ?>" class="button btn-md-office ico-left arrow-left mobile-and-desktop">Retour au site</a>

                    <?php $user = wp_get_current_user(); 
                    if($user) : ?>
                        <ul class="liens_profil dropdown desktop-only">
                            <li><?php echo $user->display_name; ?>
                                <ul class="drop-down-content">
                                    <li><a href="<?php echo wami_get_page_link('mon-profil'); ?>">Mon profil</a></li>
                                    <li><a href="<?php echo wp_logout_url( home_url() ); ?>">Déconnexion</a></li>
                                </ul>
                            </li>                        
                        </ul>
                        <ul class="mobile-profil tablet-and-mobile">
                            <li><a href="<?php echo wami_get_page_link('mon-profil'); ?>">Mon profil</a></li>
                            <li><a href="<?php echo wp_logout_url( home_url() ); ?>">Déconnexion</a></li>
                        </ul>
                    <?php endif; ?>
                    

                    <?php wp_nav_menu(array(
                        'theme_location' => 'menu-header-middle-office1',
                        'container'      => 'nav',
                        'menu_id'        => 'admin-nav' 
                    )); ?> 

                </div>  

            </div>             
        </div> 
    </div> 

    <div class="w_grid limited-content"> 

        <div class="grid-col col_size-12">


            <?php 
            $second_menu = (in_array('administrator', $user->roles) || in_array('ambassadeur_responsable_de_region', $user->roles)) ? 'menu-header-middle-office2-resp-region' :  'menu-header-middle-office2';
            wp_nav_menu(array(
                'theme_location' => $second_menu,
                'container'      => 'nav',
                'menu_id'        => 'main-nav' 
            )); ?>

            

        </div>


    </div>                
           
</header>