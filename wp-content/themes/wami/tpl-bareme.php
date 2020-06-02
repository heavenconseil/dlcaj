<?php
/*
Template Name: TPL Bareme
*/
?>
<?php get_header(); ?>

	<div class="tpl-bareme">
		
		<section id="bareme">
    
            <div class="bg-overlay"></div>
            
            <div class="w_grid limited-content">
                
                <div class="grid-col col_size-12">
                    <h1><?php the_title(); ?></h1>                    
                    <p class="mention"><?php echo get_field("sous_titre") ? get_field("sous_titre") : ""; ?></p>
                </div>
                
                <div class="grid-col col_size-12">
                    <h2><?php echo get_field("tarif_option_1_titre") ? get_field("tarif_option_1_titre") : ""; ?></h2>
                    <p class="title-addition"><?php echo get_field("tarif_option_1_sous_titre") ? get_field("tarif_option_1_sous_titre") : ""; ?></p>
                </div>

                <?php if(get_field("tarif_option_1_type_de_presentation_des_tarifs") == "tableau") : ?>                
                    <div id="honoraires-table" class="bareme-bloc">
                        <div class="bareme-head">
                            <div class="grid-col col_size-4">
                                <h3>Appartement, maison, local commercial, terrain</h3>
                            </div>
                            <div class="grid-col col_size-4">
                                <h3>Honoraires exprimés en % du prix de vente</h3>
                            </div>
                            <div class="grid-col col_size-4">
                                <h3>à la charge</h3>
                            </div>
                        </div>                        
                        <div class="bareme-body">   
                            <?php if(have_rows('tarif_option_1_tableau_des_prix')) : ?>
                                <?php while(have_rows('tarif_option_1_tableau_des_prix')): the_row(); ?>
                                    <div class="bareme-row">
                                        <ul>
                                            <li class="grid-col col_size-4"><span class="mobile-only">Bien :</span><?php the_sub_field('fourchette_de_prix'); ?></li>
                                            <li class="grid-col col_size-4"><span class="mobile-only">Honoraires :</span><?php the_sub_field('honoraires'); ?></li>
                                            <li class="grid-col col_size-4"><span class="mobile-only">A la charge :</span><?php the_sub_field('a_payer_par'); ?></li>
                                        </ul>
                                    </div>
                                <?php endwhile; ?>  
                            <?php endif; ?>
                        </div>
                    </div>
                <?php ; elseif( get_field("tarif_option_1_type_de_presentation_des_tarifs") == "texte") : ?>
                    <div class="bareme-bloc">
                        <?php echo get_field("tarif_option_1_texte") ? get_field("tarif_option_1_texte") : ""; ?>
                    </div>
                <?php endif; ?>
                
                
                <div class="grid-col col_size-12">
                    <h2><?php echo get_field("tarif_option_2_titre") ? get_field("tarif_option_2_titre") : ""; ?></h2>
                    <p class="title-addition"><?php echo get_field("tarif_option_2_sous_titre") ? get_field("tarif_option_2_sous_titre") : ""; ?></p>
                </div>

                <?php if(get_field("tarif_option_2_type_de_presentation_des_tarifs") == "tableau") : ?>                
                    <div id="honoraires-table" class="bareme-bloc">
                        <div class="bareme-head">
                            <div class="grid-col col_size-4">
                                <h3>Appartement, maison, local commercial, terrain</h3>
                            </div>
                            <div class="grid-col col_size-4">
                                <h3>Honoraires exprimés en % du prix de vente</h3>
                            </div>
                            <div class="grid-col col_size-4">
                                <h3>à la charge</h3>
                            </div>
                        </div>                        
                        <div class="bareme-body">   
                            <?php if(have_rows('tarif_option_1_tableau_des_prix')) : ?>
                                <?php while(have_rows('tarif_option_1_tableau_des_prix')): the_row(); ?>
                                    <div class="bareme-row">
                                        <ul>
                                            <li class="grid-col col_size-4"><span class="mobile-only">Bien :</span><?php the_sub_field('fourchette_de_prix'); ?></li>
                                            <li class="grid-col col_size-4"><span class="mobile-only">Honoraires :</span><?php the_sub_field('honoraires'); ?></li>
                                            <li class="grid-col col_size-4"><span class="mobile-only">A la charge :</span><?php the_sub_field('a_payer_par'); ?></li>
                                        </ul>
                                    </div>
                                <?php endwhile; ?>  
                            <?php endif; ?>
                        </div>
                    </div>
                <?php ; elseif( get_field("tarif_option_2_type_de_presentation_des_tarifs") == "texte") : ?>
                    <div class="bareme-bloc">
                        <?php echo get_field("tarif_option_2_texte") ? get_field("tarif_option_2_texte") : ""; ?>
                    </div>
                <?php endif; ?>
                
                
                
                
            </div>
        
        </section>
    
	</div>

<?php get_footer(); ?>