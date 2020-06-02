<?php 
$sub_bloc_actif = isset($_GET['subbloc']) ? $_GET['subbloc'] : 'detailbien';

$fields_bien_2 = array(         
    'field_597609cc2fc49', // Type de mandat (A LAISSER ET CACHER EN CSS CAR LES AUTRES CHAMPS EN DEPENDENT)
    // TYPE DE BIEN :  
    'field_597b4f5021f43', // titre ancre
    'field_5976161dead11', // type de bien
    'field_59c8ff76fc57e', // Résidence principale / secondaire
    'field_597616b5ead12', // Lieu insolite  
    'field_5901b56467591', // coup de coeur 
    // SURFACES
    'field_597b4f6321f44', // titre ancre
    'field_5976170eead14', // surface habitable
    'field_59761875ead1d', // Terrain
    'field_5900744778a56', // Superficie loi Carrez
    'field_59b0066f1436b', // Message loi carrez
    'field_5976180eead1a', // Surface du terrain
    'field_5976172cead15', // Surface utile
    'field_59761887ead1e', // Terrasse
    'field_59761741ead16', // Nombre de niveaux
    'field_59761854ead1b', // Surface de la terrasse
    'field_59761757ead17', // type de niveaux 
    'field_59761892ead1f', // Balcon
    'field_597617a5ead19', // surface de pièce principale
    'field_59761865ead1c', // Surface du balcon
    // PIECES / PRESTATIONS
    'field_597b4f7e21f45', // titre ancre
    'field_59b8f9689315b', // Message d'info
    'field_591eeb35686e7', // Pièce
    'field_5900746978a57', // Chambre
    'field_59761b4bb17d0', // Salon 
    'field_59761b12b17ce', // Séjour (indépendant)
    'field_59761ac0b17cd', // Suite parentale   
    'field_5977429895c13', // mezzanine  
    'field_5900922887d7d', // Salle d'eau
    'field_591ef25070bff', // Salle de bain
    'field_59d38c028ab21', // WC nb
    'field_59d38c138ab22', // WC séparés     
    'field_5977430395c16', // Spa          
    'field_5977431195c17', // Hammman
    'field_597741df95c0c', // Cuisine
    'field_59b0027019b10', // Cuisine équipée
    'field_59b0029c19b11', // Cuisine meublée
    'field_5977427295c11', // Arrière cuisine 
    'field_59de19c368b01', // Cellier
    'field_59de198368b00', // Buanderie
    'field_5977428d95c12', // Bureau  
    'field_5977426a95c10', // Grenier
    'field_5977423595c0d', // Cave       
    'field_5977431d95c18', // Cheminée / Poêle
    'field_5977424d95c0e', // Sous sol
    'field_5977425f95c0f', // Sous sol complet
    'field_5977433695c19', // Ascenseur
    'field_591eebe7686ea', // Acces handicapé
    'field_59de19d368b02', // Piscine
    'field_591eec26686eb', // Garage / Parking
    'field_597742e595c15', // Dépendances  
    'field_597742bf95c14', // Surface des dépendances
    'field_59de19e968b03', // Panneaux photovoltaïques
    'field_59761b2eb17cf', // Exposition    
    // COPROPRIETE
    'field_597b4f9e21f46', // titre ancre
    'field_59a689d4a24a1', // Copro oui ou non
    'field_5977437795c1a', // nombre d'étages
    'field_597743d495c1d', // litiges
    'field_597743fd95c1f', // nombre de lot de copro
    'field_5977439295c1b', // etage du bien
    'field_597743f095c1e', // gardien
    'field_5977441895c20', // Chage courante annuelles
    'field_597743b295c1c', // type d'architecture de l'immeuble    
    // CHAUFFAGE ET CONSOMMATION
    'field_597b4fc021f47', // titre ancre
    'field_5977453b95c25', // type de chauffage
    'field_591eec5f686ed', // chauffage
    'field_597745dc95c28', // Clim
    'field_5977457f95c27', // Maison écolo
    // HISTORIQUE ET DISPO
    'field_597b4fd421f48', // titre ancre
    'field_591eec45686ec', // Année de construction
    'field_59a68b340ed0c', // Date de rénovation
    'field_597744ca95c23', // Certificat de conformité
    // DISPONIBILITES
    'field_597b4fee21f49', // titre ancre
    'field_591eec76686ee', // Disponibilité
    // TAXE FONCIERE
    'field_597b500d21f4a', // titre ancre
    'field_591eed46686f2', // taxe fonciere       
    // INFORMATIONS ENERGETIQUE
    'field_597b502321f4b', // titre ancre
    'field_59007aef42618', // DPE
    'field_59c50d85a8af1', // bien non soumis aux DPE
    'field_59007a4742617', // GES 
    'field_5977462d95c2a', // Assainissement
    // PRIX
    'field_597b503e21f4c', // titre ancre
    'field_597603552fc45', // honoraires incl
    'field_5900742378a55', // sans honoraire    
    // HONORAIRES
    'field_597ef2be860ee', // titre ancre
    'field_597609192fc47', // honoraires à la charge de
    'field_59088607dfe53', // montant TTC des honoraires
    // DESCRIPTION
    'field_597b505621f4d', // titre ancre
    'field_597872b76a77a', // Présentation du bien
    'field_597872816a779', // Titre du bien
    'field_597746bc95c2b', // Notes internes  
    //CRITERES DE NOTATION
    'field_597b506f21f4e', // titre ancre
    // NOTE DE CHARME
    'field_597ef77c58edb', // Sous titre
    'field_590075ecd1aad', // commentaire
    'field_59257a8a883fe', // vu
    'field_59257b1688404', // jardin
    'field_59257beb88409', // mezzanine
    'field_59257c438840f', // bien classé    
    'field_59257aac883ff', // terrasse
    'field_59257b8f88405', // construction avec aspérité (style)
    'field_59257bfa8840a', // perron
    'field_59257c6988410', // Abscence de vis à vis
    'field_59257ac388400', // veranda
    'field_59257ba288406', // plan atypique
    'field_59257c048840b', // loft
    'field_59257c8488411', // patio
    'field_59257ad288401', // piscine    
    'field_59257bad88407', // luminosité
    'field_59257c0f8840c', // cheminée
    'field_59257c9288412', // etage elevé
    'field_59257ae288402', // volumes
    'field_59257c208840d', // atmosphere immeuble
    'field_59257c308840e', // authenticité
    'field_59257af588403', // cour fleurie
    'field_59257bc988408', // matériaux chaleureux
    // NOTE DE L'ENVIRONNEMENT
    'field_597ef79f58edc', // Sous titre 
    'field_5900767e42615', // commentaire       
    'field_59257d0288415', // Commodité et école tres proches
    'field_59257d1788416', // Commodités et écoles proches  
    'field_59257d4f88419', // Tres calme et nature
    'field_59257d5f8841a', // calme et nature     
    'field_59257d6d8841b', // urbain et tres calme
    'field_59257d828841c', // urbain et calme     
    'field_59257d2b88417', // urbain et quartier de grand charme vivant
    'field_59257d4188418', // urbain et quartier de charme vivant
    'field_5977476f95c2d', // accessible en TGV
    'field_59257cc888413', // acces transport excellents
    'field_59257cf488414', // acces transport faciles
    // HOTSPOT --> ATTENTION NON rattaché au lieu !        
    // CONTACT COMMERCIAL
    'field_597b509921f50', // titre ancre    
    'field_59087c1b5ce07', // agent
    'field_59087c305ce08', // telepohne de l'agent  
    'field_59a80271aaa10', // Mail 
    // IMAGES 
    'field_597ef1a2cf50d', // titre ancre
    'field_59a83a30ee2d7', // Message d'explication
    'field_590090484c4d7', // Slider d'images
    // LIEN VISITE VIRTUELLE
    'field_597ef1cbcf50e', // titre ancre
    'field_59e9c0f0774da', // type de visite lien, iframe ...
    'field_590c82f8e7046', // Lien de la visite 360
    'field_59a918461be8a', // Iframe de la visite 360
    'field_59e9c18d774db', // Iframe 360 en html

    // <iframe id="virtories-iframe" width="560" height="315" src="https://virtories.com/#/vr/-Km6Z46S1523QNy_Zi-k" frameborder="0" allowfullscreen></iframe>
    /*
    // Conditions particulières
    'field_59ae991e2170a', // titre ancre
    'field_59ae984421704', // Conditions particulières
    // Moyen de diffusion des annonces commerciales
    'field_59ae99432170b', // titre ancre
    'field_59ae984921705', // Moyen de diffusion des annonces commerciales
    // Actions particulières
    'field_59ae99652170c', // titre ancre
    'field_59ae984b21706', // Actions particulières
    // modalités et périoticité des comptes rendus
    'field_59ae98ea21709', // titre ancre
    'field_59ae984e21707', // modalités et périoticité des comptes rendus
    */
);
$fields_region = array(        
    'field_597609cc2fc49', // Type de mandat (A LAISSER ET CACHER EN CSS CAR LES AUTRES CHAMPS EN DEPENDENT)
    // 'field_590072fe03256', // nombre d'habitant
    // 'field_5900730e03257', // nb d'école
    // 'field_590893e16aee8', // Aperçu des transports
    // 'field_59006d94823a9', // Code postal
    // 'field_5903184a3d38a', // Image
    'field_5908940c6aee9' // Hotspot : lieux de référence
);
$fields_contact = array( 
    'field_597609cc2fc49', // Type de mandat (A LAISSER ET CACHER EN CSS CAR LES AUTRES CHAMPS EN DEPENDENT)
    // ADRESSE BIEN : 
    'field_597b50db21f52', // titre ancre
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
    // PROPRIETAIRE : 
    'field_597b510a21f53', // titre ancre
    'field_59786d67cfeaf', // propriétaire (repeater) 
    // CONTACT BIEN : 
    'field_597b512821f54', // titre ancre    
    'field_59760d376c4b1', // Nom
    'field_59760e026c4ba', // etage
    'field_59760d436c4b2', // Prenom
    'field_59760e0f6c4bb', // code porte 1
    'field_59760d6f6c4b3', // Telephone
    'field_59760e216c4bc', // code porte 2
    'field_59760d7d6c4b4', // Telephone portable
    'field_59760e2a6c4bd', // arrondissement
    'field_59760d9c6c4b5', // email
    'field_59760e3d6c4be', // CP
    'field_59760ddd6c4b7', // adresse 1
    'field_59760e566c4bf', // Ville
    'field_59760dec6c4b8', // adresse 2
    'field_59760e616c4c0', // Région
    'field_59760df36c4b9', // bâtiment
    'field_59760e716c4c1', // Pays
    // NOTES INTERNES :   
    'field_59a68fce2588d', // Notes internes                                   
);
?>

    <div class="sub-section_detailbien <?php if($sub_bloc_actif == 'detailbien') echo 'open'; else echo 'close'; ?>">
        <?php $args_bien_2 = array(
            'post_id'           => 'new_post',
            'new_post'          => true,
            'fields'            => $fields_bien_2,
            'field_el'          => 'div',
            'form'              => true, 
            'new_post'          => array(
                        'post_type'   => 'biens',
                        'post_status' => 'pending',
            ),           
            'submit_value' => 'Enregistrer'
        ); ?>  
        
        <?php if(get_query_var('page')) :
                                                                
            $args_bien_2['post_id']       = get_query_var('page');
            $args_bien_2['new_post']      = false;

            $args_query = array(
                'post__in'  => array(get_query_var('page')),
                'post_type' => 'biens',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'));
            $my_posts = new WP_Query($args_query); ?>                                    
           
            <?php if($my_posts->have_posts()) :
                while($my_posts->have_posts()) : 
                    $my_posts->the_post();
                        acf_form($args_bien_2); ?>
                        <?php $note = wami_cacul_charme_du_bien(get_query_var('page')); ?>
                        <ul class="key-notation undisplay" data-note="<?php echo $note; ?>">                            
                            <?php for( $i=1; $i<6; $i++) {
                                if($note>=1) echo '<li class="key key-on"></li>';
                                else if($note>0) echo '<li class="key key-float"></li>';
                                else echo '<li class="key key-off"></li>';
                                $note--;
                            }?>
                        </ul>
                        <?php $note = wami_cacul_environnement_du_bien(get_query_var('page'));  ?>
                        <ul class="star-notation undisplay" data-note="<?php echo $note; ?>">  
                            <?php for( $i=1; $i<6; $i++) {
                                if($note>=1) echo '<li class="star star-on"></li>';
                                else if($note>0) echo '<li class="star star-float"></li>';
                                else echo '<li class="star star-off"></li>';
                                $note--;
                            }?>
                        </ul>
                <?php endwhile; ?>                                   
            <?php endif; ?>

        <?php else : ?> 

            <?php acf_form($args_bien_2); ?>
            <ul class="key-notation undisplay">
                <?php $note = wami_cacul_charme_du_bien(get_query_var('page'));
                for( $i=1; $i<6; $i++) {
                    echo '<li class="key key-off"></li>';
                    $note--;
                }?>
            </ul>
            <ul class="star-notation undisplay">
                <?php $note = wami_cacul_environnement_du_bien(get_query_var('page'));    
                for( $i=1; $i<6; $i++) {
                    echo '<li class="star star-off"></li>';
                    $note--;
                }?>
            </ul>            
        
        <?php endif; ?>     
    </div>    


    <div class="sub-section_region <?php if($sub_bloc_actif == 'region') echo 'open'; else echo 'close'; ?>">
        <div class="acf-fields acf-form-fields by_wami">   
           
            <?php 
            $region = get_taxonomy_list_by_type_de_lieu('lieu', 'region');
            $ville  = get_taxonomy_list_by_type_de_lieu('lieu', 'ville'); 
            $district =  get_taxonomy_list_by_type_de_lieu('lieu', 'lieu');
            ?>

            <div class='titre_ancre section_lieu_localite'>Rattacher le bien à une région/ville/arrondissement</div>
            <div class="w_grid limited-content add_overflow">
                <div class="grid-col col_size-4 bloc_localite region">
                    <?php echo $region->list; ?>
                </div>
                <div class="grid-col col_size-4 bloc_localite ville<?php if(empty($region->region_checked)) echo ' undisplay'; ?>">
                    <?php echo $ville->list; ?>
                    <!-- <input id="add_ville" class="" name="add_ville" value="" placeholder="Ajouter une ville" maxlength="" type="text"> -->                    
                    <a href='#add_ville_popin' class='button btn-primary addville open_popin' data-openpopin='add_ville_popin'>Ajouter une ville</a>
                    <div id="add_ville_popin" class="popin-layer close">
                        <div class="popin add_ville_popin">
                            <h4>Renseignez les informations concernant la localité : </h4>
                            <form id="form_to_add_hotspot"> 
                                <input id="add_ville_name" class="" name="add_ville_name" value="" placeholder="Nom de la ville" maxlength="" type="text" required="required">
                                <input id="add_ville_cp" class="" name="add_ville_cp" value="" placeholder="Code Postal de la ville" maxlength="" type="number" required="required"> 
                                <input id="add_ville" class="button btn-primary button-large" value="Ajouter" type="submit">
                                <a href="" class="button btn-primary annuler close_popin" data-closepopin="add_ville_popin">Annuler</a>
                            </form>
                        </div>
                    </div> 
                </div>        
                <div class="grid-col col_size-4 bloc_localite lieu <?php if(empty($region->region_checked) || empty($ville->ville_checked)) echo ' undisplay'; ?>">
                    <?php echo $district->list; ?>
                    <!-- <input id="add_lieudit" class="" name="add_lieudit" value="" placeholder="Ajouter un arrodissement, lieu-dit" maxlength="" type="text"> -->
                    <a href='#add_lieudit_popin' class='button btn-primary addlieudit open_popin' data-openpopin='add_lieudit_popin'>Ajouter un lieudit</a>
                    <div id="add_lieudit_popin" class="popin-layer close">
                        <div class="popin add_lieudit_popin">                            
                            <h4>Renseignez les informations concernant la localité : </h4>
                            <form id="form_to_add_hotspot"> 
                                <input id="add_lieudit_name" class="" name="add_lieudit_name" value="" placeholder="Nom de l'arrodissement, lieu-dit" maxlength="" type="text" required="required">
                                <input id="add_lieudit_cp" class="" name="add_lieudit_cp" value="" placeholder="Code Postal de l'arrodissement, lieu-dit" maxlength="" type="number" required="required"> 
                                <input id="add_lieudit" class="button btn-primary button-large" value="Ajouter" type="submit">
                                <a href="" class="button btn-primary annuler close_popin" data-closepopin="add_lieudit_popin">Annuler</a>
                            </form>
                        </div>
                    </div> 
                </div>
            </div>  

            <div class="hotspot_conteneur">
                <?php if( get_query_var('page') ) : 
                    $last_localite = wami_get_last_localite_bien(get_query_var('page')); 
                    if( $last_localite && is_object($last_localite) && ($last_localite->type == 'district' || $last_localite->type == 'ville') ) :                        
                        $region = isset($last_localite->region->term_id) ? $last_localite->region->term_id : '';
                        $ville = $last_localite->type == 'ville' ? $last_localite->lieu->term_id : '';
                        $district = isset($last_localite->ville->term_id) ? $last_localite->ville->term_id : '';  
                        ?>
                        <div class="acf-field acf-field-message titre_ancre section_lieu_hostpot">Hotspot de <?php echo $last_localite->lieu->name; ?></div>
                            <ul class="hotspot">
                                <?php if(have_rows('lieu_de_reference', $last_localite->lieu->taxonomy.'_'.$last_localite->lieu->term_id)) :
                                    //$i=0; ?>
                                    <?php while(have_rows('lieu_de_reference', $last_localite->lieu->taxonomy.'_'.$last_localite->lieu->term_id)):
                                    the_row(); ?>
                                    <li>
                                        <p><b><?php the_sub_field('nom'); ?></b></p>
                                        <p><?php the_sub_field('adresse'); ?></p>
                                    </li>
                                    <?php  //$i++; if($i>=3) break; 
                                    endwhile; ?>                                 
                                <?php else : ?>
                                    <p class="infos">Aucun hotspot n'a pour le moment été ajouté à ce lieu.</p>
                                <?php endif; ?> 
                            </ul>
                            <a href='#add_hotspot' class='button btn-primary addhotspot open_popin' data-openpopin='add_hotspot'>Ajouter un hotspot</a>
                            <div id="add_hotspot" class="popin-layer close">
                                <div class="popin add_hotspot">
                                    <form id="form_to_add_hotspot">         
                                        <input id="add_hotspot_region" class="" name="add_hotspot_region" value="<?php echo $region; ?>" type="hidden">
                                        <input id="add_hotspot_ville" class="" name="add_hotspot_ville" value="<?php echo $ville; ?>" type="hidden">
                                        <input id="add_hotspot_district" class="" name="add_hotspot_district" value="<?php echo $district; ?>" type="hidden">

                                        <input id="add_hotspot_lieu" class="" name="add_hotspot_lieu" value="<?php echo $last_localite->lieu->taxonomy.'_'.$last_localite->lieu->term_id; ?>" type="hidden">
                                        <label for="add_hotspot_name">Nom</label>    
                                        <input id="add_hotspot_name" class="" name="add_hotspot_name" value="" placeholder="" maxlength="" type="text">
                                        <label for="add_hotspot_adresse">Adresse</label>    
                                        <input id="add_hotspot_adresse" class="" name="add_hotspot_adresse" value="" placeholder="" maxlength="" type="text">
                                        <input class="button button-primary button-large" value="Ajouter" type="submit">
                                    </form>
                                </div>
                            </div>  
                    <?php endif; ?>    
                <?php endif; ?> 
            </div> 

            <div class="acf-form-submit">
                <input id="enregistre_localite_et_hotspot" class="button button-primary button-large" value="Enregistrer" type="submit" data-postid="<?php echo get_query_var('page'); ?>">
            </div>      
            
        </div>
    </div>


    <div class="sub-section_contact <?php if($sub_bloc_actif == 'contact') echo 'open'; else echo 'close'; ?>">
        <?php $args_bien_2 = array(
            'post_id'           => 'new_post',
            'new_post'          => true,
            'fields'            => $fields_contact,
            'field_el'          => 'div',
            'form'              => true, 
            'new_post'          => array(
                        'post_type'   => 'biens',
                        'post_status' => 'pending',
            ),           
            'submit_value' => 'Enregistrer'
        ); ?>  
        
        <?php if(get_query_var('page')) :
                                                                
            $args_bien_2['post_id']       = get_query_var('page');
            $args_bien_2['new_post']      = false;

            $args_query = array(
                'post__in'  => array(get_query_var('page')),
                'post_type' => 'biens',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'));
            $my_posts = new WP_Query($args_query); ?>                                    
           
            <?php if($my_posts->have_posts()) :
                while($my_posts->have_posts()) : 
                    $my_posts->the_post();
                        acf_form($args_bien_2); ?>  
                <?php endwhile; ?>                                   
            <?php endif; ?>

        <?php else : ?> 
            <?php acf_form($args_bien_2); ?>
        
        <?php endif; ?> 
    </div>