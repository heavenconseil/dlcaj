<?php 
require_once get_template_directory().'/functions/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

add_action('wp_ajax_nopriv_wami_get_doc_precontractuel_to_pdf', 'wami_get_doc_precontractuel_to_pdf');
add_action('wp_ajax_wami_get_doc_precontractuel_to_pdf', 'wami_get_doc_precontractuel_to_pdf');
function wami_get_doc_precontractuel_to_pdf(){    
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
            echo get_doc_precontractuel($mandat_id, $agent_id);
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
        $pdf_path = $upload_dir['basedir'].'/mandats/document_precontractuel_'.$ref_mandat.'.pdf';
        file_put_contents($pdf_path, $output);         

        //$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
        //$dompdf->stream();// Output the generated PDF to Browser   
        
        echo site_url().'/wp-content/uploads/mandats/document_precontractuel_'.$ref_mandat.'.pdf';      
    }    
    die();
} 

function get_doc_precontractuel($mandat_id, $agent_id){
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
        .titre_mandat p{
            font-family: "ITCBlair-Medium", Verdana, sans-serif;
            text-transform: uppercase;
            font-size: 28px;
            color: #979d5d;
            font-weight: bold;
            letter-spacing: 3;
        }
        .titre_mandat p.ligne_2 { 
            font-size: 16px; 
            font-weight: normal;
            letter-spacing: normal;
            margin: 0 0 0 14px;
            text-align: left;            
        }
        .mention_mandat{
            text-align: center;
        }
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
            font-size: 9px;
            font-style: italic;
        }
        .espace_blanc{
            height: 100px;
        }
        /*.signature{
            margin-top: 50px;
        }*/
       /*.signature_cadre{
            width: 45%;
            border: 1px solid #383838;
            padding: 0 10px 100px;
        }
        .separateur{width: 10%;}  */    
        /*.signature_cadre{
            width: 39%;
            border: 1px solid #383838;
            padding: 0 10px 100px;
        }
        .signature_infos{                
            width: 22%;
            border: 1px solid #383838;
            padding: 0 10px 100px;
        }*/        
        .signature{
            margin-top: 20px;
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
        .green_border{ 
            width: 100%;
            height: 2px;
            background : #cbcead; 
        }
    </style>

    <?php // DEF DES VARIABLES :
    //et pour tout type de mandat    
    $ref_mandat = get_field('bien_ref', $mandat_id) ? get_field('bien_ref', $mandat_id) : "";
    $mandat_id = sprintf("%06d", $mandat_id);
    $agent_id = sprintf("%06d", $agent_id);    
    $ambassadeur = get_userdata( $agent_id );
    $ambassadeur_nom = $ambassadeur->first_name;
    $ambassadeur_prenom = $ambassadeur->last_name;    
    $ambassadeur_email = $ambassadeur->user_email;
    ?>

    <div id="footer">
      <!--Page <span class="pagenum"></span>-->
    </div>

    <div class="mandat">

        <div class="center"><img src="<?php echo get_template_directory(); ?>/lib/img/logo-middle-office.png"/></div>
        <h1>De la cour au jardin</h1>
        <h2>Vous êtes déjà chez vous</h2>
        
        <div class="titre_mandat">
            <p class="ligne_1 ligne_simple vert">INFORMATIONS PRÉCONTRACTUELLES</p>
            <p class="ligne_2 ligne_simple vert">préalables à la signature d’un mandat<p>
        </div>

        <div class="mention_mandat">
            <p class="ligne_simple mention small">COMMUNICATION AU CONSOMMATEUR</p>
            <p class="ligne_simple mention small">en application des articles l 111-1 et suivants du code de la consommation, le professionnel prestataire de services avec lequel vous entrez en relation vous informe.<p>
        </div>

        <div class="adresse_dlcaj">
            <p class="ligne_simple">SAS SEVEN & SEVEN DE LA COUR AU JARDIN</p>
            <p class="ligne_simple">RCP VD7.000.001 / 20372</p>
            <p class="ligne_simple">RCS Paris 811 493 162</p>
            <p class="ligne_simple">Carte CPI 7501 2017 000 015964</p>
            <p class="ligne_simple">157 rue du Faubourg Saint Antoine 75011 PARIS</p>
            <p class="ligne_simple">contact@delacouraujardin.com</p>
            <br>
            <p class="mention small">Le cachet de l’agence devra contenir toutes les mentions obligatoires notamment les informations exigées par l’article 92 du décret n° 72-678 du 20/07/72 et les articles R 111-1 et R 111-2 du Code de la consommation, ainsi que les coordonnées du médiateur dont il relève.<p>
        </div>

        <div class="entete">
            <div class="entete_bloc">
                <p class="ligne_simple">Représentée par :
                    <?php echo $ambassadeur_nom.' '.$ambassadeur_prenom; ?>
                </p>
                <p class="ligne_simple">Qualité : 
                    <?php $type_ambassadeur = get_field('type_ambassadeur', 'user_'.$ambassadeur->id); ?>
                    <?php echo  (isset($type_amb) && is_array($type_amb)) ?  $type_ambassadeur['label'] : ""; ?>
                    <?php echo get_field('type_ambassadeur_negociateur', 'user_'.$agent_id) ? ' '.get_field('type_ambassadeur_negociateur', 'user_'.$agent_id) : ""; ?>                    
                </p> 
                <table>
                    <tr>
                        <td class="left">
                            <p class="ligne_simple"><?php echo get_field('type_ambassadeur_agent_rsac_ville', 'user_'.$agent_id) ? ' inscrit au RSAC de '.get_field('type_ambassadeur_agent_rsac_ville','user_'.$agent_id) : ""; ?></p>
                        </td>
                        <td>
                            <p class="ligne_simple"><?php echo get_field('type_ambassadeur_agent_rsac_num', 'user_'.$agent_id) ? '<br>sous le n° '.get_field('type_ambassadeur_agent_rsac_num', 'user_'.$agent_id) : ""; ?></p>
                        </td> 
                    </tr>
                    <tr>                     
                        <td class="left"><p class="ligne_simple">E-mail : <?php echo $ambassadeur_email ; ?></p></td>
                        <td><p class="ligne_simple">Tél : <?php echo get_field('ambassadeur_telephone', 'user_'.$agent_id); ?></p></td>
                        
                    </tr>
                </table>               
            </div>
            <br>             
            <div class="green_border"></div>  

            <div class="entete_bloc">
                <p class="ligne_simple">Le (les) consommateur(s), nom et prénom :</p>
                <?php $noms = $adresses = $emails = $telephones = '';
                if(have_rows('proprietaires', $mandat_id)) : ?>
                    <?php while(have_rows('proprietaires', $mandat_id)): the_row(); 
                        $nom = get_sub_field('proprietaire_societe') ? get_sub_field('proprietaire_raison_social').' représentée par : '.get_sub_field('proprietaire_société_attention_de') : get_sub_field('proprietaire_nom').' '.get_sub_field('proprietaire_prenom'); 
                        $noms .= $nom.'<br>';
                        ?>            
                        <p class="ligne_simple"><?php echo $nom; ?></p>
                        <?php 
                        // $adresses .= '<br>';
                        // $adresses .= get_sub_field('proprietaire_adresse') ? get_sub_field('proprietaire_adresse') : "";
                        // $adresses .= '<br>';
                        // $adresses .= get_sub_field('proprietaire_cp') ? get_sub_field('proprietaire_cp').' ' : "";
                        // $adresses .= get_sub_field('proprietaire_ville') ? get_sub_field('proprietaire_ville') : "";
                        // $adresses .= '<br>';
                        // $adresses .= get_sub_field('proprietaire_pays') ? get_sub_field('proprietaire_pays') : ""; ?>
                        <?php //$emails .= '<br>'.get_sub_field('proprietaire_email') ? get_sub_field('proprietaire_email') : ""; ?>
                        <?php //$telephones .= '<br>'.get_sub_field('proprietaire_telephone') ? return_tel_french_format(get_sub_field('proprietaire_telephone')) : ""; ?>
                        <table>
                            <tr>
                                <td class="left"><p class="ligne_simple">E-mail : <?php echo get_sub_field('proprietaire_email') ? get_sub_field('proprietaire_email') : ""; ?></p></td>
                                <td><p class="ligne_simple">Tél : <?php echo get_sub_field('proprietaire_telephone') ? return_tel_french_format(get_sub_field('proprietaire_telephone')) : ""; ?></p></td>
                            </tr>
                        </table>
                        <p class="ligne_simple">demeurant :
                            <?php echo get_sub_field('proprietaire_adresse') ? get_sub_field('proprietaire_adresse').' ' : "";
                            echo get_sub_field('proprietaire_cp') ? get_sub_field('proprietaire_cp').' ' : "";
                            echo get_sub_field('proprietaire_ville') ? get_sub_field('proprietaire_ville').' ' : "";
                            echo get_sub_field('proprietaire_pays') ? get_sub_field('proprietaire_pays') : ""; ?>
                        </p><br>
                    <?php endwhile; ?>
                <?php endif; ?> 
               <!--  <table>
                    <tr>
                        <td class="left"><p class="ligne_simple">E-mail : <?php //echo $emails; ?></p></td>
                        <td><p class="ligne_simple">Tél : <?php// echo $telephones; ?></p></td>
                    </tr>
                </table>
                <p class="ligne_simple">demeurant <?php //echo $adresses; ?></p> -->
            </div> 
            <div class="green_border"></div> 
        </div>        
        
        

        <div class="bloc">
            <p>Votre interlocuteur exerce l’activité d’entremise sur les immeubles et fonds de commerce, conformément à la loi n° 70-9 du 2 janvier 1970, dite « loi Hoguet » et au décret n°72-678 du 20 juillet 1972, consultables sur le site www.legifrance.gouv.fr.</p>
            <p>Le service proposé consiste en la vente d'un bien immobilier via le mandat n°<?php echo $ref_mandat; ?>.</p> 
            <p>Comprenant une 1<sup>er</sup> période irrévocable de 3 mois.</p>   
            <p>Modalités de dénonciation : par lettre de recommandée avec demande d’avis de réception (LRAR), avec un préavis de 15 jours.</p>
            <p class="ligne_simple">Honoraires, en cas de pleine réussite de la mission confiée : <?php echo get_field('bien_honoraires_montant', $mandat_id) ? number_format(get_field('bien_honoraires_montant', $mandat_id), 0, '', ' ')."€" : ""; ?>.</p>
            <p class="ligne_simple">Modalités de règlement : chèque ou virement.</p>
        </div>

        <div class="bloc">
            <h3>DROIT DE RÉTRACTATION</h3>
            <p class="ligne_simple">Si le mandat est signé « hors établissement » ou « à distance », le mandant pourra se rétracter pendant un délai de 14 jours à compter de la signature du mandat, en renvoyant au professionnel le coupon de rétractation attaché au mandat ou toute déclaration dénuée d’ambiguïté, exprimant sa volonté de se rétracter, par lettre recommandée avec demande d’accusé réception ; ce, sans avoir à motiver sa décision.</p>
            <p class="ligne_simple">Le mandant pourra, s’il le souhaite, lors de la signature du mandat, demander à ce que le mandataire commence ses prestations avant l’expiration du délai de rétractation. Il pourra toutefois se rétracter durant cette période, sauf si le mandataire a pleinement exécuté sa mission. Le mandant reconnaît avoir pris connaissance du formulaire de rétractation attaché au mandat (modèle de formulaire de rétractation type ci-après).</p>
            <p class="ligne_simple">Si le mandat est signé à l’agence, le mandant ne bénéficie d’aucun droit de rétractation.</p>
        </div>

        <div class="bloc">
            <h3>PRÉVENTION ET RÈGLEMENT DES LITIGES ET RÉCLAMATIONS</h3>
            <p class="ligne_simple">Pour toute éventuelle réclamation, nous vous remercions de la faire à votre choix par courrier à notre adresse postale en tête des présentes, par téléphone, ou par mail ; nous la traiterons dans les meilleurs délais.</p>
            <p class="ligne_simple">En cas de litige, la législation applicable sera la loi française, et la juridiction compétente celle du lieu du domicile du consommateur.</p>            
        </div>

        <div class="bloc">
            <h3>MÉDITATION DES LITIGES DE LA CONSOMMATION</h3>
            <p class="ligne_simple">Tout consommateur a le droit de recourir gratuitement à un médiateur de la consommation en vue de la résolution amiable du litige qui l’oppose à un professionnel. A cet effet, le professionnel garantit au consommateur le recours effectif à un dispositif de médiation de la consommation, en vertu des Articles L611-1 et suivants du Code de la consommation, créés par l’ordonnance n°2016-301 du 14 mars 2016. Vous pouvez trouver les coordonnées du médiateur sectoriel sur http://www.economie.gouv.fr/mediation-conso.</p>
            <p class="bold">Le consommateur reconnaît avoir pris connaissance de l’ensemble des conditions générales et particulières du mandat proposé, par la remise préalable qui lui a été faite d’un exemplaire.</p>
        </div>

        
        <div class="signature">
             <table>
                <tr>
                    <td class="left">
                        <p class="ligne_simple">Fait à :</p> 
                    </td>
                    <td>  
                        <p class="ligne_simple">Date :</p>                                
                    </td>
                </tr>
            </table> 
            <p>en deux exemplaires originaux, dont un remis à chacune des parties qui le reconnaît.</p>
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
                        <p class="bold">Signature du (des) consommateurs</p>
                    </td>
                    <td class="separateur"></td>
                    <td class="signature_cadre">
                       <p class="bold">Signature du mandataire</p>                      
                    </td>
                </tr>
            </table>
        </div>
      

    </div>
    <?php
}