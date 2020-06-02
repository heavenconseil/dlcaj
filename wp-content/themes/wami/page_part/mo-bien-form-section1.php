<?php 
$user = wp_get_current_user();
$fields = array(   
    // TYPE DE MANDAT :
    'field_59b9263a3b7d2', // titre ancre                                  
    'field_597609cc2fc49', // type de mandat 
    // PROPRIETAIRE : 
    'field_597b32cee03c4', // titre ancre
    'field_597ee8b00566b', // titre ancre pour mandat de recherche
    'field_59786d67cfeaf', // propriétaire (repeater) 
    // ADRESSE BIEN : 
    'field_597b2f340904d', // titre ancre
    // 'field_5975f83884133', // adresse 1
    // 'field_597601e62fc3b', // bâtiment
    // 'field_597601892fc37', // adresse 2
    // 'field_597602022fc3c', // etage
    // 'field_597601ad2fc38', // CP
    // 'field_5976020d2fc3d', // code porte 1
    // 'field_597601ba2fc39', // Ville
    // 'field_597602202fc3e', // code porte 2
    // 'field_5975f9198413d', // Pays
    'field_5975f83884133', // adresse 1
    'field_59760acb23896', // arrondissement
    'field_597601892fc37', // adresse 2
    'field_597601ad2fc38', // CP
    'field_597601e62fc3b', // bâtiment
    'field_597601ba2fc39', // Ville
    'field_597602022fc3c', // etage
    'field_59760af223897', // Région
    'field_5976020d2fc3d', // code porte 1
    'field_5975f9198413d', // Pays   
    'field_597602202fc3e', // code porte 2
    //'field_5901b59d67593', // adresse google map
    // TYPE DE BIEN :
    'field_597b333fe5ae3', // titre ancre
    'field_591eed6e686f3', // désignation du bien    
    'field_5900744778a56', // Superficie loi Carrez
    'field_59b0066f1436b', // Message loi carrez
    'field_5976030a2fc43', // Acquis par acte chez maitre
    'field_599e9cb4252e3', // Type de vente (libre de toute occupation ou selon contrat)
    'field_59b927d9272ca', // Date du bail si vendu occupé (loué...)
    //'field_597602bf2fc41', // Vendu libre de toute occupation
    //'field_597602e32fc42', // Loué selon le contrat de bail et les conditions ci-annexés
    // PRTX
    'field_597b3354e5ae4', // titre ancre
    'field_597603552fc45', // Prix honoraires inclus
    'field_5900742378a55', // Prix sans honoraires
    // HONORAIRES
    'field_597b3366e5ae5', // titre ancre
    'field_597609192fc47', // honoraires à la charge de 
    //'field_59088607dfe53', // Montant TTC des honoraires    
    // INFORMATIONS DIAGNOSTIC
    'field_59ae8e5f72336', // Titre ancre
    'field_5977461295c29', // Date de diagnostics
    'field_59ae938a46d02', // Dossier des diagnostics complet
    'field_59ae93c146d03', // État amiante 
    'field_59ae9465294e3', // Constat de risque d’exposition au plomb 
    'field_59ae9473294e4', // État parasitaire 
    'field_59ae94c9294e5', // Constat des risques miniers, naturels et technologiques 
    'field_59ae94d7294e6', // Diagnostic de performance énergétique 
    'field_59ae94f3294e7', // État de l’installation intérieure d’électricité 
    'field_59ae958b294e8', // État de l’installation intérieure de gaz 
    'field_59ae959d294e9', // Contrôle assainissement non collectif
    'field_59ae95b7294ea', // Bornage 
    // CONDITION PARTICULIERES
    'field_59ae991e2170a', // titre ancre 
    'field_59db9995e00c6', // titre ancre 2 pr délégation et de recherche
        // MANDAT DE RECHERCHE
        'field_59d6326e80b75', // Caractéristique du bien
        'field_5d4166d373b96', // Format des Honoraires (en euros ou %)
        'field_59d632ad80b77', // Montant des honoraires en %
        'field_5d3997c8562e4', // Montant des honoraires en €
        'field_59d6329680b76', // Prix maximum souhaité
        'field_59d6392b6fdf0', // Affaires présentées ce jour ou visitées
        // DELEGATION DE MANDAT 
        'field_59d779bb15ae6', // Nom, Prénom du bénéficiaire de la délégation
        'field_59d77a475fd94', // Email du bénéficiaire de la délégation
        'field_59d77a075fd93', // Adresse du bénéficiaire de la délégation
        'field_59d77a5b5fd95', // Téléphone du bénéficiaire de la délégation
        'field_59d77adf5fd97', // Honoraires : % des honoraires perçus  
        'field_59d77a995fd96', // Mandat délégué 
        'field_59d77ecc864a3', // Mandat délégué concernant
    'field_59ae984921705', // Moyen de diffusion des annonces commerciales
    'field_59ae984421704', // Conditions particulières
    'field_59ae984e21707', // modalités et périoticité des comptes rendus
    'field_59ae984b21706', // Actions particulières
    // DOCUMENTS ANNEXES
    'field_597b337ce5ae6', // titre ancre
    'field_59760a5b2fc4b', // Lien de téléchargement vers une dropbox   
);

$text_after = "<div class='hide_for_mandatrecherche_or_delegationmandat'><p class='info_divers'>RÉGLEMENT DE COPROPRIÉTÉ</p>
<p class='info_divers'>DOSSIER DE DIAGNOSTIC TECHNIQUE</p>
<p class='info_divers'>CONDITIONS LOCATIVES</p>
<p class='info_divers'>TITRE DE PROPRIÉTÉ</p>
<p class='info_divers'>TROIS DERNIÈRES AG</p>
<p class='info_divers'>RELEVE DE CHARGE (UN AN)</p>
<p class='info_divers'>TAXE FONCIÈRE</p>
<p class='info_divers last'>AUTRE</p></div>";

if(get_query_var('page')) {
    $type_de_mandat = get_field("bien_mandat", get_query_var('page'));
    //debug($type_de_mandat);
    // Si notre mandat est soit un mandat de recherche soit une délagtion de mandat on remet le text_after à vide
    if( is_array($type_de_mandat) && ($type_de_mandat['value']=="mandat_de_recherche" || $type_de_mandat['value']=="mandat_delegation") )
        $text_after = "<div class='separateur'></div>";

    if( get_post_meta(get_query_var('page'), 'mandat_print', true) ) 
        $text_after .= "<a href='#confirm_print_mandat' class='button btn-primary open_popin' data-openpopin='confirm_print_mandat' data-mandatid='".get_query_var('page')."'  data-cid='".$user->ID."'>";
    else 
         $text_after .= "<a href='#' class='button btn-primary dl_mandat' data-mandatid='".get_query_var('page')."'  data-cid='".$user->ID."'>";
    $text_after .= 'TÉLÉCHARGER ET IMPRIMER LE MANDAT</a>';
    
    //if (is_array($type_de_mandat) && ($type_de_mandat['value']=="mandat_simple" || $type_de_mandat['value']=="mandat_exclusif" || $type_de_mandat['value']=="mandat_exigence") )
        $text_after .= "<a href='#' class='button btn-primary dl_doc_precontractuel' data-mandatid='".get_query_var('page')."'  data-cid='".$user->ID."'>DOCUMENT PRECONTRACTUEL</a>"; ?>

    <div id="confirm_print_mandat" class="popin-layer close">
        <div class="popin confirm_print_mandat">
            <p><span class='bold'>Attention</span> ce mandat a déjà été signé et ne doit pas être modifié.</p> 
            <?php if( get_field('bien_honoraires_montant', get_query_var('page')) != get_field('bien_prix_et_honoraires', get_query_var('page')) ) : ?>
                <p>le prix renseigné est différent du prix original du mandat, contactez l'administrateur si vous souhaitez modifier le prix du mandat.</p> 
            <?php endif; ?>
            <h4>Souhaitez vous à nouveau le télécharger ?</h4><br>
            <a href='#' class='button btn-primary dl_mandat close_popin' data-mandatid='<?php echo get_query_var('page'); ?>'  data-cid='<?php echo $user->ID; ?>' data-closepopin='confirm_print_mandat'>TÉLÉCHARGER ET IMPRIMER LE MANDAT</a>
        </div>
    </div><?php   
}
?>


        <?php $args_bien = array(
            'post_id'           => 'new_post',
            'new_post'          => true,
            'fields'            => $fields,
            'field_el'          => 'div',
            'form'              => true, 
            'new_post'          => array(
                        'post_type'   => 'biens',
                        'post_status' => 'pending',
            ),  
            'html_after_fields' => $text_after,           
            'submit_value' => 'Enregistrer'
        ); ?>  
        
        <?php if(get_query_var('page')) :
                                                                
            $args_bien['post_id']       = get_query_var('page');
            $args_bien['new_post']      = false;

            $args_query = array(
                'post__in'  => array(get_query_var('page')),
                'post_type' => 'biens',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'));
            $my_posts = new WP_Query($args_query); ?>                                    
           
            <?php if($my_posts->have_posts()) :
                while($my_posts->have_posts()) : 
                    $my_posts->the_post();
                        acf_form($args_bien); ?>                                
                <?php endwhile; ?>                                   
            <?php endif; ?>

        <?php else : ?> 

            <?php acf_form($args_bien); ?>
        
        <?php endif; ?>