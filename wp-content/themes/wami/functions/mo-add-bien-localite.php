<?php 

add_action('wp_ajax_nopriv_wami_add_bien_localite', 'wami_add_bien_localite');
add_action('wp_ajax_wami_add_bien_localite', 'wami_add_bien_localite');
function wami_add_bien_localite(){  

    if( isset($_REQUEST['data']) ) { 

        $localite       = $_REQUEST['data'];  
        $localite_id    = explode('_', $localite); 
        $localite_id    = $localite_id[1];
        $full_localite  = get_term($localite_id, 'lieu');

        $region         = $_REQUEST['region'];
        // $region_id      = explode('_', $region);  
        // $region_id      = $region_id[1];
        // $full_region    = get_term($region_id, 'lieu');

        $ville          = $_REQUEST['ville'];
        $ville_id       = explode('_', $ville); 
        $ville_id       = $ville_id[1];
        $full_ville     = get_term($ville_id, 'lieu');

        $district       = $_REQUEST['district'];
        // $district_id    = explode('_', $district);  
        // $district_id    = $district_id[1];  
        // $full_district  = get_term($district_id, 'lieu');  

        ?>

        <div class="acf-field acf-field-message titre_ancre section_lieu_hostpot">Hotspot de <?php echo get_field('type_de_lieu',  $full_localite->taxonomy.'_'.$full_localite->term_id) == 'disctrict' ? $full_ville->name.' '.$full_localite->name : $full_localite->name; ?></div>
        <?php if(have_rows('lieu_de_reference', $localite)) : 
            //$i=0; ?>
            <ul class="hotspot">
                <?php while(have_rows('lieu_de_reference', $localite)):
                the_row(); ?>
                <li>
                    <p><b><?php the_sub_field('nom'); ?></b></p>
                    <p><?php the_sub_field('adresse'); ?></p>
                </li>
                <?php  //$i++; if($i>=3) break; 
                endwhile; ?>  
            </ul>
        <?php else : ?>
            <p class="infos">Aucun hotspot n'a pour le moment été ajouté à ce lieu.</p>
        <?php endif; ?>

        <a href='#add_hotspot' class='button btn-primary addhotspot open_popin' data-openpopin='add_hotspot'>Ajouter un hotspot</a>
        <div id="add_hotspot" class="popin-layer close">
            <div class="popin add_hotspot">
                <form id="form_to_add_hotspot">
                    <input id="add_hotspot_region" class="" name="add_hotspot_region" value="<?php echo $region; ?>" type="hidden">
                    <input id="add_hotspot_ville" class="" name="add_hotspot_ville" value="<?php echo $ville; ?>" type="hidden">
                    <input id="add_hotspot_district" class="" name="add_hotspot_district" value="<?php echo $district; ?>" type="hidden">
                    <input id="add_hotspot_lieuname" class="" name="add_hotspot_lieuname" value="<?php echo get_field('type_de_lieu',  $full_localite->taxonomy.'_'.$full_localite->term_id) == 'disctrict' ? $full_ville->name.' '.$full_localite->name : $full_localite->name; ?>" type="hidden">
                    <input id="add_hotspot_lieu" class="" name="add_hotspot_lieu" value="<?php echo $localite; ?>" type="hidden">
                    <label for="add_hotspot_name">Nom</label>    
                    <input id="add_hotspot_name" class="" name="add_hotspot_name" value="" placeholder="" maxlength="" type="text" required="required">
                    <label for="add_hotspot_adresse">Adresse</label>    
                    <input id="add_hotspot_adresse" class="" name="add_hotspot_adresse" value="" placeholder="" maxlength="" type="text" required="required">
                    <input class="button button-primary button-large" value="Ajouter" type="submit">
                </form>
            </div>
        </div> <?php   

    }
    die();
} 



add_action('wp_ajax_nopriv_wami_enregistre_lieu_et_hotspot', 'wami_enregistre_lieu_et_hotspot');
add_action('wp_ajax_wami_enregistre_lieu_et_hotspot', 'wami_enregistre_lieu_et_hotspot');
function wami_enregistre_lieu_et_hotspot(){ 

    if( isset($_REQUEST['data']) ) { 

        $datas = (object) $_REQUEST['data']; 
        $user = wp_get_current_user();

        $post_id = $datas->post_id;
        if( !$post_id ){
            $post_id = wp_insert_post( array(
                'post_type'     => 'biens',
                'post_author'   => $user->ID,   
            ));
        };

        $region      = $datas->region;         
        $region_id   = explode('_', $region);  
        $region_id   = $region_id[1];  
        $post_terms = array($region_id);

        $ville = $datas->ville;
        $c_ville = $datas->ville_cp;
        if( is_array($ville) ){
            $ville_id = wp_insert_term( $ville['nom'], 'lieu', array('parent'=>$region_id) ); 
            $ville_id = $ville_id['term_id'];           
            update_field("field_5901de9cfcb62", 'ville', 'lieu_'.$ville_id);
            update_field("field_59006d94823a9", $ville['cp'], 'lieu_'.$ville_id);
        } else {
            $ville_id   = explode('_', $ville);  
            $ville_id   = $ville_id[1];  
        }
        if( $ville_id ) $post_terms[] = $ville_id;
        
        $district = $datas->district;
        if( is_array($district) ){
            $district_id = wp_insert_term( $district['nom'], 'lieu', array('parent'=>$ville_id) );
            $district_id = $district_id['term_id'];
            update_field("field_5901de9cfcb62", 'district', 'lieu_'.$district_id);  
            update_field("field_59006d94823a9", $ville['cp'], 'lieu_'.$district_id);          
        } 
        else {
            $district_id   = explode('_', $district);  
            $district_id   = $district_id[1];  
        }
        if( $district_id ) $post_terms[] = $district_id;  

        // On update le post avec la localité choisie (nota région, ville et district)
        // le dernier argument "false" permet de remplacer les région/ville/district qui ont peut etre déjà été renseigné pour ce post !
        $plop = wp_set_post_terms( $post_id, $post_terms, 'lieu', false );// On ajouter le hotspot pour le lieu

        // et on ajoute les éventuels hotspot
        if( $datas->hotspot ) : 
            $localite_id = !$district_id ? $ville_id : $district_id; 
            foreach ( $datas->hotspot as $row ) {
                $geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($row['adresse']).'&key='.GOOGLE_API_KEY.'&sensor=false');
                $geocode = json_decode($geocode);
                if( is_array($geocode->results) && !empty($geocode->results) ){
                    $value = array("address" => $geocode->results[0]->formatted_address, "lat" => $geocode->results[0]->geometry->location->lat, "lng" => $geocode->results[0]->geometry->location->lng);  
                    $row['adresse_map'] = $value;              
                }
                add_row( 'field_5908940c6aee9', $row, 'lieu_'.$localite_id );
            }
        endif; 

        // Et on passe par la class qu'on a créée pour le form ACF qui n'est plus dans cette rubrique du formulaire de creation de bien
        $acf_add_class = new WamiAmbassadeurSaveBien();
        $acf_add_class->bien_save_post($post_id);

        echo home_url('tableau-de-bord/ajouter-annonce/'.$post_id.'/?bloc=bloc-annonce&subbloc=region'); 
    
    };

    die();
}