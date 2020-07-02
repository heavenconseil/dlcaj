<?php 
require_once get_template_directory().'/functions/dompdf/autoload.inc.php';
//require_once get_template_directory().'/functions/dompdf/src/FontMetrics.php';
//require_once get_template_directory()."/functions/dompdf/lib/php-font-lib/src/FontLib/Font.php";
use Dompdf\Dompdf;

function wami_create_pdf($mandat_id, $agent_id, $type){

    $ref_mandat     = get_field('bien_ref', $mandat_id) ? get_field('bien_ref', $mandat_id) : $mandat_id;
    $code_affaire   = "";
    if(have_rows('proprietaires', $mandat_id)) :
        while(have_rows('proprietaires', $mandat_id)): the_row();   
            $code_affaire = get_sub_field('proprietaire_societe') ? get_sub_field('proprietaire_raison_social') : get_sub_field('proprietaire_nom');               
        endwhile;
    endif; 
    $code_affaire   = substr(normalize_ref($code_affaire), 0, 5);

    // instantiate and use the dompdf class 
    $dompdf = new Dompdf();    
    // On recupere le contenu PDF
    ob_start();
        if($type == 'mandat') echo get_mandat($mandat_id, $agent_id);
        elseif($type == 'form_retractation') echo get_form_retractation($mandat_id);
        elseif($type == 'document_precontractuel') echo get_doc_precontractuel($mandat_id, $agent_id);
    $mandat_content = ob_get_clean();    
    //On envoi le contenu
    $dompdf->loadHtml($mandat_content);      
    //Converti le html en pdf
    $dompdf->render();
    $font = $dompdf->getFontMetrics()->get_font("akrobat-light", "normal");
    //$dompdf->set_option('helvetica', 'defaultFont');
    $date_mandat = date('d.m.Y');         
    //$dompdf->getCanvas()->page_text(32, 756, "Date d'édition : {$date_mandat}           Code affaire : {$code_affaire}          Code agence : DLCAJ1           État : Original            Paraphes :                            Page : {PAGE_NUM}/{PAGE_COUNT}", $font, 8, array(0,0,0));
    $dompdf->getCanvas()->page_text(50, 766, "Date d'édition : {$date_mandat}                                                        Code affaire : {$code_affaire} - Code agence : DLCAJ1                                                                État : Original - Page : {PAGE_NUM}/{PAGE_COUNT}", $font, 8, array(0,0,0));
    // Enregistre le fichier dans le dossier "mandats"
    $output = $dompdf->output();         
    $upload_dir = wp_upload_dir();
    $pdf_path = $upload_dir['basedir'].'/mandats/'.$type.'_'.$ref_mandat.'.pdf';
    file_put_contents($pdf_path, $output);      
    
    echo site_url().'/wp-content/uploads/mandats/'.$type.'_'.$ref_mandat.'.pdf?v='.time(); 
}


// LES APPELS AJAX
add_action('wp_ajax_nopriv_wami_get_mandat_to_pdf', 'wami_get_mandat_to_pdf');
add_action('wp_ajax_wami_get_mandat_to_pdf', 'wami_get_mandat_to_pdf');
function wami_get_mandat_to_pdf(){    
    if( isset($_REQUEST['mandat_id']) ) {
        $mandat_id      = $_REQUEST['mandat_id'];
        $agent_id       = $_REQUEST['agent_id'];
        wami_create_pdf($mandat_id, $agent_id, 'mandat'); 
    }     
    die();
} 

add_action('wp_ajax_nopriv_wami_get_form_retractation_to_pdf', 'wami_get_form_retractation_to_pdf');
add_action('wp_ajax_wami_get_form_retractation_to_pdf', 'wami_get_form_retractation_to_pdf');
function wami_get_form_retractation_to_pdf(){ 
    if( isset($_REQUEST['mandat_id']) ) {
        $mandat_id      = $_REQUEST['mandat_id'];
        $agent_id       = $_REQUEST['agent_id'];
        wami_create_pdf($mandat_id, $agent_id, 'form_retractation'); 
    }     
    die();
} 

add_action('wp_ajax_nopriv_wami_get_doc_precontractuel_to_pdf', 'wami_get_doc_precontractuel_to_pdf');
add_action('wp_ajax_wami_get_doc_precontractuel_to_pdf', 'wami_get_doc_precontractuel_to_pdf');
function wami_get_doc_precontractuel_to_pdf(){ 
    if( isset($_REQUEST['mandat_id']) ) {
        $mandat_id      = $_REQUEST['mandat_id'];
        $agent_id       = $_REQUEST['agent_id'];
        wami_create_pdf($mandat_id, $agent_id, 'document_precontractuel'); 
    }     
    die();
} 


// LES CONTENUS
function get_form_retractation($mandat_id){
    echo get_wami_pdf_style();
    $type_mandat = get_field("bien_mandat", $mandat_id);
    if(is_array($type_mandat)) $type_mandat = $type_mandat['value']; ?>  
    <div id="footer"></div>
    <div class="mandat">
        <?php echo formulaire_de_retractation($mandat_id, $type_mandat); ?>
    </div><?php
}


function get_doc_precontractuel($mandat_id, $agent_id){    
    echo get_wami_pdf_style();   
    // DEF DES VARIABLES et pour tout type de mandat    
    $ref_mandat         = get_field('bien_ref', $mandat_id) ? get_field('bien_ref', $mandat_id) : "";
    $mandat_id          = sprintf("%06d", $mandat_id);
    $agent_id           = sprintf("%06d", $agent_id);    
    $ambassadeur        = get_userdata($agent_id);
    $ambassadeur_id    = $ambassadeur->ID;    
    $ambassadeur_nom    = $ambassadeur->first_name;
    $ambassadeur_prenom = $ambassadeur->last_name;    
    $ambassadeur_email  = $ambassadeur->user_email;
    ?> 
    <div id="footer"></div>
    <div class="mandat">  
        <div class="page">          
            <?= get_pdf_logo(); ?>        
            <div class="titre_mandat">
                <p class="ligne_1 ligne_simple vert">INFORMATIONS PRECONTRACTUELLES</p>
                <p class="ligne_2 ligne_simple vert">PREALABLES A LA SIGNATURE D'UN MANDAT ET RGPD</p>
            </div>
            <div class="mention_mandat"><p class="">COMMUNICATION AU CONSOMMATEUR en application des articles L 111-1 et suivants du code de la consommation, le professionnel prestataire de services avec lequel vous entrez en relation vous informe.<p></div>
            <br>
            <?= get_pdf_adresse_agence('with_mention'); ?>
            <div class="entete_bloc_s"><?= get_pdf_represente_par($agent_id, $ambassadeur_id, $ambassadeur_nom, $ambassadeur_prenom, $ambassadeur_email); ?></div>
            <div class="entete_bloc_s">
            <?= get_pdf_consommateurs($mandat_id, 'Le(s) consommateur(s)'); ?>            
            <?= get_pdf_modalite_et_honoraires($ref_mandat); ?> 
            </div>
            <?= get_pdf_droit_retractation(); ?>
            <?= get_pdf_rgpd(); ?>
        </div>
        <div class="page">  
            <?= get_pdf_prevention_et_reglement_litiges(); ?>
            <?= get_pdf_mediation_litiges(); ?>
            <?= get_pdf_signature(); ?> 
        </div>    
    </div>
    <?php
}


function get_mandat($mandat_id, $agent_id){
    $upload_dir         = wp_upload_dir();     
    $ref_mandat         = get_field('bien_ref', $mandat_id) ? get_field('bien_ref', $mandat_id) : "";
    $type_mandat        = get_field("bien_mandat", $mandat_id);
    $agent_id           = sprintf("%06d", $agent_id);    
    $ambassadeur        = get_userdata( $agent_id );
    $ambassadeur_id     = $ambassadeur->ID;    
    $ambassadeur_nom    = $ambassadeur->last_name;
    $ambassadeur_prenom = $ambassadeur->first_name;  
    $ambassadeur_email  = $ambassadeur->user_email;

    $titre              = "MANDAT EXCLUSIF DE VENTE";
    $sous_titre         = "AVEC FACULTÉ DE RÉTRACTATION";
    if($type_mandat['value']=='mandat_simple') :
        $titre          = "MANDAT SIMPLE DE VENTE";
    elseif($type_mandat['value']=='mandat_de_recherche') :
        $titre          = "MANDAT DE RECHERCHE";
        $sous_titre     = "<span class='big'>ET DE NEGOCIATION EXCLUSIF</span>";
    elseif($type_mandat['value']=='mandat_delegation') : 
        $titre          = "DELEGATION DE MANDAT";
        $sous_titre     = "";        
        $ref_mandat     = "...";
    elseif($type_mandat['value']=='mandat_exigence') : 
        $titre          = "MANDAT EXIGENCE UN MOIS";
        $sous_titre     = "MANDAT EXCLUSIF DE VENTE AVEC FACULTÉ DE RÉTRACTATION";
    endif;
    
    echo get_wami_pdf_style();   ?>
    <div id="footer"></div>
    <div class="mandat">            
        
        <div class="page">

            <?= get_pdf_logo(); ?>        
            <div class="titre_mandat">
                <p class="ligne_1 ligne_simple vert"><?php echo $titre; ?> <span class="mandat_num">n° <?php echo $ref_mandat; ?></span></p>
                <p class="ligne_2 ligne_simple vert"><?php echo $sous_titre; ?><p>
            </div>        
            <?= get_pdf_adresse_agence(); ?>
            <div class="entete_bloc"><?= get_pdf_represente_par($agent_id, $ambassadeur_id, $ambassadeur_nom, $ambassadeur_prenom, $ambassadeur_email); ?></div>
            <?php 
            if($type_mandat['value']=='mandat_simple') : 
                echo get_pdf_consommateurs($mandat_id, 'Nous soussignés');
                echo get_pdf_intro_mandat(); 
                ?></div>
                <div class="page"><?php
                echo get_pdf_situation_designation('I', $mandat_id);
                echo get_pdf_prix('II', $mandat_id);
                echo get_pdf_honoraires('III', $mandat_id);
                echo get_pdf_conditions_particulieres('IV', $mandat_id);
                echo get_pdf_supperficie_loi_carrez('V', $mandat_id);
                ?></div>
                <div class="page"><?php
                echo get_pdf_dossier_diagnostique_technique('VI', $mandat_id);
                echo get_pdf_moyens_de_diffusion_des_annonces_commerciales('VII', $mandat_id);
                echo get_pdf_actions_particulieres('VIII', $mandat_id);
                echo get_pdf_modalite_et_periodicite_de_compte_rendu('IX', $mandat_id);
                echo get_pdf_plus_value_et_tva('X', $mandat_id);
                echo get_pdf_duree_du_mandat_et_obligations_du_mandant('XI', $mandat_id, 'mandat_simple');
                ?></div>
                <div class="page"><?php
                echo get_pdf_pouvoir_du_mandataire('XII', $mandat_id);
                ?></div>
                <div class="page"><?php
                echo get_pdf_mandat_simple('XIII', $mandat_id); 
                echo get_pdf_vente_sans_votre_concours('XIV', $mandat_id); 
                echo get_pdf_droit_de_retractation('XV', $mandat_id);
                echo get_pdf_mediation_des_litiges_de_la_consommation('XVI', $mandat_id);
                ?></div>
                <div class="page"><?php
                echo get_pdf_informatique_liberte_rgpd('XVII', $mandat_id);
                echo get_pdf_informatique_liberte_rgpd_suite();
                echo get_pdf_signature_mandats('mandat_simple');
                //echo formulaire_de_retractation($noms, $adresses);
                
            elseif($type_mandat['value']=='mandat_exclusif') : 
                echo get_pdf_consommateurs($mandat_id, 'Nous soussignés');
                echo get_pdf_intro_mandat(); 
                ?></div>
                <div class="page"><?php
                echo get_pdf_situation_designation('I', $mandat_id);
                echo get_pdf_prix('II', $mandat_id);
                echo get_pdf_honoraires('III', $mandat_id);
                echo get_pdf_conditions_particulieres('IV', $mandat_id);
                echo get_pdf_supperficie_loi_carrez('V', $mandat_id);
                ?></div>
                <div class="page"><?php
                echo get_pdf_dossier_diagnostique_technique('VI', $mandat_id);
                echo get_pdf_moyens_de_diffusion_des_annonces_commerciales('VII', $mandat_id);
                echo get_pdf_actions_particulieres('VIII', $mandat_id);
                echo get_pdf_modalite_et_periodicite_de_compte_rendu('IX', $mandat_id);
                echo get_pdf_plus_value_et_tva('X', $mandat_id);
                echo get_pdf_duree_du_mandat_et_obligations_du_mandant('XI', $mandat_id, 'mandat_exclusif');
                echo get_pdf_exclusivite_et_obligation_du_mandant('XII', $mandat_id, 'mandat_exclusif'); 
                // Saut de page dans le bloc ci dessus
                echo get_pdf_declaration_du_mandant('XIII', $mandat_id);
                echo get_pdf_pouvoir_du_mandataire('XIV', $mandat_id, 'mandat_exclusif');
                // Saut de page dans le bloc ci dessus
                echo get_pdf_droit_de_retractation('XV', $mandat_id);
                echo get_pdf_mediation_des_litiges_de_la_consommation('XVI', $mandat_id);
                echo get_pdf_informatique_liberte_rgpd('XVII', $mandat_id);
                echo get_pdf_informatique_liberte_rgpd_suite();
                echo get_pdf_signature_mandats('mandat_exclusif');

            elseif( $type_mandat['value']=='mandat_de_recherche' ) : 
                echo get_pdf_consommateurs($mandat_id, 'Nous soussignés', 'mandat_recherche');
                echo get_pdf_intro_mandat_recherche(); 
                ?></div>
                <div class="page"><?php 
                echo get_pdf_caracteristique_du_bien('I', $mandat_id);            
                echo get_pdf_honoraires_mandat_recherche('II', $mandat_id);
                echo get_pdf_actions_particulieres('III', $mandat_id, "mandat_recherche");
                echo get_pdf_modalite_et_periodicite_de_compte_rendu('IV', $mandat_id);   
                echo get_pdf_exclusivite_du_mandat('V', $mandat_id);  
                ?></div>
                <div class="page"><?php         
                echo get_pdf_duree_du_mandat_et_obligations_du_mandant('VI', $mandat_id, 'mandat_recherche');
                echo get_pdf_information_du_mandant_recherche('VII', $mandat_id); 
                echo get_pdf_mediation_des_litiges_de_la_consommation('VIII', $mandat_id);
                echo get_pdf_informatique_liberte_rgpd('IX', $mandat_id);
                ?></div>
                <div class="page"><?php   
                echo get_pdf_informatique_liberte_rgpd_suite();
                echo get_pdf_signature_mandats("mandat_recherche");

            elseif( $type_mandat['value']=='mandat_exigence' ) :  
                echo get_pdf_consommateurs($mandat_id, 'Nous soussignés');           
                echo get_pdf_intro_mandat(); 
                ?></div>
                <div class="page"><?php
                echo get_pdf_situation_designation('I', $mandat_id);
                echo get_pdf_prix('II', $mandat_id);
                echo get_pdf_honoraires('III', $mandat_id);
                echo get_pdf_conditions_particulieres('IV', $mandat_id);
                echo get_pdf_supperficie_loi_carrez('V', $mandat_id);              
                ?></div>
                <div class="page"><?php 
                echo get_pdf_dossier_diagnostique_technique('VI', $mandat_id);
                echo get_pdf_moyens_de_diffusion_des_annonces_commerciales('VII', $mandat_id);  
                echo get_pdf_actions_particulieres('VIII', $mandat_id);
                echo get_pdf_modalite_et_periodicite_de_compte_rendu('IX', $mandat_id);
                echo get_pdf_plus_value_et_tva('X', $mandat_id);
                echo get_pdf_duree_du_mandat_et_obligations_du_mandant('XI', $mandat_id, 'mandat_exigence');
                echo get_pdf_exclusivite_et_obligation_du_mandant('XII', $mandat_id, 'mandat_exigence');
                echo get_pdf_declaration_du_mandant('XIII', $mandat_id);
                echo get_pdf_pouvoir_du_mandataire('XIV', $mandat_id);     
                echo get_pdf_droit_de_retractation('XV', $mandat_id);
                echo get_pdf_mediation_des_litiges_de_la_consommation('XVI', $mandat_id);
                echo get_pdf_informatique_liberte_rgpd('XVII', $mandat_id);
                ?></div>
                <div class="page"><?php                 
                echo get_pdf_informatique_liberte_rgpd_suite();
                echo get_pdf_signature_mandats('mandat_exigence');
            
            elseif( $type_mandat['value']=='mandat_delegation' ) : 
                echo get_pdf_consommateurs_delegation($mandat_id);             
                ?></div>
                <div class="page"><?php 
                echo get_pdf_mandat_delegation($mandat_id);
                echo get_pdf_signature_mandats('mandat_delegation');
            endif; ?>

        </div>

    </div><?php
}
