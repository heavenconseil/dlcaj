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
    <div class="grid-col col_size-6 tablet_size-4 mobile_size-12" style="background:#979d5d; padding:20px; opacity:1;">
        <h3 style="margin-bottom: 40px;width: 320px;">L’IMMOBILIER DE CHARME ET INSOLITE</h3>

          <p style="color:#fff; font-size:16px;">De La Cour Au Jardin est l’agence immobilière spécialisée dans la vente de biens de charme, uniques et exclusifs proposés à nos clients français et étrangers : maisons, appartements, lofts, lieux insolites, ateliers d’artistes avec cour, terrasse ou jardin.</p>
          <br /><br />
          <h4 style="color:#fff; font-size:20px; margin:10px 0;">Vers le bien-être « Intérieur »</h4>
          <p style="color:#fff; font-size:16px; margin:10px 0;">Ces lieux où les ambiances s’imposent et où le charme opère. Le lieu d’habitation est le prolongement de soi. Tout comme chaque personne est unique, chaque bien est unique. Prendre le temps de se rencontrer, de se connaître et de partager votre projet fait partie intégrante de notre philosophie. Nous privilégions donc toujours la qualité des biens proposés.</p>
          <p style="color:#fff; font-size:16px; margin:10px 0;">Notre priorité et notre engagement ? Offrir une prestation sur mesure axée sur l’Humain et l’art de vivre.</p>
          <!--br /><br />
          <a href="<?php echo wami_get_page_link('biens-en-vente'); ?>" class="button btn-accent btn-center btn-to-center">Voir toutes nos annonces</a-->

        
    </div>

</div>