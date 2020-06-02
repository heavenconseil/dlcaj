<?php 
$iframe = get_field("iframe_de_la_visite_360_code_html") ? get_field("iframe_de_la_visite_360_code_html") : get_field("iframe_de_la_visite_360"); ?>

<div id="demarrer_visite_virtuelle" class="popin-layer close">
    <div class="popin">
        <?php echo $iframe; ?>
        <a href='#annuler' class='button btn-primary annuler close_popin bouton_close_video' data-closepopin="demarrer_visite_virtuelle">+</a> 
    </div>
</div>


        