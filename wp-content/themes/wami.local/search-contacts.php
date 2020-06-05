<form id="filtre_les_contacts">   
    
    <fieldset class="single-field grid-col col_size-4 tablet_size-12">        
        <input placeholder="Rechercher" value="" name="mon_contact" autocomplete="off" id="mon_contact" type="text">
        <input type="submit" value="ok" class="right-arrow submit-secondary">
    </fieldset>   

    <fieldset class="align-right grid-col col_size-8 mobile_size-12 col_hack-padding">   
        <a href="#" class="open_popin" data-openpopin="ajouter_contact">+</a>
        <?php
        $lettres_actives = wami_return_alphabetic_list_contact();
        foreach(range('A','Z') as $i) {
            if( in_array($i, $lettres_actives) )
                echo '<a href="#" class="search_contacts_by_letter" data-lettre="'.$i.'"">'.$i.'</a>';
            else                 
                echo "<span>".$i."</span>";
            if ($i != "Z") echo " ";
        } ?>
    </fieldset>

</form>
