<?php 
include_add_action_for_acf_form();
$user = wp_get_current_user();
$ambassadeur = get_userdata( $user->ID );

$bloc_actif = isset($_GET['bloc']) ? $_GET['bloc'] : 'bloc-mandat';
$sub_bloc_actif = isset($_GET['subbloc']) ? $_GET['subbloc'] : 'detailbien';

$type_de_mandat = get_query_var('page') ? get_field("bien_mandat", get_query_var('page')) : '';
$class_for_type_mandat = (is_array($type_de_mandat) && ($type_de_mandat['value']=="mandat_de_recherche" || $type_de_mandat['value']=="mandat_delegation")) ? 'mandatrecherche_or_delegationmandat' : '';


// Si on est admin ou ambassadeur (sinon redirect to home)
if( in_array('administrator', $user->roles) || in_array('ambassadeur', $user->roles) || in_array('ambassadeur_responsable_de_region', $user->roles) ) : ?>

    <?php get_header('middle-office'); ?>        

        <div class="tpl-annonces-ajouts middle_office" data-commercial="<?php echo $ambassadeur->first_name.' '.$ambassadeur->last_name; ?>" data-ctel="<?php echo get_field('ambassadeur_telephone', 'user_'.$user->ID); ?>" data-cmail="<?php echo $user->user_email; ?>">         
                <div class="w_grid limited-content form-add-bien">

                    <?php 
                    if( get_query_var('page') && ( get_field('bien_vente_vendu', get_query_var('page')) || get_field('bien_vente_date_signature_acte', get_query_var('page')) || get_post_status(get_query_var('page'))=='trash' ) ) : ?>                      
                        <p>Ce bien est vendu ou indisponible, son mandat ne peut plus être modifié.</p>
                        <a href="#" class="button btn-secondary btn-to-right retour_precedent">Retour</a>

                    <?php ; else : ?> 
                    
                        <div class="grid-col col_size-3 tablet_size-3 mobile_size-12 asside">
                            <div class="bloc_with_ancres <?php if($bloc_actif == 'bloc-mandat') echo 'open  open-sub-menu-ancres'; else echo 'close'; ?>" data-open="bloc-mandat">
                                <a href="?bloc=bloc-mandat" class="titre_bloc_with_ancres button btn-accent"><span class="numero_bulle">1</span> Mandat</a>
                                <div class="bloc_ancres">
                                    <a class="ancredouce hide_for_mandatrecherche_or_delegationmandat<?php echo' '.$class_for_type_mandat; ?>" href="#section_type_mandat">Type de mandat</a>
                                    <a class="ancredouce hide_for_mandatrecherche_or_delegationmandat<?php echo' '.$class_for_type_mandat; ?>" href="#section_proprietaire">Propriétaire</a>
                                    <a class="ancredouce hide_for_mandatrecherche_or_delegationmandat<?php echo' '.$class_for_type_mandat; ?>" href="#section_adresse_bien">Adresse du bien</a>
                                    <a class="ancredouce hide_for_mandatrecherche_or_delegationmandat<?php echo' '.$class_for_type_mandat; ?>" href="#section_type_bien">Type de bien</a>
                                    <a class="ancredouce hide_for_mandatrecherche_or_delegationmandat<?php echo' '.$class_for_type_mandat; ?>" href="#section_prix">Prix</a>    
                                    <a class="ancredouce hide_for_mandatrecherche_or_delegationmandat<?php echo' '.$class_for_type_mandat; ?>" href="#section_honoraires">Honoraires</a>    
                                    <a class="ancredouce hide_for_mandatrecherche_or_delegationmandat<?php echo' '.$class_for_type_mandat; ?>" href="#section_documents_info_diagnostics">Diagnostics</a>     
                                    <a class="ancredouce hide_for_mandatrecherche_or_delegationmandat<?php echo' '.$class_for_type_mandat; ?>" href="#section_documents_conditions_particulieres">Conditions particulières</a>
                                    <a class="ancredouce hide_for_mandatrecherche_or_delegationmandat<?php echo' '.$class_for_type_mandat; ?>" href="#section_documents_annexes">Documents annexes</a>                                    
                                </div>
                            </div>
                            <div class="bloc_with_ancres <?php if($bloc_actif == 'bloc-annonce') echo 'open open-sub-menu-ancres'; else echo 'close'; ?> hide_for_mandatrecherche_or_delegationmandat<?php echo' '.$class_for_type_mandat; ?>" data-open="bloc-annonce">
                                <a href="?bloc=bloc-annonce" class="titre_bloc_with_ancres button btn-accent"><span class="numero_bulle">2</span> Annonce</a>
                                <div class="bloc_ancres">
                                    <a href="?bloc=bloc-annonce&subbloc=detailbien" class="titre_bloc_with_sub_ancres <?php if($sub_bloc_actif == 'detailbien') echo 'open'; else echo 'close'; ?>" data-open="detailbien">Détails du bien</a>
                                    <div class="bloc_sub_ancres">
                                        <a class="ancredouce" href="#section_type_bien_annonce">Type de bien</a>
                                        <a class="ancredouce" href="#section_surfaces">Surfaces</a>    
                                        <a class="ancredouce" href="#section_pieces_prestations">Pièces/Prestations</a>                
                                        <a class="ancredouce" href="#section_documents_copropriete">Copropriété</a>
                                        <a class="ancredouce" href="#section_documents_chauffage">Chauffage</a> 
                                        <a class="ancredouce" href="#section_documents_historique_logement">Historique du logement</a>
                                        <a class="ancredouce" href="#section_documents_disponibilite">Disponibilités</a>                
                                        <a class="ancredouce" href="#section_documents_taxe_fonciere">Taxe foncière</a>
                                        <a class="ancredouce" href="#section_documents_info_energetiques">Informations énergétiques</a>
                                        <a class="ancredouce" href="#section_documents_prix_annonce">Prix</a>                
                                        <a class="ancredouce" href="#section_documents_description">Description</a>                
                                        <a class="ancredouce" href="#section_documents_criteres_notation">Critères de notation</a> 
                                        <a class="ancredouce" href="#section_documents_contact_commercial">Contact commercial</a>
                                        <a class="ancredouce" href="#section_documents_medias">Médias</a>     <!--            
                                        <a class="ancredouce" href="#section_documents_conditions_particulieres">Conditions particulières</a>
                                        <a class="ancredouce" href="#section_documents_moyen_diffusion">Moyen de diffusion des annonces commerciales</a>
                                        <a class="ancredouce" href="#section_documents_actions_particulieres">Actions particulières</a>
                                        <a class="ancredouce" href="#section_documents_modalites_cr">Modalités et périodicité des comptes rendus</a> -->
                                    </div>    
                                </div>
                                <div class="bloc_ancres">
                                    <a href="?bloc=bloc-annonce&subbloc=region" class="titre_bloc_with_sub_ancres <?php if($sub_bloc_actif == 'region') echo 'open'; else echo 'close'; ?>" data-open="region">Localité du bien</a>
                                    <div class="bloc_sub_ancres">
                                        <a class="ancredouce" href="#section_lieu_localite">Région</a>                      
                                        <a class="ancredouce hide" href="#section_lieu_hostpot">Hotspot</a>                
                                    </div>
                                </div>
                                <div class="bloc_ancres">
                                    <a href="?bloc=bloc-annonce&subbloc=contact" class="titre_bloc_with_sub_ancres <?php if($sub_bloc_actif == 'contact') echo 'open'; else echo 'close'; ?>" data-open="contact">Contact</a>
                                    <div class="bloc_sub_ancres">
                                        <a class="ancredouce" href="#section_adresse_bien_annonce">Adresse du bien</a>
                                        <a class="ancredouce" href="#section_contact_proprietaire">Contact du propriétaire</a>
                                        <a class="ancredouce" href="#section_contact_visite">Contact de visite</a>
                                    </div>
                                </div>
                                <!-- <div class="bloc_ancres">
                                    <p>Diffusion supports</p>
                                </div>
                                <div class="bloc_ancres">
                                    <p>Statisques</p>
                                </div> -->
                            </div>
                            <div class="bloc_with_ancres <?php if($bloc_actif == 'bloc-vente') echo 'open  open-sub-menu-ancres'; else echo 'close'; ?> hide_for_delegationmandat" data-open="bloc-vente">
                                <a href="?bloc=bloc-vente" class="titre_bloc_with_ancres button btn-accent"><span class="numero_bulle">3</span> Vente</a>
                                <div class="bloc_ancres">
                                    <a class="ancredouce" href="#section_contact_acheteur">Contact de l'acheteur</a>
                                    <a class="ancredouce" href="#section_notaire_acheteur">Notaire de l'acheteur</a>
                                    <a class="ancredouce" href="#section_conditions_supensives">Conditions suspensives</a>
                                    <a class="ancredouce" href="#section_date_signature">Date de signature</a>    
                                    <a class="ancredouce" href="#section_notes_internes">Notes internes</a>
                                    <?php if(get_query_var('page')) : 
                                        $post_id = get_query_var('page'); ?>
                                        <div id="declare_vente_par_un_tier">
                                            <a href="#" class="lien_vendupartiers open_popin" data-openpopin="vendupartiers-<?php echo $post_id; ?>" data-bid="<?php echo $post_id; ?>">Mandat vendu par un tiers</a>
                                            <div id="vendupartiers-<?php echo $post_id; ?>" class="popin-layer close">
                                                <div class="popin vendupartiers">
                                                    <p class="titre">Attention vous êtes sur le point de supprimer ce mandat</p>
                                                    <p>Confirmez-vous sa vente par un tiers ?</p>
                                                    <br>
                                                    <a href='#' class='button btn-primary close_popin annuler' data-closepopin='vendupartiers-<?php echo $post_id; ?>'>Annuler</a>
                                                    <a href='#' class='button btn-primary confirmer' data-closepopin='vendupartiers-<?php echo $post_id; ?>' data-mandatid="<?php echo $post_id; ?>" data-goto="<?php echo wami_get_page_link("tableau-de-bord"); ?>">Confirmer</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php // if(get_query_var('page')) ?>
                                <a href='#diffusion-annonce' class='button btn-primary open_popin hide_for_mandatrecherche_or_delegationmandat<?php echo' '.$class_for_type_mandat; ?>' data-openpopin='diffusion-annonce'>Diffusion de votre annonce</a>                   
                        </div>

                        <div class="grid-col col_size-9 tablet_size-9 mobile_size-12 annonce-add"> 
                            <section class="section-form-part bloc-mandat <?php if($bloc_actif == 'bloc-mandat') echo 'open'; else echo 'close'; ?>">
                                <?php get_template_part('page_part/mo', 'bien-form-section1'); ?>
                            </section>
                            <section class="section-form-part bloc-annonce <?php if($bloc_actif == 'bloc-annonce') echo 'open'; else echo 'close'; ?>">
                                <?php get_template_part('page_part/mo', 'bien-form-section2'); ?>
                            </section>
                            <section class="section-form-part bloc-vente <?php if($bloc_actif == 'bloc-vente') echo 'open'; else echo 'close'; ?>">
                                <?php get_template_part('page_part/mo', 'bien-form-section3'); ?>
                            </section>
                        </div>


                        <?php //if(get_query_var('page')) : ?>
                            <?php get_template_part('page_part/mo', 'bien-publish-section'); ?>
                        <?php //endif; ?>

                    <?php endif; ?>                   
                    
                </div>
            </div>

    <?php acf_enqueue_uploader(); ?>    
    <?php get_footer(); ?>

<?php else : ?>

    <?php wp_redirect( home_url() ); exit; ?>

<?php endif; ?>