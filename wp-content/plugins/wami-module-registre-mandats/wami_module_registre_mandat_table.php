<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class wami_manage_registre_mandats_table extends WP_List_Table {

 
    function column_default($item, $column_name){
        switch($column_name){
            case 'mandat_numero':
            case 'mandat_date':
            case 'mandant_nom':
            case 'mandant_adresse':
            case 'mandat_objet':
            case 'mandat_bien':
            case 'statut':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    function get_columns(){
        $columns = array(
            //'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
	        'mandat_numero'		=> __( 'N° du mandat' ),
            'statut'            => __( 'Statut du mandat' ),
			'mandat_date'		=> __( 'Date du mandat' ),
			'mandant_nom'		=> __( 'Nom du ou des mandant(s)' ),
			'mandant_adresse'	=> __( 'Adresse du ou des mandant(s)' ),
			'mandat_objet'     	=> __( 'Objet du mandat' ),
			'mandat_bien'		=> __( 'Nature et situation du bien' ),
        );
        return $columns;
    }
    
    function get_sortable_columns() {
        $sortable_columns = array(
            //'title'     => array('title',false),     //true means it's already sorted
            'mandat_numero'   => array('mandat_numero', false),
            'statut'          => array('statut', false),
            'mandat_date'     => array('mandat_date', false),
            'mandant_nom'     => array('mandant_nom', false),
        );
        return $sortable_columns;
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        $per_page = 100;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
      
        //$this->process_bulk_action();

        if( $_REQUEST['page'] ) {
           $url = '?page='.sanitize_text_field($_REQUEST['page']);
        } else {
            $url = '';
        }

        $wami_module_registre_mandat = new wami_module_registre_mandat(); 
        $rows = $wami_module_registre_mandat->return_all_mandats();
        foreach( $rows as $l=>$r ){
            if($l != "titre"){
                $data[] = $r;
            } 
        }

                
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? sanitize_text_field($_REQUEST['orderby']) : 'mandat_numero'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? sanitize_text_field($_REQUEST['order']) : 'asc'; //If no order, default to asc
            $result = strnatcmp($a[$orderby], $b[$orderby]); //Determine sort order
            //ici strnatcmp au lieu de strcmp pour le numéric !
            return ($order==='desc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');        
      
        $current_page = $this->get_pagenum();
       
        $total_items = count($data);
       
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);    
        
        $this->items = $data;    
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }

}