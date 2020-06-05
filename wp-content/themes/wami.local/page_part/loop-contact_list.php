<li class="w_grid limited-content">    
    <div class="grid-col col_size-4 mobile_size-12 nom"><a href="<?php echo home_url('tableau-de-bord/carnet-adresses/'.$post->ID); ?>"><?php the_title(); ?></a></div>    
    <div class="grid-col col_size-4 mobile_size-6 col_2">
        <div class="mail">
            <p class="titre">email</p>
            <a href="mailto:<?php echo get_field('contact_email') ? get_field('contact_email') : ''; ?>"><?php echo get_field('contact_email') ? get_field('contact_email') : ''; ?></a>
        </div>
        <div class="adresse">
            <p class="titre">Adresse</p>            
            <?php echo get_field('contact_adresse_1') ? '<p>'.get_field('contact_adresse_1').'</p>' : ''; ?>
            <?php echo get_field('contact_adresse_2') ? '<p>'.get_field('contact_adresse_2').'</p>' : ''; ?>
            <?php echo get_field('contact_adresse_batiment') ? '<p>Batiment : '.get_field('contact_adresse_batiment').'</p>' : ''; ?>
            <p><?php echo get_field('contact_adresse_cp') ? '<span>'.get_field('contact_adresse_cp').'</span>' : ''; ?>
            <?php echo get_field('contact_adresse_ville') ? '<span>'.get_field('contact_adresse_ville').'</span>' : ''; ?></p>            
            <?php echo get_field('contact_adresse_pays') ? '<p>'.get_field('contact_adresse_pays').'</p>' : ''; ?>            

            <?php echo get_field('contact_adresse_region') ? '<p>Région : '.get_field('contact_adresse_region').'</p>' : ''; ?>
            <?php echo get_field('contact_adresse_etage') ? '<p>Etage : '.get_field('contact_adresse_etage').'</p>' : ''; ?>
            <?php echo get_field('contact_adresse_code_porte_1') ? '<p>Code porte 1 : '.get_field('contact_adresse_code_porte_1').'</p>' : ''; ?>
            <?php echo get_field('contact_adresse_code_porte_2') ? '<p>Code porte 2 : '.get_field('contact_adresse_code_porte_2').'</p>' : ''; ?>
        </div>
    </div>
    <div class="grid-col col_size-4 mobile_size-6 col_3">
        <div class="telephone">
            <p class="titre">Téléphone</p>
            <p><?php echo get_field('contact_telephone') ? return_tel_french_format(get_field('contact_telephone')) : ''; ?></p>
        </div>
        <div class="type_contact">
            <p class="titre">Type de contact</p>            
            <p><?php echo get_field('contact_type_de_contact') ? get_field('contact_type_de_contact') : ''; ?></p>
        </div>
    </div>   
    <a href="#" class="middle-office-accordion chevron"></a> 
</li>