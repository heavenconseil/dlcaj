<?php 
require_once get_template_directory().'/functions/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

add_action('wp_ajax_nopriv_wami_get_mandat_to_pdf', 'wami_get_mandat_to_pdf');
add_action('wp_ajax_wami_get_mandat_to_pdf', 'wami_get_mandat_to_pdf');
function wami_get_mandat_to_pdf(){    
    if( isset($_REQUEST['mandat_id']) ) { 

        $mandat_id = $_REQUEST['mandat_id'];
        $agent_id = $_REQUEST['agent_id'];
        $ref_mandat = get_field('bien_ref', $mandat_id) ? get_field('bien_ref', $mandat_id) : $mandat_id;

        $code_affaire = "";
        if(have_rows('proprietaires', $mandat_id)) :
            while(have_rows('proprietaires', $mandat_id)): the_row();   
                $code_affaire = get_sub_field('proprietaire_societe') ? get_sub_field('proprietaire_raison_social') : get_sub_field('proprietaire_nom');               
            endwhile;
        endif; 
        $code_affaire = substr(normalize_ref($code_affaire), 0, 5);

        
        // instantiate and use the dompdf class 
        $dompdf = new Dompdf();
        
        // On recupere le contenu PDF
        ob_start();
            echo get_mandat($mandat_id, $agent_id);
        $mandat_content = ob_get_clean();
        
        //On envoi le contenu
        $dompdf->loadHtml($mandat_content);        
        
        //Converti le html en pdf
        $dompdf->render();
        $font = $dompdf->getFontMetrics()->get_font("helvetica", "normal");
        $date_mandat = date('d.m.Y');         
        $dompdf->getCanvas()->page_text(32, 756, "Date d'édition : {$date_mandat}           Code affaire : {$code_affaire}          Code agence : DLCAJ1           État : Original            Paraphes :                            Page : {PAGE_NUM}/{PAGE_COUNT}", $font, 8, array(0,0,0));

        // Enregistre le fichier dans le dossier "mandats"
        $output = $dompdf->output();         
        $upload_dir = wp_upload_dir();
        $pdf_path = $upload_dir['basedir'].'/mandats/mandat_'.$ref_mandat.'.pdf';
        file_put_contents($pdf_path, $output);         

        //$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
        //$dompdf->stream();// Output the generated PDF to Browser   
        
        echo site_url().'/wp-content/uploads/mandats/mandat_'.$ref_mandat.'.pdf?v='.time();      
    }    
    die();
} 

function get_mandat($mandat_id, $agent_id){
    ?>
    <style>
        @import url("//hello.myfonts.net/count/337be5");

        @font-face {font-family: 'AvenirLTPro-Medium';src: url('../lib/webfonts/337BE5_0_0.eot');src: url('../lib/webfonts/337BE5_0_0.eot?#iefix') format('embedded-opentype'),url('../lib/webfonts/337BE5_0_0.woff2') format('woff2'),url('../lib/webfonts/337BE5_0_0.woff') format('woff'),url('../lib/webfonts/337BE5_0_0.ttf') format('truetype');}
        @font-face {font-family: 'AvenirLTPro-BookOblique';src: url('../lib/webfonts/337BE5_1_0.eot');src: url('../lib/webfonts/337BE5_1_0.eot?#iefix') format('embedded-opentype'),url('../lib/webfonts/337BE5_1_0.woff2') format('woff2'),url('../lib/webfonts/337BE5_1_0.woff') format('woff'),url('../lib/webfonts/337BE5_1_0.ttf') format('truetype');}          
        @font-face {font-family: 'AvenirLTPro-Book';src: url('../lib/webfonts/337BE5_2_0.eot');src: url('../lib/webfonts/337BE5_2_0.eot?#iefix') format('embedded-opentype'),url('../lib/webfonts/337BE5_2_0.woff2') format('woff2'),url('../lib/webfonts/337BE5_2_0.woff') format('woff'),url('../lib/webfonts/337BE5_2_0.ttf') format('truetype');}
        @font-face {font-family: 'AvenirLTPro-MediumOblique';src: url('../lib/webfonts/337BE5_3_0.eot');src: url('../lib/webfonts/337BE5_3_0.eot?#iefix') format('embedded-opentype'),url('../lib/webfonts/337BE5_3_0.woff2') format('woff2'),url('../lib/webfonts/337BE5_3_0.woff') format('woff'),url('../lib/webfonts/337BE5_3_0.ttf') format('truetype');}
        @font-face {font-family: 'AvenirLTPro-Heavy';src: url('../lib/webfonts/337BE5_4_0.eot');src: url('../lib/webfonts/337BE5_4_0.eot?#iefix') format('embedded-opentype'),url('../lib/webfonts/337BE5_4_0.woff2') format('woff2'),url('../lib/webfonts/337BE5_4_0.woff') format('woff'),url('../lib/webfonts/337BE5_4_0.ttf') format('truetype');}
        @font-face {font-family: 'AvenirLTPro-HeavyOblique';src: url('../lib/webfonts/337BE5_5_0.eot');src: url('../lib/webfonts/337BE5_5_0.eot?#iefix') format('embedded-opentype'),url('../lib/webfonts/337BE5_5_0.woff2') format('woff2'),url('../lib/webfonts/337BE5_5_0.woff') format('woff'),url('../lib/webfonts/337BE5_5_0.ttf') format('truetype');}
        @font-face {font-family: 'ITCBlair-Medium';src: url('../lib/webfonts/337BE5_6_0.eot');src: url('../lib/webfonts/337BE5_6_0.eot?#iefix') format('embedded-opentype'),url('../lib/webfonts/337BE5_6_0.woff2') format('woff2'),url('../lib/webfonts/337BE5_6_0.woff') format('woff'),url('../lib/webfonts/337BE5_6_0.ttf') format('truetype');}    

        .mandat{
            font-weight: 400;
            font-family: "AvenirLTPro-Book", Verdana, sans-serif;
            font-size: 12px;
        }
        .bold{font-weight: bold;}
        .vert{color: #979d5d;}
        .center{text-align: center;}
        .uppercase{text-transform: uppercase;}
        a{text-decoration: none;}
        p.ligne_simple{margin: 0;}
        h1{
            font-family: "ITCBlair-Medium", Verdana, sans-serif;
            text-transform: uppercase;
            font-size: 22px;
            color: #383838;
            font-weight: 400;
            text-align: center;
            margin:0;
        }
        h2{
            font-family: "AvenirLTPro-Medium", serif;
            font-size: 16px;        
            font-weight: 400;
            text-align: center;
            margin:0;
        }
        .titre_mandat{
            border: 2px solid #cbcead;
            text-align: center;
            padding: 10px;
            margin: 30px 0;
            height: 65px;
        }
        .titre_mandat p, .titre_mandat p.ligne_2.big{
            font-family: "ITCBlair-Medium", Verdana, sans-serif;
            text-transform: uppercase;
            font-size: 28px;
            color: #979d5d;
            font-weight: bold;
            letter-spacing: 3;
        }
        .titre_mandat.retractation{height: auto;}
        .titre_mandat.retractation p{letter-spacing: normal;}
        .titre_mandat p.ligne_2 { 
            font-size: 16px; 
            font-weight: normal;
            letter-spacing: normal;
            margin: 0;
        }
        .titre_mandat.small{
            text-transform: normal;
            font-size: 18px;
            letter-spacing: normal;
            margin: 0;
        }
        .mandat_num{padding-left: 20px;}
        h3{
            font-family: "ITCBlair-Medium", Verdana, sans-serif;
            text-transform: uppercase;
            font-size: 20px;
            color: #979d5d;
            font-weight: bold;
        }
        h3:after{
            content:'';
            display: block;
            width: 100%;
            height: 2px;
            background-color: #979d5d;
        }     
        .adresse_dlcaj{
            margin-left: 400px;
        }
        .entete_bloc{
            padding: 10px 0;
        }
        .bloc{
            padding-top: 20px;
        }    
        table{
            width: 100%;
            border: none;
        }
        tr{
            width: 100%;
            text-align: left;
            padding: 0;
            margin: 0;
        }
        td{
           width: 46%;
           padding: 0 0 0 4%; 
           vertical-align: top;           
        }
        td.left{ padding: 0 4% 0 0; }
        .small{
            font-size: 10px;
            line-height: 12px;
        }
        .small.bold{line-height: 14px;}
        .mention{
            font-size: 8px;
            font-style: italic;
            padding: 0 0 20px;
        }
        .espace_blanc{
            height: 100px;
        }
        label{
            float: left; 
            padding-left: 20px;
        }
        .signature{
            margin-top: 50px;
        }
        .signature_cadre{
            width: 39%;
            border: 1px solid #383838;
            padding: 0 10px 80px;
        }
        .signature_infos{                
            width: 22%;
            border: 1px solid #383838;
            padding: 0 10px 10px;
        }
        .separateur{width: 0%;}
        #footer { 
            position: fixed; 
            border-top:2px solid #383838;
            bottom: 15px;
        } 
        /*.pagenum:before { content: counter(page); }*/
    </style>


    <?php // DEF DES VARIABLES :
    $ref_mandat = get_field('bien_ref', $mandat_id) ? get_field('bien_ref', $mandat_id) : "";
    $type_mandat = get_field("bien_mandat", $mandat_id); //bien_mandat       
    // postulat de départ : mandat_exclusif
    $titre = "MANDAT EXCLUSIF DE VENTE";
    $sous_titre = "AVEC FACULTÉ DE RÉTRACTATION";
    
    // Mais si mandat simple 
    if($type_mandat['value']=='mandat_simple') :
        $titre = "MANDAT SIMPLE DE VENTE <span class='small'>(sans exclusivité)</span>";
        $sous_titre = "AVEC FACULTÉ DE RÉTRACTATION";
    elseif($type_mandat['value']=='mandat_de_recherche') :
        $titre = "MANDAT DE RECHERCHE";
        $sous_titre = "<span class='big'>ET DE NEGOCIATION EXCLUSIF</span>";
    elseif($type_mandat['value']=='mandat_delegation') : 
        $titre = "DELEGATION DE MANDAT";
        $sous_titre = "";
    endif;

    //et pour tout type de mandat
    //$mandat_id = sprintf("%06d", $mandat_id);
    $agent_id = sprintf("%06d", $agent_id);    
    $ambassadeur = get_userdata( $agent_id );
    $ambassadeur_nom = $ambassadeur->last_name;
    $ambassadeur_prenom = $ambassadeur->first_name;
    ?>

    <div id="footer"><!--Page <span class="pagenum"></span>--></div>
    <div class="mandat">

        <div class="center"><img src="<?php echo get_template_directory(); ?>/lib/img/logo-middle-office.png"/></div>
        <h1>De la cour au jardin</h1>
        <h2>Vous êtes déjà chez vous</h2>
        
        <div class="titre_mandat">
            <p class="ligne_1 ligne_simple vert"><?php echo $titre; ?></p>
            <p class="ligne_2 ligne_simple vert"><?php echo $sous_titre; ?> <span class="mandat_num">MANDAT N°<?php echo $ref_mandat; ?></span><p>
        </div>

        <div class="adresse_dlcaj">
            <p class="ligne_simple">SAS SEVEN & SEVEN DE LA COUR AU JARDIN</p>
            <p class="ligne_simple">RCP VD7.000.001 / 20372</p>
            <p class="ligne_simple">RCS Paris 811 493 162</p>
            <p class="ligne_simple">Carte CPI 7501 2017 000 015964</p>
            <p class="ligne_simple">157 rue du Faubourg Saint Antoine 75011 PARIS</p>
            <p class="ligne_simple">contact@delacouraujardin.com</p>
        </div>

        <div class="entete">
            <div class="entete_bloc">
                <p><span class="bold">Représentée par :</span>
                    <?php echo '<span class="uppercase">'.$ambassadeur_nom.'</span> '.$ambassadeur_prenom; ?>
                </p>
                <p>Qualité : 
                    <?php $type_amb = get_field('type_ambassadeur', 'user_'.$agent_id);
                    echo (isset($type_amb) && is_array($type_amb)) ? $type_amb['label']: ""; ?>
                    <?php echo get_field('type_ambassadeur_negociateur', 'user_'.$agent_id) ? ' '.get_field('type_ambassadeur_negociateur', 'user_'.$agent_id) : ""; ?>
                    <?php echo get_field('type_ambassadeur_agent_rsac_ville', 'user_'.$agent_id) ? ' inscrit au RSAC de '.get_field('type_ambassadeur_agent_rsac_ville','user_'.$agent_id) : ""; ?>
                    <?php echo get_field('type_ambassadeur_agent_rsac_num', 'user_'.$agent_id) ? '<br>sous le n° '.get_field('type_ambassadeur_agent_rsac_num', 'user_'.$agent_id) : ""; ?>
                </p>   
                <table>
                    <tr>
                        <td class="left">E-mail : <?php echo $ambassadeur->user_email; ?></td>
                        <td>Téléphone : <?php echo get_field('ambassadeur_telephone', 'user_'.$agent_id); ?></td>
                    </tr>
                </table>             
            </div>
            <br> 
            
            <?php if( $type_mandat['value']!='mandat_delegation' ) : ?>
                <div class="entete_bloc">
                    <p class="ligne_simple bold">Nous soussignés </p>
                    <?php $noms = $adresses = $emails = $telephones = '';
                    if(have_rows('proprietaires', $mandat_id)) : ?>
                        <?php while(have_rows('proprietaires', $mandat_id)): the_row(); 
                            $nom = get_sub_field('proprietaire_societe') ? get_sub_field('proprietaire_raison_social').' représentée par : '.get_sub_field('proprietaire_société_attention_de') : '<span class="uppercase">'.get_sub_field('proprietaire_nom').'</span> '.get_sub_field('proprietaire_prenom'); 
                            $noms .= $nom.'<br>';
                            $adresses .= '<p class="ligne_simple">';
                            $adresses .= get_sub_field('proprietaire_adresse') ? get_sub_field('proprietaire_adresse') : "";
                            $adresses .= '<br>';
                            $adresses .= get_sub_field('proprietaire_cp') ? get_sub_field('proprietaire_cp').' ' : "";
                            $adresses .= get_sub_field('proprietaire_ville') ? get_sub_field('proprietaire_ville') : "";
                            $adresses .= '<br>';
                            $adresses .= get_sub_field('proprietaire_pays') ? get_sub_field('proprietaire_pays') : "";
                            $adresses .= '</p>';
                            $emails .= '<p class="ligne_simple">'.get_sub_field('proprietaire_email') ? get_sub_field('proprietaire_email') : "".'</p>'; 
                            $telephones .= '<p class="ligne_simple">'.get_sub_field('proprietaire_telephone') ? return_tel_french_format(get_sub_field('proprietaire_telephone')) : "".'</p>'; 
                            ?>  
                            <div class="bloc">                          
                                <p class="ligne_simple"><?php echo $nom; ?></p>
                                <div class="entete_bloc">demeurant 
                                    <p class="ligne_simple">
                                        <?php echo get_sub_field('proprietaire_adresse') ? get_sub_field('proprietaire_adresse') : ""; ?>
                                        <br>
                                        <?php echo get_sub_field('proprietaire_cp') ? get_sub_field('proprietaire_cp').' ' : ""; ?>
                                        <?php echo get_sub_field('proprietaire_ville') ? get_sub_field('proprietaire_ville') : ""; ?>
                                        <br>
                                        <?php echo get_sub_field('proprietaire_pays') ? get_sub_field('proprietaire_pays') : ""; ?>
                                    </p>
                                </div>
                                <table>
                                    <tr>
                                        <td class="left">E-mail : <p class="ligne_simple"><?php echo get_sub_field('proprietaire_email') ? get_sub_field('proprietaire_email') : ""; ?></p></td>
                                        <td>Téléphone* : <p class="ligne_simple"><?php echo get_sub_field('proprietaire_telephone') ? return_tel_french_format(get_sub_field('proprietaire_telephone')) : ""; ?></p></td>
                                    </tr>
                                </table>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?> 
                </div>                
                <div class="entete_bloc">
                    <p class="mention">*vous avez la possibilité de vous inscrire sur www.bloctel.gouv.fr pour vous opposer à tout démarchage téléphonique conformément à l’article L121-34 du Code de la consommation</p>
                </div>
            <?php else : ?>
                <div class="entete_bloc">
                    <p>Entre les soussignés :</p>
                </div>
                <div class="mandataire">
                    <p><span class="bold">Le Mandataire,</span> titulaire du mandat d'origine</p>
                    <p class="ligne_simple">SAS SEVEN & SEVEN DE LA COUR AU JARDIN</p>
                    <p class="ligne_simple">RCP VD7.000.001 / 20372</p>
                    <p class="ligne_simple">RCS Paris 811 493 162</p>
                    <p class="ligne_simple">Carte CPI 7501 2017 000 015964</p>
                    <p class="ligne_simple">157 rue du Faubourg Saint Antoine 75011 PARIS</p>
                    <table>
                        <tr>
                            <td class="left">E-mail : contact@delacouraujardin.com</td>
                            <td>Téléphone* : </td>
                        </tr>
                    </table>
                </div>
                <p class="bold">ET</p>
                <div class="delegataire">
                    <p><span class="bold">Le Délégataire,</span> bénéficiaire de la délégation : <br>
                    <?php echo get_field('mandat_delegation_nom_prenom_du_beneficiaire', $mandat_id) ? get_field('mandat_delegation_nom_prenom_du_beneficiaire', $mandat_id) : ''; ?>
                    </p>
                    <table>
                        <tr>
                            <td class="left">E-mail : <?php echo get_field('mandat_delegation_email_du_beneficiaire', $mandat_id) ? get_field('mandat_delegation_email_du_beneficiaire', $mandat_id) : ''; ?></td>
                            <td>Téléphone* : <?php echo get_field('mandat_delegation_tel_du_beneficiaire', $mandat_id) ? get_field('mandat_delegation_tel_du_beneficiaire', $mandat_id) : ''; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="entete_bloc">
                    <p class="mention">*vous avez la possibilité de vous inscrire sur www.bloctel.gouv.fr pour vous opposer à tout démarchage téléphonique conformément à l’article L121-34 du Code de la consommation</p>
                </div>
                <p>Tous deux régulièrement titulaire d’une carte professionnelle conformément à la loi n° 70/9 du 2 janvier 1970</p>
            <?php endif; ?>
        </div>        

        <?php 
        if($type_mandat['value']=='mandat_simple') :
            echo intro_mandat_simple_excusif(); 
            echo situation_designation('I', $mandat_id);
            echo prix('II', $mandat_id);
            echo honoraires('III', $mandat_id);
            echo conditions_particulieres('IV', $mandat_id);
            echo moyens_de_diffusion_des_annonces_commerciales('V', $mandat_id);
            echo supperficie_loi_carrez('VI', $mandat_id);
            echo dossier_diagnostique_technique('VII', $mandat_id);
            echo duree_du_mandat_et_obligations_du_mandant_simple('XIII', $mandat_id);
            echo pouvoir_du_mandataire('IX', $mandat_id);
            echo mandat_simple('X', $mandat_id);
            echo vente_sans_votre_concours('XI', $mandat_id);
            echo droit_de_retractation('XII', $mandat_id);
            echo non_detention_de_fonds('XIII', $mandat_id);
            echo mediation_des_litiges_de_la_consommation_simple('XIV', $mandat_id);
            echo information_et_libertes('XV', $mandat_id);
            echo signature_mandat_simple_exclusif($type_mandat['value']);
            echo formulaire_de_retractation($noms, $adresses);
            
        elseif($type_mandat['value']=='mandat_exclusif') :
            echo intro_mandat_simple_excusif(); 
            echo situation_designation('I', $mandat_id);
            echo prix('II', $mandat_id);
            echo honoraires('III', $mandat_id);
            echo conditions_particulieres('IV', $mandat_id);
            echo supperficie_loi_carrez('V', $mandat_id);
            echo dossier_diagnostique_technique('VI', $mandat_id);
            echo moyens_de_diffusion_des_annonces_commerciales('VII', $mandat_id);
            echo actions_particulieres('VIII', $mandat_id);
            echo modalite_et_periodicite_de_compte_rendu('IX', $mandat_id);
            echo plus_value_et_tva('X', $mandat_id);
            echo duree_du_mandat_et_obligations_du_mandant('XI', $mandat_id);
            echo exclusivite_et_obligation_du_mandant('XII', $mandat_id);
            echo declaration_du_mandant('XIII', $mandat_id);
            echo pouvoir_du_mandataire('XIV', $mandat_id);
            echo droit_de_retractation('XV', $mandat_id);
            echo non_detention_de_fonds('XVI', $mandat_id);
            echo mediation_des_litiges_de_la_consommation('XVII', $mandat_id);
            echo information_et_libertes('XVIII', $mandat_id);
            echo signature_mandat_simple_exclusif($type_mandat['value']);
            echo formulaire_de_retractation($noms, $adresses);

        elseif( $type_mandat['value']=='mandat_de_recherche' ) : 
            echo intro_mandat_recherche();            
            echo caracteristique_du_bien('I', $mandat_id);
            echo affaires_presentees_ou_visitees('II', $mandat_id);
            echo moyens_de_diffusion_des_annonces_commerciales('III', $mandat_id);
            echo actions_particulieres('IV', $mandat_id);
            echo modalite_et_periodicite_de_compte_rendu('V', $mandat_id);
            echo obligations_du_mandat_recherche('VI', $mandat_id);
            echo duree_du_mandat_recherche('VII', $mandat_id);
            echo information_du_mandant_recherche('VIII', $mandat_id);
            echo signature_mandat_recherche();

        elseif( $type_mandat['value']=='mandat_delegation' ) : 
            echo mandat_delegation($mandat_id);
        endif; ?>

    </div>
    <?php
}


// LES FONCTIONS ECHO DES BLOCS :
function intro_mandat_simple_excusif(){
    ?><p>agissant conjointement et solidairement en <span class="bold">QUALITÉ DE SEULS PROPRIÉTAIRES</span>, vous mandatons par la présente afin de rechercher un acquéreur et faire toutes les démarches en vue de vendre les biens et droits, ci-dessous désignés, nous engageant à produire toutes justifications de propriété.</p>
    <?php
}
function intro_mandat_recherche(){
    ?><p>Agissant en qualité d’acquéreurs éventuels, vous mandatons afin de nous présenter et de nous faire visiter tous biens répondants à nos critères de choix. Lorsque nous arrêterons notre choix, pour négocier les conditions générales de la vente, pour rédiger l’accord des parties, aux conditions suivantes :</p><?php
}

function situation_designation($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — SITUATION — DÉSIGNATION</h3> 
         <?php  $type_bien = get_field('bien_type', $mandat_id) ? ucfirst(get_field('bien_type', $mandat_id)) : "Bien";
        echo'<p>'.$type_bien.' sis à '.ucfirst(get_field('bien_adresse_ville', $mandat_id)).' : '.get_field("bien_adresse_1", $mandat_id).' '.get_field("bien_adresse_cp", $mandat_id).' '.ucfirst(get_field('bien_adresse_pays', $mandat_id)).'</p>'; ?>
        <p>Désignation : <?php echo get_field('designation_du_bien_mandat', $mandat_id) ? get_field('designation_du_bien_mandat', $mandat_id) : ""; ?></p>        
        <p>Dont nous sommes devenus propriétaires par acte chez Maître :
            <?php echo get_field('bien_acquis_par_acte_chez_maitre', $mandat_id) ? get_field('bien_acquis_par_acte_chez_maitre', $mandat_id) : ""; ?>
        </p>
        <p>Le bien est vendu :
            <?php echo get_field('bien_type_vente', $mandat_id)=='bien_vendu_libre_de_toute_occupation' ? 'libre de toute occupation' : ""; ?>
            <?php echo get_field('bien_type_vente', $mandat_id)=='bien_loue_selon_le_contrat_de_bail_et_les_conditions_ci-annexes' ? 'Loué selon le contrat de bail et les conditions ci-annexés' : ""; ?>
            <?php echo (get_field('bien_loue_date_du_bail', $mandat_id) && get_field('bien_type_vente', $mandat_id)=='bien_loue_selon_le_contrat_de_bail_et_les_conditions_ci-annexes') ? '<span>. Date du bail initial : '.get_field('bien_loue_date_du_bail', $mandat_id).'</span>' : ""; ?>
        </p>
    </div><?php 
}       

function prix($number, $mandat_id){ 
    //$prix_mandat = get_field('bien_prix_mandat', $mandat_id) ? get_field('bien_prix_mandat', $mandat_id) : ( get_field('field_597603552fc45', $mandat_id) ? get_field('field_597603552fc45', $mandat_id) : "" );
    $prix_mandat = get_field('bien_prix_mandat', $mandat_id) ? get_field('bien_prix_mandat', $mandat_id) : ( get_field('bien_prix_et_honoraires', $mandat_id) ? get_field('bien_prix_et_honoraires', $mandat_id) : "" );
    $prix_mandat = number_format($prix_mandat, 0, '', ' ');
    ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — PRIX</h3>
        <p>Les biens et droits, ci-devant désignés devront être présentés au prix de : <?php echo $prix_mandat.' €'; ?></p>
        <p>Payable comptant le jour de la signature de l’acte authentique, tant à l’aide de prêts que de fonds propres de l’acquéreur.</p>
    </div><?php 
}       

function honoraires($number, $mandat_id){ ?>
    <div class="bloc"> 
        <h3><?php echo $number; ?> — HONORAIRES</h3>
        <p class="ligne_simple">Vos honoraires (en Euros TTC) seront de :
            <?php echo get_field('bien_honoraires_montant', $mandat_id) ? number_format(get_field('bien_honoraires_montant', $mandat_id), 0, '', ' ').' €' : ""; ?>
        </p>
        <p class="ligne_simple">Ils seront à votre charge, sauf choix de l’option « honoraires charge acquéreur ».</p>
        <p class="ligne_simple">Ils seront exigibles le jour où l’opération sera effectivement conclue et constatée dans un acte écrit, signé des deux parties, conformément à l’article 74 du décret n° 72-678 du 20 juillet 1972.</p>           
        <p class="ligne_simple">
            <label>Option : « honoraires charge acquéreur »</label><input type="checkbox"<?php echo get_field('bien_honoraires_charge_de', $mandat_id)=='charge_acquereur' ? ' checked="checked"' : ""; ?> />
        </p>
    </div><?php 
}       

function conditions_particulieres($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — CONDITIONS PARTICULIÈRES</h3>
        <?php echo get_field('bien_notes_conditions_particulieres', $mandat_id) ? '<p>'.get_field('bien_notes_conditions_particulieres', $mandat_id).'</p>' : '<div class="espace_blanc"></div>'; ?>  
    </div><?php 
}            

function moyens_de_diffusion_des_annonces_commerciales($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — MOYENS DE DIFFUSION DES ANNONCES COMMERCIALES</h3>
        <?php echo get_field('bien_notes_moyen_diffusion_annonces_commerciales', $mandat_id) ? '<p>'.get_field('bien_notes_moyen_diffusion_annonces_commerciales', $mandat_id).'</p>' : '<div class="espace_blanc"></div>'; ?>        
    </div><?php 
}

function supperficie_loi_carrez($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — SUPERFICIE PRIVATIVE « LOI CARREZ » (SI COPROPRIÉTÉ), EN MÈTRES CARRÉS, M2</h3>
            <?php echo get_field('bien_superficie_carrez', $mandat_id) ? '<p>'.get_field('bien_superficie_carrez', $mandat_id).' m²</p>' : '<div class="espace_blanc"></div>'; ?>      
    </div><?php 
}

function dossier_diagnostique_technique($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — DOSSIER DE DIAGNOSTIC TECHNIQUE</h3>
            <p>Date des diagnostics :            
                <?php echo get_field('bien_date_diagnostics', $mandat_id) ? get_field('bien_date_diagnostics', $mandat_id) : ""; ?>
            </p>
            <?php if( get_field('bien_diagnostics_dossier_complet', $mandat_id) ) { ?>
                <p>Dossier complet</p>
            <?php } else { ?>
                <table>
                    <tr>
                        <td class="left">                     
                            <p class="ligne_simple">
                                <label>Dossier complet</label><input type="checkbox"<?php echo get_field('bien_diagnostics_dossier_complet', $mandat_id) ? ' checked="checked"' : ""; ?> />                    
                            </p>
                            <p class="ligne_simple">
                                <label>État amiante</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_etat_amiante', $mandat_id)) ? ' checked="checked"' : ""; ?> />                    
                            </p>
                            <p class="ligne_simple">
                                <label>Constat de risque d’exposition au plomb</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_rique_au_plomb', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>État parasitaire</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_etat_parasitaire', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>Constat des risques miniers, naturels et technologiques</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_risques_miniers', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                        </td>
                        <td>  
                            <p class="ligne_simple">
                                <label>Diagnostic de performance énergétique</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_performance_energetique', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>État de l’installation intérieure d’électricité</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_installation_elec_interieure', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>État de l’installation intérieure de gaz</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_installation_gaz_interieure', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>Contrôle assainissement non collectif</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_controle_assainissement_non_collectif', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>Bornage</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_bornage', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>                      
                        </td>
                    </tr>
                </table>
            <?php }; ?>
            <?php echo get_field('bien_consommation_non_soumis_dpe', $mandat_id) ? '<p>Bien non soumis aux DPE.</p>' : ""; ?>
    </div><?php 
}

function actions_particulieres($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — ACTIONS PARTICULIÈRES</h3>
            <?php echo get_field('bien_notes_actions_particulieres', $mandat_id) ? '<p>'.get_field('bien_notes_actions_particulieres', $mandat_id).'</p>' : '<div class="espace_blanc"></div>'; ?> 
    </div><?php 
}

function modalite_et_periodicite_de_compte_rendu($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — MODALITÉS ET PÉRIODICITÉ DE COMPTES-RENDUS</h3>
            <?php echo get_field('bien_notes_modalite_cr', $mandat_id) ? '<p>'.get_field('bien_notes_modalite_cr', $mandat_id).'</p>' : '<div class="espace_blanc"></div>'; ?>  
    </div><?php 
}

function plus_value_et_tva($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — PLUS-VALUE ET T.V.A.</h3>
            <p class="small">Les parties reconnaissent avoir été informées des dispositions fiscales<br>
            concernant les plus-values et déclarent agir en toute connaissance<br>
            de cause. Si la vente est en T.V.A. le prix ci-dessus stipulé<br>
            s’entend T.V.A. incluse.</p>
    </div><?php 
}

function duree_du_mandat_et_obligations_du_mandant($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — DURÉE DU MANDAT ET OBLIGATIONS DU MANDANT</h3>
            <table>
                <tr>
                    <td class="left">
                        <p class="ligne_simple small">CE MANDAT VOUS EST CONSENTI POUR UNE DURÉE DE VINGT QUATRE MOIS PRENANT EFFET CE JOUR, DONT LES TROIS PREMIERS MOIS SONT IRRÉVOCABLES. AU TERME DE CETTE PÉRIODE D’IRRÉVOCABILITÉ, LE MANDAT POURRA ÊTRE DÉNONCÉ</p>
                    </td>
                    <td> 
                        <p class="ligne_simple small">À TOUT MOMENT PAR CHACUNE DES PARTIES, À CHARGE POUR CELLE QUI ENTEND Y METTRE FIN D’EN AVISER L’AUTRE PARTIE QUINZE JOURS À L’AVANCE PAR LETTRE RECOMMANDÉS AVEC AVIS DE RÉCEPTION.</p>
                    </td>
                </tr>
            </table>
    </div><?php 
}

function duree_du_mandat_et_obligations_du_mandant_simple($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — DURÉE DU MANDAT ET OBLIGATIONS DU MANDANT</h3>
            <table>
                <tr>
                    <td class="left">
                        <p>LE PRÉSENT MANDAT VOUS EST CONSENTI POUR UNE DURÉE DE 24 MOIS.<br>
                        IL NE POURRA ÊTRE DÉNONCÉ DURANT LES 3 PREMIERS MOIS ; ENSUITE IL POURRA ÊTRE DÉNONCÉ À TOUT MOMENT, AVEC UN PRÉAVIS DE 15 JOURS PAR LETTRE RECOMMANDÉE AVEC ACCUSÉ DE RÉCEPTION. EN CONSÉQUENCE :<br>
                        - pendant la durée du mandat, nous nous engageons à ratifier la vente à tout preneur que vous nous présenterez en acceptant les prix et les conditions des présentes, et à libérer les lieux pour le jour de l’acte authentique.<br>
                        - Si nous présentons les biens à vendre directement ou par l’intermédiaire d’un autre mandataire, nous le ferons au prix des présentes, de façon à ne pas vous gêner dans votre mission.<br>
                        - Nous nous interdisons de vendre sans votre concours, y compris par un autre intermédiaire, à un acquéreur qui nous aurait été présenté par vous, pendant la durée du mandat et deux ans après son expiration.</p>
                        <p>EN TOUTE CONFORMITE AVEC LE CODE CIVIL ET LES PRESCRIPTIONS D’ORDRE PUBLIC DE L’ARTICLE 78 DU</p>
                    </td>
                    <td> 
                        <p>DECRET N° 72-678 DU 20/07/1972, VOTRE REMUNERATION SERA DUE EN CAS DE VENTE A UN ACQUEREUR AYANT EU CONNAISSANCE DE LA VENTE DU BIEN PAR VOTRE INTERMEDIAIRE, MEME SI L’OPERATION EST CONCLUE SANS VOS SOINS.</p>
                        <p>EN CAS DE VENTE, PENDANT LA DURÉE DU PRÉSENT MANDAT ET 2 ANS APRÈS SON EXPIRATION, NOUS DEVRONS OBTENIR DE NOTRE ACQUÉREUR L’ASSURANCE ÉCRITE QUE LES BIENS NE LUI ONT PAS ÉTÉ PRÉSENTÉS PAR VOUS.</p>
                        <p>SI NOUS VENDONS APRÈS L’EXPIRATION DE CE MANDAT, COMME NOUS EN GARDONS LE DROIT, À TOUTE AUTRE PERSONNE NON PRÉSENTÉE PAR VOUS, NOUS NOUS OBLIGEONS À VOUS AVERTIR IMMÉDIATEMENT PAR LETTRE RECOMMANDÉE, EN VOUS PRÉCISANT LES COORDONNÉES DES ACQUÉREURS, DU NOTAIRE CHARGÉ D’AUTHENTIFIER LA VENTE, ET DE L’AGENCE ÉVENTUELLEMENT INTERVENUE, AINSI QUE LE PRIX DE VENTE FINAL, CE PENDANT DEUX ANS.</p>
                    </td>
                </tr>
            </table>
    </div><?php 
}

function exclusivite_et_obligation_du_mandant($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — EXCLUSIVITÉ ET OBLIGATIONS DU MANDANT</h3>
            <table>
                <tr>
                    <td class="left">
                        <p class="ligne_simple small">LE PRÉSENT MANDAT VOUS EST CONSENTI EN EXCLUSIVITÉ POUR TOUTE LA DURÉE DU MANDAT. EN CONSÉQUENCE, NOUS NOUS INTERDISONS, PENDANT LE COURS DU PRÉSENT MANDAT, DE NÉGOCIER DIRECTEMENT OU INDIRECTEMENT LA VENTE DES BIENS, CI-AVANT DÉSIGNÉS, Y COMPRIS PAR UN AUTRE INTERMÉDIAIRE OU PAR UN OFFICE NOTARIAL, ET NOUS NOUS ENGAGEONS À DIRIGER VERS VOUS TOUTES LES DEMANDES QUI NOUS SERAIENT ADRESSÉES PERSONNELLEMENT.</p>
                        <p class="vert bold ligne_simple small">ART. 78 DU DÉCRET DU 20 JUILLET 72</p><br>
                    </td>
                    <td> 
                        <p class="ligne_simple small">PASSÉ UN DÉLAI DE TROIS MOIS À COMPTER DE SA SIGNATURE, LE MANDAT CONTENANT UN TELLE CLAUSE PEUT ÊTRE DÉNONCÉ À TOUT MOMENT PAR CHACUNE DES PARTIES, À CHARGE POUR CELLE QUI ENTEND Y METTRE FIN D’EN AVISER L’AUTRE PARTIE QUINZE JOURS AU MOINS À L’AVANCE PAR LETTRE RECOMMANDÉE AVEC DEMANDE D’AVIS DE RÉCEPTION.</p>                      
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td class="left">
                        <p class="bold ligne_simple small">MENTION EXPRESSE : EN TOUTE CONFORMITÉ AVEC LE CODE CIVIL ET LES PRESCRIPTIONS D’ORDRE PUBLIC DE L’ARTICLE 78 DU DÉCRET N° 72-678 DU 20 JUILLET 1972, LA RÉMUNÉRATION DU MANDATAIRE SERA DUE MÊME SI L’OPÉRATION EST CONCLUE SANS LES SOINS DU MANDATAIRE.</p>
                        <p class="ligne_simple small">- Pendant la durée du mandat, nous nous engageons à ratifier la vente à tout preneur que vous nous présenterez, acceptant les prix et conditions des présentes, et à libérer les lieux pour le jour de l’acte authentique.</p>
                        <p class="ligne_simple small">- Pendant une période de deux ans après expiration du mandat, nous nous interdisons de vendre sans votre concours, y compris par un autre intermédiaire, à un acquéreur à qui auriez présenté le bien pendant la période de validité du mandat.</p>
                        <p class="ligne_simple small">- Le mandant s’engage à faire réaliser et à fournir sans délai au mandataire l’ensemble des diagnostics obligatoires.</p><br>
                    </td>
                    <td> 
                        <p class="ligne_simple small">- Pendant une période de deux ans après expiration du mandat, nous nous interdisons de vendre sans votre concours, y compris par un autre intermédiaire, à un acquéreur qui nous aurait été présenté par vous pendant la période de validité du mandat.</p>
                        <p class="ligne_simple small">En cas de vente, pendant deux ans après l’expiration du présent mandat, nous devrons obtenir de notre acquéreur l’assurance écrite que les biens ne lui ont pas été présentés par vous.</p>
                        <p class="ligne_simple small">Si nous vendons après l’expiration de ce mandat, comme nous en gardons le droit, à toute personne non présentée par vous, nous nous obliegeons à vous avertir immédiatement par lettre recommandée, en vous précisant les coordonnées des acquéreurs, du notaire chargé d’authentifier la vente, et de l’agence éventuellement intervenue, ainsi que le prix de vente final, ce pendant deux ans.</p>                       
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td class="left">
                        <p class="vert bold ligne_simple small">CLAUSES PÉNALES : EN CAS DE NON RESPECT DE LA CLAUSE CI DESSUS, LE MANDANT VERSERA UNE INDEMNITÉ COMPENSATRICE FORFAITAIRE CORRESPONDANT À LA MOITIÉ DES HONORAIRES CONVENUS. PAR AILLEURS, EN CAS DE VENTE À UN ACQUÉREUR AYANT EU CONNAISSANCE DE LA VENTE DU BIEN PAR L’INTERMÉDIAIRE DE L’AGENCE, OU DE REFUS DE VENDRE À UN ACQUÉREUR QUI AURAIT ÉTÉ PRÉSENTÉ PAR L’AGENCE, OU EN CAS D’INFRACTION À UNE CLAUSE D’EXCLUSIVITÉ, LE MANDAT VERSERA UNE INDEMNITÉ COMPENSATRICE FORFAITAIRE ÉGALE</p>
                    </td>
                    <td>   
                        <p class="vert bold ligne_simple small">AUX HONORAIRES PRÉVUS AU PRÉSENT MANDAT. EN CAS DE PRÉSENTATION DU BIEN A VENDRE A UN PRIX DIFFÉRENT, EN CONTRADICTION AVEC LE PARAGRAPHE XII, ET SI CETTE PRÉSENTATION EST FAITE À UN PRIX INFÉRIEUR À CELUI QUI EST PRÉVU AU PRÉSENT MANDAT, LE MANDANT VERSERA UNE INDEMNITÉ COMPENSATRICE FORFAITAIRE CORRESPONDANT À LA MOITIÉ DES HONORAIRES CONVENUS, LE MANDATAIRE SUBISSANT UN PRÉJUDICE PAR LA PERTE D’UNE CHANCE DE VENDRE LE BIEN.</p>                        
                    </td>
                </tr>
            </table>
    </div><?php 
}

function declaration_du_mandant($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — DÉCLARATION DU MANDANT</h3>
            <table>
                <tr>
                    <td class="left">
                        <p class="ligne_simple small">En considération du mandat présentement accordé, le mandant :</p>
                        <p class="ligne_simple small">- Déclare avoir la capacité pleine et entière de disposer des biens objets du présent mandat. Il déclare en outre et sous son entière responsabilité, ne faire l’objet d’aucune mesure de protection de la personne (curatelle, tutelle...) ni d’aucune procédure collective (redressement ou liquidation judiciaire).</p>
                        <p class="ligne_simple small">- Déclare que les biens, objets du présent mandat ne font l’objet d’aucune procédure de saisie immobilière.</p>
                    </td>
                    <td> 
                        <p class="ligne_simple small">- Déclare ne pas avoir consenti, par ailleurs, de mandat de vente non expiré ou dénoncé.</p>
                        <p class="ligne_simple small">- S’interdit de signer tout autre mandat ultérieurement via un autre intermédiaire sans avoir préalablement dénoncé le présent mandat.</p>                       
                    </td>
                </tr>
            </table>
    </div><?php 
}

function pouvoir_du_mandataire($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — POUVOIRS DU MANDATAIRE</h3> 
            <table>
                <tr>
                    <td class="left">
                        <p class="small">En considération du mandat présentement accordé, tous pouvoirs vous sont donnés pour mener à bien votre mission. Vous pourrez notamment :</p>
                        <p class="small ligne_simple">1) Faire tout ce qui vous sera utile pour parvenir à la vente, et notamment toute publicité sur tous supports à votre convenance, y compris sur fichiers informatiques librement accessibles (internet,...) mais à vos frais seulement ; apposer un panneau de mise en vente à l’endroit que vous jugerez le plus approprié ; publier toute photographie, étant entendu que nous sommes seuls propriétaires du droit à l’image de notre bien.<br>Le mandant pourra exercer son droit d’accès et de rectification conformément à l’article 27 de la loi du 6 janvier 1978. Le bien ne pourra faire l’objet d’une campagne publicitaire publique qu’à compter de la transmission au mandataire du DPE.</p><br>         
                    </td>
                    <td>  
                        <p class="small">7) Application de l’article 46 de la loi n°65-557 du 10 juillet 1965 et du décret n°97-532 du 23 mai 1997 (vente d’un lot ou d’une fraction de lot, dite loi Carrez) : si nous ne vous fournissons pas ce document sous huitaine, nous vous autorisons à faire établir à nos frais, par un homme de l’art, une attestation mentionnant la superficie exacte de la partie privative des biens objet du présent mandat.</p>
                        <p class="small">8) Dossier diagnostic technique : le vendeur fera effectuer sans délai l’ensemble des constats, états et diagnostics obligatoires. Ce dossier devra être annexé à l’engagement des parties.</p>
                        <p class="small">9) Vous adjoindre ou substituer tout professionnel de votre choix pour l’accomplissement des présentes.</p>
                    </td>
                </tr>
                <tr>
                    <td class="left">
                        <p class="small ligne_simple">2) Réclamer toutes les pièces utiles auprès de toutes personnes privées ou publiques, notamment le certificat d’urbanisme.</p>
                        <p class="small">3) Indiquer, présenter et faire visiter les biens à vendre à toutes personnes que vous jugerez utile. A cet effet, nous nous obligeons à vous assurer le moyen de visiter pendant le cours du présent mandat.</p>
                        <p class="small">4) Établir en notre nom tous actes sous seing privé (compromis en particulier), éventuellement assortis d’une demande de prêt, aux clauses et conditions nécessaires à l’accomplissement des présentes et recueillir la signature de l’acquéreur.</p>   
                        <p class="small">5) Satisfaire, s’il y a lieu, à la déclaration d’intention d’aliéner, exigée par la loi. En cas d’exercice du droit de préemption, négocier avec l’organisme préempteur, bénéficiaire de ce droit à la condition de nous en avertir, étant entendu que nous gardons le droit d’accepter ou refuser le prix proposé par le préempteur, si ce prix est inférieur au prix demandé.</p>
                        <p class="small">6) SEQUESTRE : en vue de garantir la bonne exécution des présentes et de leur suite, les fonds ou valeurs qu’il est d’usage de faire verser par l’acquéreur seront détenus par tout séquestre habilité à cet effet (notaire ou agence titulaire d’une garantie financière).</p>                    
                    </td>
                    <td>  
                        <p class="small ligne_simple">10) Copropriété : le mandant autorise expressément le mandataire à demander au syndic, en son nom et à ses frais, communication et copie des documents devant être présentés ou fournis à l’acquéreur, notamment le carnet d’entretien de l’immeuble, le diagnostic technique, les diagnostics amiante, plomb et termites concernant les parties communes et l’état daté prévu par l’article 5 du décret modifié du 17 mars 1967. Cette autorisation ne concerne que les documents que le vendeur copropriétaire n’aurait pas déjà fournis au mandataire. Les documents ainsi obtenus sont réputés la propriété du mandant et lui seront restitués en fin de mission.</p>
                        <p class="small">11) Le mandataire informera le mandant, par LRAR ou par tout autre écrit remis contre récépissé ou émargement, dans les huit jours de l’opération, de l’accomplissement du mandat, en joignant le cas échéant une copie de la quittance ou du reçu délivré ; ce, conformément à l’art. 77 du décret n° 72-678 du 20 juillet 1972.</p>
                    </td>
                </tr>
            </table>
    </div><?php 
}

function droit_de_retractation($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — DROIT DE RÉTRACTATION</h3>
            <table>
                <tr>
                    <td class="left">
                        <p class="ligne_simple small">Le consommateur (Propriétaire mandant) dispose d’un délai de rétractation de quatorze jours pour exercer son droit de rétractation d’un contrat conclu à distance, à la suite d’un démarchage téléphonique ou hors établissement, sans avoir à motiver sa décision.<br>
                        Toute clause par laquelle le consommateur abandonne son droit de rétractation est nulle.<br>
                        Le délai court à compter du jour de la conclusion du contrat.</p>
                        <p class="ligne_simple small">Pour exercer le droit de rétractation, vous devez nous notifier votre décision de rétractation du présent contrat au moyen d’une déclaration dénuée d’ambiguïté (par exemple, lettre par la poste, ou par courrier électronique). Vous pouvez utiliser le modèle de formulaire de rétractation mais ce n’est aucunement obligatoire.</p>
                    </td>
                    <td> 
                        <p class="ligne_simple small">Pour que le délai de rétractation soit respecté, il suffit que vous transmettiez votre communication relative à l’exercice du droit de rétractation avant l’expiration du délai contractuel.</p>
                        <p class="small bold">SI LE MANDANT A DEMANDE A CE QUE LE MANDATAIRE COMMENCE SES PRESTATIONS AVANT LE DELAI DE RETRACTATION, IL POURRA NEANMOINS SE RETRACTER PENDANT CE DELAI CONTRACTUEL, SAUF SI LE MANDATAIRE A PLEINEMENT EXECUTE SA MISSION.</p>                       
                    </td>
                </tr>
            </table>
    </div><?php 
}

function non_detention_de_fonds($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — NON DÉTENTION DE FONDS</h3> 
            <table>
                <tr>
                    <td class="left">
                        <p class="ligne_simple small">Concerne uniquement les agences ayant une carte professionnelle portant la mention « non détention de fonds » : « L’AGENCE, TITULAIRE D’UNE CARTE PROFESSIONNELLE PORTANT LA MENTION « NON DÉTENTION DE FONDS »</p>
                    </td>
                    <td> 
                        <p class="ligne_simple small">POUR SON ACTIVITÉ DE TRANSACTIONS SUR IMMEUBLES ET FONDS DE COMMERCES, NE PEUT RECEVOIR NI DÉTENIR AUCUN FONDS, EFFET OU VALEUR ».</p>                      
                    </td>
                </tr>
            </table>  
    </div><?php 
}

function mediation_des_litiges_de_la_consommation($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — MÉDIATION DES LITIGES DE LA CONSOMMATION</h3>
            <table>
                <tr>
                    <td class="left">
                        <p class="ligne_simple small">Tout consommateur a le droit de recourir gratuitement à un médiateur de la consommation en vue de la résolution amiable du litige qui l’oppose à un professionnel.</p>
                    </td>
                    <td>
                        <p class="ligne_simple small">A cet effet, le professionnel garantit au consommateur le recours effectif à un dispositif de médiation de la consommation, en vertu des Articles L611-1 et suivants du Code de la consommation, créés par l’ordonnance n°2016-301 du 14 mars 2016. Vous pouvez trouver les coordonnées du médiateur sectoriel sur <a href="http://www.economi.gouv.fr/mediation-conso">http://www.economi.gouv.fr/mediation-conso</a>.</p>                        
                    </td>
                </tr>
            </table>
    </div><?php 
}

function mediation_des_litiges_de_la_consommation_simple($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — MÉDIATION DES LITIGES DE LA CONSOMMATION</h3>
            <!-- <table>
                <tr>
                    <td class="left">
                        <p class="ligne_simple small">Tout consommateur a le droit de recourir gratuitement à un médiateur de la consommation en vue de la résolution amiable du litige qui l’oppose à un professionnel. A cet effet, le professionnel garantit au consommateur le recours effectif à un dispositif de médiation de la consommation, en vertu des Articles L611-1 et suivants du Code de la consommation, créés par l’ordonnance n°2016-301 du 14 mars 2016. Vous pouvez trouver les coordonnées du médiateur sectoriel sur http://www.economi.gouv.fr/mediation-conso.</p>
                    </td>
                    <td>
                        <p class="ligne_simple small bold">Votre médiateur sectoriel :</p>                           
                    </td>
                </tr>
            </table>  -->
            <table>
                <tr>
                    <td class="left">
                        <p class="ligne_simple small">Les informations recueillies par le mandataire en considération du présent mandat font l’objet d’un traitement informatique, nécessaire à l’exécution de sa mission. Les informations concernant le bien objet du présent contrat sont susceptibles d’être transmises à des partenaires commerciaux, sites internet notamment. Conformément à la loi du 06/01/78,</p>
                    </td>
                    <td>
                        <p class="ligne_simple small">le mandant bénéficie d’un droit d’accès, de rectification et de suppression des informations qui le concernent. Ces informations sont accessibles, rectifiables, ou peuvent être supprimées sur demande auprès de l’agence, dont les coordonnées figurent sur le présent mandat.</p>                           
                    </td>
                </tr>
            </table>
    </div><?php 
}

function information_et_libertes($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — INFORMATIQUE ET LIBERTÉS</h3>
            <table>
                <tr>
                    <td class="left">
                        <p class="ligne_simple small">Les informations recueillies par le mandataire en considération du présent mandat font l’objet d’un traitement informatique, nécessaire à l’exécution de sa mission. Les informations concernant le bien objet du présent contrat sont susceptibles d’être transmises à des partenaires commerciaux, sites internet notamment.</p>
                    </td>
                    <td>
                        <p class="ligne_simple small">Conformément à la loi du 06/01/78, le mandant bénéficie d’un droit d’accès, de rectification et de suppression des informations qui le concernent. Ces informations sont accessibles, rectifiables, ou peuvent être supprimées sur demande auprès de l’agence, dont les coordonnées figurent sur le présent mandat.</p>                           
                    </td>
                </tr>
            </table>
    </div><?php 
}

function mandat_simple($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — MANDAT SIMPLE</h3>
            <p class="small">Le présent mandat vous est consenti sans exclusivité, conformément au §VII ci-dessus. En conséquence, nous gardons toute liberté de vendre par nous-mêmes ou par l’intermédiaire d’une autre agence, sauf à un acquéreur qui nous aurait été présenté par vous.</p>
    </div><?php 
}

function vente_sans_votre_concours($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — VENTE SANS VOTRE CONCOURS</h3>
            <p class="small">Dans les cas autorisés aux présentes de vente sans votre concours, nous nous engageons à vous informer immédiatement par lettre recommandée avec accusé de réception, en vous précisant les noms et adresses de l’acquéreur, du notaire chargé de l’acte authentique et de l’agence éventuellement intervenue, ainsi que le prix de vente final, ce, pendant la durée du présent mandat et deux ans après son expiration.</p>
            <p class="bold ligne_simple small">CLAUSES PÉNALES :</p>
            <p class="ligne_simple small">EN CAS DE NON RESPECT DE LA CLAUSE CI-DESSIS, NOUS VOUS VERSERONS UNE INDEMNITÉ COMPENSATRICE FORFAITAIRE CORRESPONDANT À LA MOITIE DES HONORAIRES CONVENUS.</p>
            <p class="small">PAR AILLEURS, EN CAS DE VENTE A UN ACQUÉREUR AYANT EU CONNAISSANCE DE LA VENTE DU BIEN PAR VOTRE INTERMÉDIAIRE, OU DE REFUS DE VENDRE À UN ACQUÉREUR QUI NOUS AURAIT ÉTÉ PRÉSENTÉ PAR VOUS, NOUS VOUS VERSERONS UNE INDEMNITÉ COMPENSATRICE FORFAITAIRE ÉGALE AUX HONORAIRES PRÉVUS AU PRÉSENT MANDAT.</p> 
            <p class="small">EN CAS DE PRÉSENTATION DU BIEN À VENDRE À UN PRIX DIFFÉRENT, EN CONTRADICTION AVEC LE PARAGRAPHE XI, ET SI CETTE PRÉSENTATION EST FAITE À UN PRIX INFÉRIEUR À CELUI QUI EST PRÉVU AU PRÉSENT MANDAT, LE MANDANT VERSERA UNE INDEMNITÉ COMPENSATRICE FORFAITAIRE CORRESPONDANT À LA MOITIÉ DES HONORAIRES CONVENUS, LE MANDATAIRE SUBISSANT UN PRÉJUDICE PAR LA PERTE D’UNE CHANCE DE VENDRE LE BIEN.</p>
    </div><?php 
}

function caracteristique_du_bien($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — CARACTERISTIQUES DU BIEN RECHERCHE</h3>
            <?php echo get_field('mandat_recherche_caracteristique_du_bien', $mandat_id) ? '<p>'.get_field('mandat_recherche_caracteristique_du_bien', $mandat_id).'</p>' : '<div class="espace_blanc"></div>'; ?> 
            <p>Prix Maximum souhaité : <?php echo get_field('mandat_recherche_prix_maximum_souhaite', $mandat_id) ? get_field('mandat_recherche_prix_maximum_souhaite', $mandat_id) : ''; ?></p>
            <p>En cas d’achat d’un bien présenté par l’agence vos honoraires seront de : <?php echo get_field('mandat_recherche_montant_des_honoraires', $mandat_id) ? get_field('mandat_recherche_montant_des_honoraires', $mandat_id) : ''; ?></p>
            <p>Du montant fixé par l’agence ou 50/50 en inter-cabinet</p>
            <p>Ils ne deviendront éligibles qu’après achat effectivement conclu, levée étant obligatoirement faite de toutes conditions suspensives, et seront à notre charge.</p>
    </div><?php 
}

function affaires_presentees_ou_visitees($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — AFFAIRES PRESENTEES CE JOUR OU VISITEES</h3>
            <?php echo get_field('bien_affaires_presentees_ce_jour_ou_visitees', $mandat_id) ? '<p>'.get_field('bien_affaires_presentees_ce_jour_ou_visitees', $mandat_id).'</p>' : '<div class="espace_blanc"></div>'; ?> 
    </div><?php 
}

function obligations_du_mandat_recherche($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — OBLIGATIONS</h3>
            <p>CONCERNANT LES AFFAIRES PRESENTEES CE JOUR OU VISITEES EN TOUTE CONFORMITE AVEC LE CODE CIVIL ET LES PRESCRIPTIONS D’ORDRE PUBLIC DE L’ARTICLE 78 DU DECRET N° 72-678 DU 20/07/1972, LA REMUNERATION DU MANDATAIRE SERA DUE MEME SI L’OPERATION EST CONCLUE SANS LES SOINS DU MANDATAIRE DIRECTEMENT OU PAR UN AUTRE INTERMEDIAIRE, PENDANT LA DUREE DU MANDAT ET PENDANT 24 MOIS APRES LA FIN DU MANDAT.</p>
    </div><?php 
}

function duree_du_mandat_recherche($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — DUREE</h3>
            <p>ART.78 DU DECRET DU 20 JUILLET 1972<br>PASSE UN DELAI DE TROIS MOIS A COMPTER DE SA SIGNATURE, LE MANDAT CONTENANT UNE TELLE CLAUSE PEUT ETRE DENONCE A TOUT MOMENT PAR CHACUNE DES PARTIES, A CHARGE POUR CELLE QUI ENTEND Y METTRE FIN D’EN AVISER L’AUTRE PARTIE QUINZE JOURS AU MOINS A L’AVANCE PAR LETTRE RECOMMANDEE AVEC AVIS DE RECEPTION.<br/>NOUS RECONNAISSONS QUE LES AFFAIRES PROPOSEES ET IDENTIFIEES CI-DESSSUS SONT STRICTEMENT CONFIDENTIELLES ET NOUS NOUS ENGAGEONS A N’EN TRAITER L’ACHAT EVENTUEL QUE PAR VOTRE SEUL INTERMEDIAIRE PENDANT 24 MOIS A COMPTER DE CE JOUR.<br>A DEFAUT DE RECEPTER CETTE CLAUSE, VOUS AURIEZ DROIT A UNE INDEMNITE FORFAITAIRE A TITRE DE CLAUSE PENALE ,  A NOTRE CHARGE, D’UN MONTANT EGAL A VOTRE REMUNERATION PREVUE AUX PRESENTES.</p>
    </div><?php 
}

function information_du_mandant_recherche($number, $mandat_id){ ?>
    <div class="bloc">
        <h3><?php echo $number; ?> — INFORMATION DU MANDANT</h3>
            <p>Le mandataire informera le mandant par LRAR ou par tout autre écrit remis contre récépissé ou émargement, dans les 8 jours de l’opération, de l ‘accomplissement du mandat en joignant le cas échéant une copie de la quittance ou du reçu délivré, ce conformément à l’article 77 du décret N° 72 678 du 20 Juillet 1972.</p>
    </div><?php 
}

function mandat_delegation($mandat_id){ ?>
    <div class="bloc">
        <p class="bold">Il est convenu ce qui suit :</p>
        <p>Le Mandataire est titulaire d’un mandat enregistré dans son registre sous le N°<?php echo get_field('mandat_delegation_mandat_delegue', $mandat_id) ? get_field('mandat_delegation_mandat_delegue', $mandat_id) : ''; ?></p>
        <p>Et concernant :<br/>
            <?php echo get_field('mandat_delegation_mandat_delegue_detail', $mandat_id) ? get_field('mandat_delegation_mandat_delegue_detail', $mandat_id) : ''; ?>
        </p>   
        <p>Ce mandat, dont copie est annexée aux présentes avec toutes ses annexes, autorise le Mandataire à s’adjoindre ou se substituer à tout professionnel de son choix en vue de son accomplissement.</p>
        <p>En conséquence, le Mandataire délègue au Délégataire, qui accepte, ses droits et obligations résultant de ce mandat, tout en restant seul responsable vis à vis du Mandant.<br/>
        En cas de réalisation de l’objet du mandat par le délégataire, ses honoraires seront de : <?php echo get_field('mandat_delegation_honoraires', $mandat_id) ? get_field('mandat_delegation_honoraires', $mandat_id) : ''; ?>% des honoraires perçus</p>
        <p>à la charge du Mandataire, dès que celui-ci aura perçu ses propres honoraires.</p>
        <p>Si le Mandataire réalise lui même l’objet du mandat, ce qu’il lui est toujours possible de faire, il s’engage à en tenir informé immédiatement le Délégataire, mettant ainsi fin aux présentes sans indemnité de part ni d’autre.</p>
    </div>
    <div class="bloc">
        <h3>CONDITIONS PARTICULIÈRES</h3>
        <?php echo get_field('bien_notes_conditions_particulieres', $mandat_id) ? '<p>'.get_field('bien_notes_conditions_particulieres', $mandat_id).'</p>' : '<div class="espace_blanc"></div>'; ?>  
    </div>    
    <?php echo signature_mandat_recherche();
}

function signature_mandat_recherche(){
    ?><div class="signature">        
        <!-- <p>Fait au siège de l’agence, le &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; en deux exemplaires originaux dont un remis dès à présent au mandant qui le reconnaît.</p> -->
        <table>
            <tr>
                <td class="left">
                    <p>Fait à :</p>
                </td>
                <td>  
                    <p>Date :</p>                                   
                </td>
            </tr>
        </table>
        <br>
        <p>En deux exemplaires originaux dont un remis dès à présent au mandant qui le reconnaît.</p> 
        <p>Le mandant reconnaît expressément avoir pris connaissance, préalablement à la signature des présentes, de l’intégralité des caractéristiques des services définis au présent mandat, conformément aux articles L111-1 et suivants du Code de la consommation.</p>
        <table>
            <tr>
                <td class="signature_infos">
                <p class="bold">Signer et parapher l'ensemble des documents édités ce jour ayant seule valeur d’originaux.<p>
                    <p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mots</p>
                    <p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; lignes</p>
                    <p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; chiffres</p>
                    <p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; rayés comme nuls</p>
                </td>
                <td class="signature_cadre">
                    <p><span class="bold">Le Mandant :</span><br>
                    Signature</p>
                </td>
                <td class="separateur"></td>
                <td class="signature_cadre">
                    <p><span class="bold">Le mandataire :</span><br>
                Signature</p>                      
                </td>
            </tr>
        </table>        
    </div><?php 
}

function signature_mandat_simple_exclusif($type_mandat_val){
    ?><div class="signature">
        <?php 
            if($type_mandat_val=='mandat_simple')                     
                echo mentions_avant_signatures_mandat_simple();
            elseif($type_mandat_val=='mandat_exclusif') 
                echo mentions_avant_signatures_mandat_exclusif();
        ?>                 
        <br><br>
        <table>
            <tr>
                <td class="signature_infos">
                <p class="bold">Signer et parapher l'ensemble des documents édités ce jour ayant seule valeur d’originaux.<p>
                    <p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mots</p>
                    <p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; lignes</p>
                    <p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; chiffres</p>
                    <p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; rayés comme nuls</p>
                </td>
                <td class="signature_cadre">
                    <p><span class="bold">Le Propriétaire :</span><br>
                    Signature précédée de la mention manuscrite<br>« Lu et approuvé, bon pour mandat »</p>
                </td>
                <td class="separateur"></td>
                <td class="signature_cadre">
                    <p><span class="bold">L'Agence :</span><br>
                Signature précédée de la mention manuscrite<br>« Lu et approuvé, mandat accepté »</p>                      
                </td>
            </tr>
        </table>    
    </div><?php 
}

function mentions_avant_signatures_mandat_exclusif(){
    ?>
    <p class="bold ligne_simple">Conformément à l’article L. 121-21-5 du Code de la consommation :</p>
    <p class="bold ligne_simple">le mandant souhaite expressément que le mandataire commence ses prestations avant l’expiration du délai de rétractation.</p>
    <table>
        <tr>
            <td class="left">
                <p class="mention small">Le mandant reconnaît expressément avoir pris connaissance, préalablement à la signature des présentes, de l’intégralité des caractéristiques des services définis au présent mandat, ainsi que toutes les informations prévues aux articles L 111-1 et suivants du Code de la consommation.</p>
            </td>
            <td>  
                <p class="mention small">Le mandant reconnaît avoir pris connaissance des conditions générales ci-dessus, et du modèle de formulaire de rétractation en dernière</p>                                   
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="left">
                <p class="ligne_simple">Fait à (adresse complète) : </p> 
                <p class="small ligne_simple">(en deux exemplaires, dont un pour<br>le propriétaire et un pour l’agence.)</p>
            </td>
            <td>  
                <p class="ligne_simple">Date :</p>                                
            </td>
        </tr>
    </table> 
    <?php 
}

function mentions_avant_signatures_mandat_simple(){
    ?>    
    <table>
        <tr>
            <td class="left">
                <p>Fait à :</p>
            </td>
            <td>  
                <p>Date :</p>                                   
            </td>
        </tr>
    </table>
    <p>en deux exemplaires originaux, dont un pour le propriétaire et un pour l’agence.</p>
    <br>
    <p>Le mandat reconnaît expressément avoir pris connaissance, préalablement à la signature des présentes, de l’intégralité des caractéristiques des services définis au présent mandat, conformément aux articles L111-1 et suivants du Code de la consommation.</p>
    <p class="bold ligne_simple">Le mandant reconnaît avoir pris connaissance des conditions générales.</p>
    <?php 
}

function formulaire_de_retractation($noms, $adresses){
    ?>
    <br><br>
    <div class="titre_mandat retractation">
        <p class="ligne_1 ligne_simple vert">modèle de formulaire de rétractation</p>                
    </div>    
    <div class="bloc">
        <p class="small">Code de la consommation - art. Annexe à l’article R121-1 Créé par décret n°2014-1061 du 17 septembre 2014.<br>Veuillez compléter et renvoyer le présent formulaire uniquement si vous souhaitez vous rétracter du contrat ci-avant.</p>
        <br>
        <p class="ligne_simple">A l’attention de : SAS SEVEN & SEVEN DE LA COUR AU JARDIN</p>
        <p class="ligne_simple">Nom de l’agence :</p>
        <br>
        <p class="ligne_simple">Adresse : 157 rue du Faubourg Saint Antoine 75011 PARIS</p>
        <br>
        <p class="ligne_simple">Téléphone :</p>
        <p class="ligne_simple">Télécopie (fax) :</p>
        <p class="ligne_simple">E-mail : contact@delacouraujardin.com</p>
    </div>
    <div class="bloc">            
        <p>Je/nous* vous notifie/notifions* par la présente ma/notre* rétractation du contrat, portant sur la vente du bien ci-après<br>(*rayer la mention inutile) :</p>
    </div>
    <div class="bloc">  
        <p class="ligne_simple">Commandé le :</p>                
        <p class="ligne_simple">Nom du (des) consommateur(s) : <?php echo $noms; ?></p>
        <br>
        <p class="ligne_simple">Adresse du (des) consommateur(s) : </p><?php echo $adresses; ?>
    </div>
    <div class="bloc">  
        <p class="ligne_simple">Date :</p>
        <p class="ligne_simple">Signature du (des) consommateur(s), uniquement en cas de notification du présent formulaire sur papier.</p> 
    </div>
    <br><br><br><br><br>
    <div class="bloc"> 
        <p class="small">Conditions :<br>• Compléter et signer ce formulaire.<br>• L’envoyer par lettre recommandée avec avis de réception.<br>• Utiliser l’adresse de l’agence ci-dessus.<br>• L’expédier au plus tard le quatorzième jour à partir du jour de la commande ou,<br>si ce délai expiré normalement un samedi, un dimanche ou un jour férié ou chômé,<br>le premier jour ouvrable suivant.<br>• L’envoyer par lettre recommandée avec avis de réception.<br>• Si une adresse mail ou un numéro de télécopie figurent, vous pouvez utiliser<br>l’un ou l’autre pour notifier votre rétractation</p>
    </div>
    <?php
}