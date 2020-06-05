<?php 
$internat = get_terms('lieu', array(
    "hide_empty"    => true,
    'orderby'       => 'count',
    'order'         => 'DESC',
    'parent'        => false,
    'post_type'     => 'biens',
    'meta_query'    => array(                   
        array(
            'key'       => 'type_de_lieu',
            'value'     => 'hors-carte',
            'compare'   => '='
        ),
    ),
    'number' => 1
)); 

$regions_all = get_terms('lieu', array(
    "hide_empty"    => true,
    'orderby'       => 'count',
    'order'         => 'DESC',
    'parent'        => false,
    'post_type'     => 'biens',
    'meta_query'    => array(                   
        array(
            'key'       => 'type_de_lieu',
            'value'     => 'hors-carte',
            'compare'   => '!='
        ),
    )
)); 
$total_region = count($regions_all);

$arg_city = array(    
    "hide_empty"    => false,
    'orderby'       => 'count',
    'order'         => 'DESC',
    'number'        => -1,
    'meta_query'    => array(                   
        array(
            'key'       => 'type_de_lieu',
            'value'     => 'ville',
            'compare'   => '='
        ),
    )
);
?>

<div class="w_grid limited-content">

    <div class="grid-col col_size-6 tablet_size-4 mobile_size-12">
        <h3>Rechercher<br/>par régions</h3>

        <?php $coupure = round($total_region/2)-1; ?>

        <div class="country-list-container">
        	<?php if($regions_all) : ?>
        		<ul class="country-list"> 
                    <?php for($i=0; $i<=$coupure; $i++) : ?>
            		<li class="country-item">
                        <a href="<?= get_term_link_for_map($regions_all[$i], 'lieu'); ?>">
                            <?php echo $regions_all[$i]->name; ?>
                            <?php echo '<span class="city"><i>('.$regions_all[$i]->count.')</i></span>'; ?>                        	
                        </a>
                    </li>
            	<?php endfor; ?>                
            	</ul>
                <ul class="country-list">
                <?php for($i=$coupure+1; $i<$total_region; $i++) : ?>
                    <li class="country-item">
                        <a href="<?= get_term_link_for_map($regions_all[$i], 'lieu'); ?>">
                            <?php echo $regions_all[$i]->name; ?>
                            <?php echo '<span class="city"><i>('.$regions_all[$i]->count.')</i></span>'; ?>
                        </a>
                    </li>
                <?php endfor; ?>  
                </ul>
        	<?php endif; ?>
        </div>

       
        <?php if($internat) : ?>
            <div class="internat-list-container">
                <ul class="internat-list"> 
                    <?php //for($i=0; $i<=$coupure; $i++) : ?>
                    <li class="country-item">
                        <a href="<?= get_term_link_for_map($internat[0], 'lieu'); ?>">
                            <?php echo $internat[0]->name; ?>
                            <?php echo '<span class="city"><i>('.$internat[0]->count.')</i></span>'; ?>                         
                        </a>
                    </li>
                <?php //endfor; ?>                
                </ul>  
            </div>              
        <?php endif; ?>

        <a href="<?php echo wami_get_page_link('biens-en-vente'); ?>" class="button btn-accent btn-center btn-to-center">Voir toutes les annonces</a>
        <?php //get_template_part('searchform', 'lieu'); ?>
    </div>


    <div class="grid-col col_size-6 tablet_size-8 region-map">  
        <div class="image_map"> 
            <img src="<?php echo get_template_directory_uri(); ?>/lib/img/map-new.png" alt="regions-de-france" title="Régions de France" width="771" height="890" border="0" usemap="#Map" />
            <map name="Map" id="Map">
                <?php if($regions_all) : $i = 1; ?>
                    <?php foreach( $regions_all as $region ) :
                    if(get_field('map-coordonnees', $region->taxonomy.'_'.$region->term_id)) : ?>
                       <area  id="map_<?php echo $i; ?>" class="mapping map_<?php echo $region->slug; ?>" shape="poly" alt="<?php echo $region->name; ?>" title="<?php echo $region->name; ?>" href="<?= get_term_link_for_map($region, 'lieu'); ?>" coords="<?php echo get_field('map-coordonnees', $region->taxonomy.'_'.$region->term_id); ?>" />
                        <span class="region_active" data-region="map_<?php echo $region->slug; ?>" style="position: absolute; top: <?php echo get_field('coordonnee_picto_top', $region->taxonomy.'_'.$region->term_id); ?>%; left: <?php echo get_field('coordonnee_picto_left', $region->taxonomy.'_'.$region->term_id); ?>%;"></span>
                    <?php endif;
                    $i++; endforeach; ?>
                <?php endif; ?>            
            </map>
        </div>        
    </div>


</div>