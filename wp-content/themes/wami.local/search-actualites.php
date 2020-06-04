<?php $terms = get_terms("type_bien"); //debug($terms); ?>
<?php $qsearch = get_search_query(); 
$my_term = $qsearch!="" ? get_term_by('name', $qsearch, 'lieu') : 0;  
$submit_val = $my_term ? $my_term->name.' ('.$my_term->count.')' : 'Rechercher'; ?>

<form id="filtre_les_actualites">   
    
    <fieldset class="single-field grid-col col_size-4 tablet_size-12">        
        <input placeholder="Rechercher" value="" name="mon_lieu" autocomplete="off" id="mon_lieu" type="text">
        <input type="submit" value="ok" class="right-arrow submit-secondary">
    </fieldset>   

    <fieldset class="single-line align-right grid-col col_size-8 mobile_size-12 col_hack-padding">

        <input id="ma_page" type="hidden" value="<?php echo $post->post_name; ?>">
        
        <select id="filtre_mois" name="filtre_mois">
            <option value="">Choisir un mois</option>            
            <?php $dates = wami_acf_get_archives_month();
            if($dates && is_object($dates)){
                foreach($dates as $d){
                    echo '<option value="'.$d->year.$d->month.'">'.$d->mois.' '.$d->year.'</option>';        
                };  
            }
            ?>
        </select>

        <input type="submit" value="<?php echo $submit_val; ?>" class="submit-secondary">

    </fieldset>

</form>
