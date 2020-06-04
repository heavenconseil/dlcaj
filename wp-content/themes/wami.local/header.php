<?php get_template_part('header-part', 'head'); ?>

<header id="header" class="header-front">

    <div class="w_grid limited-content">        
        <div class="grid-col col_size-12">            

            <div id="responsive_header">
                <a href="<?php bloginfo('url'); ?>" id="logo"><?php bloginfo('name'); ?></a>
                <a href="#" id="responsive-show-menu"><span></span></a>
            </div>
            
            <div id="header-container">
                <a href="<?php bloginfo('url'); ?>" id="logo"><?php bloginfo('name'); ?></a>

                <ul id="account" class="logout">                    
                    <?php if( is_user_logged_in() ) : ?>
                        <li><a href="<?php echo wami_get_page_link('tableau-de-bord'); ?>" class="button btn-accent">Tableau de bord</a></li>
                        <li><a href="<?php echo wami_get_page_link('ajouter-annonce'); ?>" class="log-item">Publier votre annonce</a></li>
                    <?php else : ?>
                        <li><a href="<?php echo wami_get_page_link('connexion'); ?>" class="button btn-accent">login</a></li>
                    <?php endif; ?>
                </ul>
                
                <?php wp_nav_menu(array(
                    'theme_location' => 'menu-header',
                    'container'      => 'nav',
                    'menu_id'        => 'main-nav' 
                )); ?> 
                
            </div>

        </div> 
    </div>  

</header>