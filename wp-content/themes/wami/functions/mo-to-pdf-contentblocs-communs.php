<?php 
// LE STYLE
function get_wami_pdf_style(){
    ?><style>
        @import url("//hello.myfonts.net/count/337be5");
       /* @font-face {
            font-family: 'AvenirLTPro-Medium';            
            src: url('/dompldf/lib/fonts/337BE5_0_0.eot'),
                 url('/dompldf/lib/fonts/337BE5_0_0.eot?#iefix') format('embedded-opentype'),
                 url('/dompldf/lib/fonts/337BE5_0_0.woff2') format('woff2'),
                 url('/dompldf/lib/fonts/337BE5_0_0.woff') format('woff'),
                 url('/dompldf/lib/fonts/337BE5_0_0.ttf') format('truetype');
        }
        @font-face {
            font-family: 'AvenirLTPro-BookOblique';            
            src: url('/dompldf/lib/fonts/337BE5_1_0.eot'),
                 url('/dompldf/lib/fonts/337BE5_1_0.eot?#iefix') format('embedded-opentype'),
                 url('/dompldf/lib/fonts/337BE5_1_0.woff2') format('woff2'),
                 url('/dompldf/lib/fonts/337BE5_1_0.woff') format('woff'),
                 url('/dompldf/lib/fonts/337BE5_1_0.ttf') format('truetype');
        }          
        @font-face {
            font-family: 'AvenirLTPro-Book';            
            src: url('/dompldf/lib/fonts/337BE5_2_0.eot'),
                 url('/dompldf/lib/fonts/337BE5_2_0.eot?#iefix') format('embedded-opentype'),
                 url('/dompldf/lib/fonts/337BE5_2_0.woff2') format('woff2'),
                 url('/dompldf/lib/fonts/337BE5_2_0.woff') format('woff'),
                 url('/dompldf/lib/fonts/337BE5_2_0.ttf') format('truetype');
        }
        @font-face {
            font-family: 'AvenirLTPro-MediumOblique';            
            src: url('/dompldf/lib/fonts/337BE5_3_0.eot')
                 url('/dompldf/lib/fonts/337BE5_3_0.eot?#iefix') format('embedded-opentype'),
                 url('/dompldf/lib/fonts/337BE5_3_0.woff2') format('woff2'),
                 url('/dompldf/lib/fonts/337BE5_3_0.woff') format('woff'),
                 url('/dompldf/lib/fonts/337BE5_3_0.ttf') format('truetype');
        }
        @font-face {
            font-family: 'AvenirLTPro-Heavy';            
            src: url('/dompldf/lib/fonts/337BE5_4_0.eot'),
                 url('/dompldf/lib/fonts/337BE5_4_0.eot?#iefix') format('embedded-opentype'),
                 url('/dompldf/lib/fonts/337BE5_4_0.woff2') format('woff2'),
                 url('/dompldf/lib/fonts/337BE5_4_0.woff') format('woff'),
                 url('/dompldf/lib/fonts/337BE5_4_0.ttf') format('truetype');
        }
        @font-face {
            font-family: 'AvenirLTPro-HeavyOblique';            
            src: url('/dompldf/lib/fonts/337BE5_5_0.eot')
                 url('/dompldf/lib/fonts/337BE5_5_0.eot?#iefix') format('embedded-opentype'),
                 url('/dompldf/lib/fonts/337BE5_5_0.woff2') format('woff2'),
                 url('/dompldf/lib/fonts/337BE5_5_0.woff') format('woff'),
                 url('/dompldf/lib/fonts/337BE5_5_0.ttf') format('truetype');
        }
        @font-face {
            font-family: 'ITCBlair-Medium';            
            src: url('/dompldf/lib/fonts/337BE5_6_0.eot');
            src: url('/dompldf/lib/fonts/337BE5_6_0.eot?#iefix') format('embedded-opentype'),
                 url('/dompldf/lib/fonts/337BE5_6_0.woff2') format('woff2'),
                 url('/dompldf/lib/fonts/337BE5_6_0.woff') format('woff'),
                 url('/dompldf/lib/fonts/337BE5_6_0.ttf') format('truetype');
        }  */
        @font-face {
            font-family: 'akrobat-black';          
            src: url('/dompldf/lib/fonts/Akrobat-Black.eot'),
                 url('/dompldf/lib/fonts/Akrobat-Black.eot?#iefix') format('embedded-opentype'),   
                 url('/dompldf/lib/fonts/Akrobat-Black.woff2') format('woff2'), 
                 url('/dompldf/lib/fonts/Akrobat-Black.woff') format('woff'), 
                 url('/dompldf/lib/fonts/Akrobat-Black.ttf') format('truetype'); 
        }
        @font-face {
            font-family: 'akrobat-bold';  
            src: url('/dompldf/lib/fonts/Akrobat-Bold.eot'),
                 url('/dompldf/lib/fonts/Akrobat-Bold.eot?#iefix') format('embedded-opentype'),            
                 url('/dompldf/lib/fonts/Akrobat-Bold.woff2') format('woff2'), 
                 url('/dompldf/lib/fonts/Akrobat-Bold.woff') format('woff'), 
                 url('/dompldf/lib/fonts/Akrobat-Bold.ttf') format('truetype'); 
        }
        @font-face {
            font-family: 'akrobat-extrabold'; 
            src: url('/dompldf/lib/fonts/Akrobat-ExtraBold.eot'),
                 url('/dompldf/lib/fonts/Akrobat-ExtraBold.eot?#iefix') format('embedded-opentype'),             
                 url('/dompldf/lib/fonts/Akrobat-ExtraBold.woff2') format('woff2'), 
                 url('/dompldf/lib/fonts/Akrobat-ExtraBold.woff') format('woff'), 
                 url('/dompldf/lib/fonts/Akrobat-ExtraBold.ttf') format('truetype');
        }
        @font-face {
            font-family: 'akrobat-extralight';   
            src: url('/dompldf/lib/fonts/Akrobat-ExtraLight.eot'),
                 url('/dompldf/lib/fonts/Akrobat-ExtraLight.eot?#iefix') format('embedded-opentype'),           
                 url('/dompldf/lib/fonts/Akrobat-ExtraLight.woff2') format('woff2'), 
                 url('/dompldf/lib/fonts/Akrobat-ExtraLight.woff') format('woff'), 
                 url('/dompldf/lib/fonts/Akrobat-ExtraLight.ttf') format('truetype'); 
        }
        @font-face {
            font-family: 'akrobat-light';    
            src: url('/dompldf/lib/fonts/Akrobat-Light.eot'),
                 url('/dompldf/lib/fonts/Akrobat-Light.eot?#iefix') format('embedded-opentype'),          
                 url('/dompldf/lib/fonts/Akrobat-Light.woff2') format('woff2'), 
                 url('/dompldf/lib/fonts/Akrobat-Light.woff') format('woff'), 
                 url('/dompldf/lib/fonts/Akrobat-Light.ttf') format('truetype'); 
        }
        @font-face {
            font-family: 'akrobat-regular';   
            src: url('/dompldf/lib/fonts/Akrobat-Regular.eot'),
                 url('/dompldf/lib/fonts/Akrobat-Regular.eot?#iefix') format('embedded-opentype'),          
                 url('/dompldf/lib/fonts/Akrobat-Regular.woff2') format('woff2'), 
                 url('/dompldf/lib/fonts/Akrobat-Regular.woff') format('woff'), 
                 url('/dompldf/lib/fonts/Akrobat-Regular.ttf') format('truetype'); 
        }
        @font-face {
            font-family: 'akrobat-semibold';  
            src: url('/dompldf/lib/fonts/Akrobat-SemiBold.eot'),
                 url('/dompldf/lib/fonts/Akrobat-SemiBold.eot?#iefix') format('embedded-opentype'),            
                 url('/dompldf/lib/fonts/Akrobat-SemiBold.woff2') format('woff2'), 
                 url('/dompldf/lib/fonts/Akrobat-SemiBold.woff') format('woff'), 
                 url('/dompldf/lib/fonts/Akrobat-SemiBold.ttf') format('truetype'); 
        }
        @font-face {
            font-family: 'akrobat-thin'; 
            src: url('/dompldf/lib/fonts/Akrobat-Thin.eot'),
                 url('/dompldf/lib/fonts/Akrobat-Thin.eot?#iefix') format('embedded-opentype'),             
                 url('/dompldf/lib/fonts/Akrobat-Thin.woff2') format('woff2'), 
                 url('/dompldf/lib/fonts/Akrobat-Thin.woff') format('woff'), 
                 url('/dompldf/lib/fonts/Akrobat-Thin.ttf') format('truetype'); 
        }
        .page{
            height: 925px;
            margin: 5px 20px 0;
        }
        .mandat{
            font-weight: 400;
            font-family: "akrobat-light", sans-serif; /*"AvenirLTPro-Book", Verdana, sans-serif;   */  
            font-size: 13px;
            line-height: 1em;
        }
        .semibold{
            font-family: "akrobat-bold", sans-serif;
            /*font-weight: bold;*/
        }
        .bold{
            font-family: "akrobat-semibold", sans-serif;
            /*font-weight: bold;*/
        }
        .uppercase{text-transform: uppercase;}
        .vert{color: #979d5d;}
        .center{text-align: center;}         
        .underline{border-bottom: 1px solid black; }
        img{
            max-width: 100%;
            height: auto;
        }
        img.logo{            
            max-width: 30%;
            height: auto;
        }
        a{text-decoration: none;}
        p.ligne_simple{margin: 0;}
        p.ligne_simple_bas{margin-bottom: 0;}
        p.ligne_double{margin-bottom: 10px;}
        p.ligne_height{line-height: 12px;}
        p.first{margin-top: 20px;}        
        .titre_mandat{
            font-family: "akrobat-regular", serif;
            text-align: center;
            padding: 10px;
            margin: 30px 0;
            height: 65px;
        }
        .titre_mandat p, .titre_mandat p.ligne_2.big{
            text-transform: uppercase;
            font-size: 24px;
            color: #979d5d;
            font-family: "akrobat-semibold", sans-serif; 
        }
        .titre_mandat.retractation{height: auto;}
        .titre_mandat.retractation p{letter-spacing: normal;}
        .titre_mandat p.ligne_2 { 
            font-size: 18px; 
            letter-spacing: normal;
            margin: 0;
        }
        .titre_mandat.small{
            text-transform: normal;
            font-size: 18px;
            letter-spacing: normal;
            margin: 0;
        }
        .mandat_num{text-transform:lowercase;}
        .titre_3{
            font-family: "akrobat-semibold", sans-serif; 
            text-transform: uppercase;
            font-size: 16px;
            color: #979d5d;
        }
        .titre_3 span { border-bottom: 1px solid #979d5d; }
        .adresse_dlcaj{
            text-align: right;
        }
        .adresse_dlcaj .ligne_simple{font-size: 15px;}
        .entete_bloc{
            padding: 10px 0;
        }
        .entete_bloc_s{
            padding: 5px 0;
        }
        .bloc{
            padding-top: 10px;
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
        td.w_30{ width: 36%; }
        td.w_70{ width: 60%; }
        .small{
            font-size: 10px;
            line-height: 12px;
        }
        .small.bold{line-height: 14px;}
        .mention{
            font-size: 8px;
            margin: 0;
        }
        .margin-b{margin-bottom: 10px;}
        .mention_cadre{   
            border: 1px solid #383838;
            padding: 0 10px 10px;
        }
        label{
            float: left; 
            padding-left: 20px;
        }        
        .signature{
            margin-top: 40px;
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
            bottom: 10px;
        } 
        .small_img{width: 800px; height: auto; }
    </style><?php 
}

// LES FONCTIONS ECHO DES BLOCS :
function get_pdf_logo(){
    ?><div class="center"><img class="logo" src="<?php echo get_template_directory(); ?>/lib/img/logo-dlcaj-pdf.png"/></div><?php
}

function get_pdf_adresse_agence($with_mention=false){
    ?><div class="adresse_dlcaj">
        <p class="ligne_simple semibold">Agence DE LA COUR AU JARDIN</p>
        <p class="ligne_simple semibold">157 rue du Faubourg Saint Antoine 75011 PARIS</p>
        <p class="ligne_simple semibold">Carte T 7501 2017 000 015964 délivrée le 17 janvier 2017 par la CCI de Paris</p>
        <p class="ligne_simple semibold">RCP GENERALI AL 591311/29511 - CE GC 16 rue Hoche 92319 LA DEFENSE</p>
        <p class="ligne_simple semibold">RCS Paris 811 493 162 / Agence non détentrice de fonds</p>
        <br>
    </div>
    <?php if($with_mention) echo '<p class="mention small">Le cachet de l’agence devra contenir toutes les mentions obligatoires notamment les informations exigées par l’article 92 du décret n° 72-678 du 20/07/72 et les articles R 111-1 et R 111-2 du Code de la consommation, ainsi que les coordonnées du médiateur dont il relève.<p>'; 
}


function get_pdf_represente_par($agent_id, $ambassadeur_id, $ambassadeur_nom, $ambassadeur_prenom, $ambassadeur_email){
    ?><p class="ligne_simple first">
        <span class="bold">Représentée par</span> <?php echo $ambassadeur_prenom.' '.$ambassadeur_nom; ?>, en qualité de 
        <?php $type_ambassadeur = get_field('type_ambassadeur', 'user_'.$ambassadeur_id); ?>
        <?php echo  (isset($type_ambassadeur) && is_array($type_ambassadeur)) ?  $type_ambassadeur['label'] : ""; ?>
        <?php echo get_field('type_ambassadeur_negociateur', 'user_'.$agent_id) ? ' '.get_field('type_ambassadeur_negociateur', 'user_'.$agent_id) : ""; ?>  
    </p>
    <p class="ligne_simple">E-mail : <span class="underline"><?php echo $ambassadeur_email; ?></span></p>
    <p class="ligne_simple">Téléphone : <?php echo get_field('ambassadeur_telephone', 'user_'.$agent_id); ?></p><?php
}


function get_pdf_signature_mandats($type_mandat=false){
    $le_proprietaire = ($type_mandat == 'mandat_recherche') ? "Le client" : "Le propriétaire";
    ?><div class="signature">
        <p class="bold">Le mandant reconnaît avoir pris connaissance des conditions générales ci-dessus, et du modèle de formulaire de rétractation en dernière page.
        <br>Il reconnait avoir reçu au préalable la communication des informations précontractuelles, annexées au présent mandat.</p>
        <br>
        <table>
            <tr>
                <td class="left w_30">
                    <p class="ligne_simple_bas">Fait à :<br>(en deux exemplaires, dont un pour<br>le propriétaire et un pour l’agence.)</p> 
                </td>
                <td class="right w_70 mention_cadre">
                    <p class="bold">Signer et parapher l'ensemble des documents édités ce<br>jour ayant seule valeur d’originaux.<p>
                    <table>
                        <tr>
                            <td><p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mots</p></td>
                            <td><p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; chiffres</p></td>
                        </tr>
                        <tr>
                            <td><p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; lignes</p></td>
                            <td><p class="ligne_simple">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; rayés comme nuls</p></td>
                        </tr>
                    </table>                    
                </td>
            </tr>
        </table> 
        <p class="ligne_simple">Date :</p>  
        <table>
            <tr>                
                <td class="signature_cadre">
                    <p class="ligne_simple_bas bold"><?= $le_proprietaire; ?> :</p>
                    <p class="ligne_simple">Signature précédée de la mention manuscrite « Lu et accepté, bon pour mandat »</p>
                </td>
                <td class="signature_cadre">
                   <p class="ligne_simple_bas bold">L'agence :</p>
                    <p class="ligne_simple">Signature précédée de la mention manuscrite « Lu et approuvé, mandat accepté »</p>                   
                </td>
            </tr>
        </table>
    </div><?php 
}