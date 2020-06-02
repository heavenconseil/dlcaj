<?php 

add_action('wp_ajax_nopriv_wami_update_bien_niveau_vente', 'wami_update_bien_niveau_vente');
add_action('wp_ajax_wami_update_bien_niveau_vente', 'wami_update_bien_niveau_vente');
function wami_update_bien_niveau_vente(){  

    if( isset($_REQUEST['data']) ) { 
        
        $bien_id    = $_REQUEST['bien_id'];
        $niveau_vente = $_REQUEST['niveau_vente'];

        if($niveau_vente == 'confirme-declarer-vente')
            update_field('field_597eebb61ceba', date('m/d/Y'), $bien_id);
            // Et on update l'etat du mandat
            update_field('field_597721a9edd7a', 'signe', $bien_id);

        if($niveau_vente == 'confirme-declarer-vendu'){
            update_field('field_5983301bd237a', 1, $bien_id);	// on update la case vendu            
            update_field('field_597eebec1cebb', date('m/d/Y'), $bien_id);	// on update la date de signature d'acte authentique	
			update_field('field_5901b5b467594', 0, $bien_id); // on retire le bien de dispo à la vente
            //  et on update l'etat du mandat
            update_field('field_597721a9edd7a', 'vendu', $bien_id);
        }
        
    }
    die();
} 