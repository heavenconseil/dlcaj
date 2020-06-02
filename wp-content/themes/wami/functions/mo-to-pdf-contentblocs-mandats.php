<?php 
function get_pdf_consommateurs($mandat_id, $titre, $mandat_type=false){
    $limit = $mandat_type=='mandat_recherche' ? 3 : 5;
    ?>
    <div class="entete_bloc">
        <p class="bold"><?= $titre; ?></p>
        <?php 
        if(have_rows('proprietaires', $mandat_id)) : ?>
            <?php while(have_rows('proprietaires', $mandat_id)): the_row(); 
                if(get_row_index() < $limit) : 
                    $nom = get_sub_field('proprietaire_societe') ? get_sub_field('proprietaire_raison_social').' représentée par : '.get_sub_field('proprietaire_société_attention_de') : get_sub_field('proprietaire_prenom').' '.get_sub_field('proprietaire_nom'); ?>            
                    <p class="ligne_simple">
                        <?php echo $nom; ?>, demeurant 
                        <?php echo get_sub_field('proprietaire_adresse') ? get_sub_field('proprietaire_adresse').' ' : "";
                        echo get_sub_field('proprietaire_cp') ? get_sub_field('proprietaire_cp').' ' : "";
                        echo get_sub_field('proprietaire_ville') ? get_sub_field('proprietaire_ville').' ' : "";
                        echo get_sub_field('proprietaire_pays') ? get_sub_field('proprietaire_pays') : ""; ?>
                    </p>
                    <p class="ligne_simple">E-mail&nbsp;: <?php echo get_sub_field('proprietaire_email') ? get_sub_field('proprietaire_email') : ""; ?></p>
                    <p class="ligne_simple">Téléphone&nbsp;: <?php echo get_sub_field('proprietaire_telephone') ? return_tel_french_format(get_sub_field('proprietaire_telephone')) : ""; ?></p>
                    <p class="mention small margin-b">(Vous avez la possibilité de vous inscrire sur www.bloctel.gouv.fr pour vous opposer à tout démarchage téléphonique conformément à l'article L223-1 du Code de la consommation)</p>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </div><?php
}


function get_pdf_consommateurs_delegation($mandat_id){
    ?><div class="entete_bloc">
        <p>Entre les soussignés&nbsp;:</p>
    </div>
    <div class="mandataire">
        <p><span class="bold">Le Mandataire,</span> titulaire du mandat d'origine</p>
        <p class="ligne_simple">SAS SEVEN & SEVEN DE LA COUR AU JARDIN</p>
        <p class="ligne_simple">RCP VD7.000.001 / 20372</p>
        <p class="ligne_simple">RCS Paris 811 493 162</p>
        <p class="ligne_simple">Carte CPI 7501 2017 000 015964</p>
        <p class="ligne_simple">157 rue du Faubourg Saint Antoine 75011 PARIS</p>
        <table>
            <tr>
                <td class="left">E-mail&nbsp;: contact@delacouraujardin.com</td>
                <td>Téléphone*&nbsp;: </td>
            </tr>
        </table>
    </div>
    <p class="bold">ET</p>
    <div class="delegataire">
        <p><span class="bold">Le Délégataire,</span> bénéficiaire de la délégation&nbsp;: <br>
        <?php echo get_field('mandat_delegation_nom_prenom_du_beneficiaire', $mandat_id) ? get_field('mandat_delegation_nom_prenom_du_beneficiaire', $mandat_id) : ''; ?>
        </p>
        <table>
            <tr>
                <td class="left">E-mail&nbsp;: <?php echo get_field('mandat_delegation_email_du_beneficiaire', $mandat_id) ? get_field('mandat_delegation_email_du_beneficiaire', $mandat_id) : ''; ?></td>
                <td>Téléphone*&nbsp;: <?php echo get_field('mandat_delegation_tel_du_beneficiaire', $mandat_id) ? get_field('mandat_delegation_tel_du_beneficiaire', $mandat_id) : ''; ?></td>
            </tr>
        </table>
    </div>
    <div class="entete_bloc">
        <p class="mention">*vous avez la possibilité de vous inscrire sur www.bloctel.gouv.fr pour vous opposer à tout démarchage téléphonique conformément à l’article L121-34 du Code de la consommation</p>
    </div>
    <p>Tous deux régulièrement titulaire d’une carte professionnelle conformément à la loi n° 70/9 du 2 janvier 1970</p><?php
}


function get_pdf_intro_mandat(){
    ?><p>agissant conjointement et solidairement en QUALITÉ DE SEULS PROPRIÉTAIRES, vous mandatons par la présente afin de rechercher un acquéreur et faire toutes les démarches en vue de vendre les biens et droits, ci-dessous désignés, nous engageant à produire toutes justifications de propriété.</p>
    <?php
}
function get_pdf_intro_mandat_recherche(){
    ?><p>Agissant en qualité d’acquéreurs éventuels, vous mandatons afin de nous présenter et de nous faire visiter tous biens répondants à nos critères de choix. Lorsque nous arrêterons notre choix, pour négocier les conditions générales de la vente, pour rédiger l’accord des parties, aux conditions suivantes&nbsp;:</p><?php
}


function get_pdf_situation_designation($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — SITUATION — DÉSIGNATION</span></div> 
        <?php  $type_bien = get_field('bien_type', $mandat_id) ? ucfirst(get_field('bien_type', $mandat_id)) : "Bien";
        echo'<p>'.$type_bien.' sis à '.ucfirst(get_field('bien_adresse_ville', $mandat_id)).' : '.get_field("bien_adresse_1", $mandat_id).' '.get_field("bien_adresse_cp", $mandat_id).' '.ucfirst(get_field('bien_adresse_pays', $mandat_id)).'</p>'; ?>
        <p>Désignation&nbsp;: <?php echo get_field('designation_du_bien_mandat', $mandat_id) ? substr(get_field('designation_du_bien_mandat', $mandat_id), 0, 2440) : ""; ?></p>  
        <p>Dont nous sommes devenus propriétaires par acte chez Maître&nbsp;: <?php echo get_field('bien_acquis_par_acte_chez_maitre', $mandat_id) ? get_field('bien_acquis_par_acte_chez_maitre', $mandat_id) : ""; ?></p>
        <p>Le bien est vendu&nbsp;:
            <?php echo get_field('bien_type_vente', $mandat_id)=='bien_vendu_libre_de_toute_occupation' ? 'libre de toute occupation' : ""; ?>
            <?php echo get_field('bien_type_vente', $mandat_id)=='bien_loue_selon_le_contrat_de_bail_et_les_conditions_ci-annexes' ? 'Loué selon le contrat de bail et les conditions ci-annexés' : ""; ?>
            <?php echo (get_field('bien_loue_date_du_bail', $mandat_id) && get_field('bien_type_vente', $mandat_id)=='bien_loue_selon_le_contrat_de_bail_et_les_conditions_ci-annexes') ? '<span>. Date du bail initial&nbsp;: '.get_field('bien_loue_date_du_bail', $mandat_id).'</span>' : ""; ?>
        </p>
    </div><?php 
}


function get_pdf_prix($number, $mandat_id){ 
    $prix_mandat = get_field('bien_prix_mandat', $mandat_id) ? get_field('bien_prix_mandat', $mandat_id) : ( get_field('bien_prix_et_honoraires', $mandat_id) ? get_field('bien_prix_et_honoraires', $mandat_id) : "" );
    $prix_mandat = number_format($prix_mandat, 0, '', ' ');
    ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — PRIX</span></div>
        <p>Les biens et droits, ci-avant désignés devront être présentés, sauf accord ultérieur, au prix de <?php echo $prix_mandat.' €'; ?>, honoraires du mandataire inclus, payable comptant le jour de la signature de l’acte authentique, tant à l’aide de prêts que de fonds propres de l’acquéreur.</p>
    </div><?php 
}  
 

function get_pdf_honoraires($number, $mandat_id){ ?>
    <div class="bloc"> 
        <div class="titre_3"><span><?= $number; ?> — HONORAIRES</span></div>
        <p>Vos honoraires (en Euros TTC) seront de&nbsp;:
            <?php echo get_field('bien_honoraires_montant', $mandat_id) ? number_format(get_field('bien_honoraires_montant', $mandat_id), 0, '', ' ').' €' : ""; ?>
        </p>
        <?php $charge_de = get_field("bien_honoraires_charge_de", $mandat_id)=="charge_acquereur" ? "de l’acquéreur" : "du vendeur"; ?>
        <p>Ils seront à la charge <?= $charge_de; ?> et seront exigibles le jour où l’opération sera effectivement conclue et constatée dans un acte écrit, signé des deux parties, conformément aux articles 73 et 74 du décret n° 72-678 du 20 juillet 1972.</p> 
    </div><?php 
} 


function get_pdf_conditions_particulieres($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — CONDITIONS PARTICULIÈRES</span></div>
        <?php echo get_field('bien_notes_conditions_particulieres', $mandat_id) ? '<p>'.get_field('bien_notes_conditions_particulieres', $mandat_id).'</p>' : '<p>Aucune condition particulière.</p>'; ?>  
    </div><?php 
} 


function get_pdf_supperficie_loi_carrez($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> —  SUPERFICIE PRIVATIVE « LOI CARREZ » (SI COPROPRIÉTÉ), EN MÈTRES CARRÉS (m2)</span></div>
        <?php echo get_field('bien_superficie_carrez', $mandat_id) ? '<p>'.get_field('bien_superficie_carrez', $mandat_id).' m²</p>' : '<p></p>'; ?>  
    </div><?php 
} 


function get_pdf_dossier_diagnostique_technique($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — DOSSIER DE DIAGNOSTIC TECHNIQUE</span></div>
            <p>Date des diagnostics&nbsp;:            
                <?php echo get_field('bien_date_diagnostics', $mandat_id) ? get_field('bien_date_diagnostics', $mandat_id) : ""; ?>
            </p>
            <?php if( get_field('bien_diagnostics_dossier_complet', $mandat_id) ) { ?>
                <p class="ligne_simple">Le dossier est complet.</p>
            <?php } else { ?>
                <table>
                    <tr>
                        <td class="left">                     
                            <p class="ligne_simple">
                                <label>Dossier complet</label><input type="checkbox"<?php echo get_field('bien_diagnostics_dossier_complet', $mandat_id) ? ' checked="checked"' : ""; ?> />                    
                            </p>
                            <p class="ligne_simple">
                                <label>État amiante</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_etat_amiante', $mandat_id)) ? ' checked="checked"' : ""; ?> />                    
                            </p>
                            <p class="ligne_simple">
                                <label>Constat de risque d’exposition au plomb</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_rique_au_plomb', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>État parasitaire</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_etat_parasitaire', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>Constat des risques miniers, naturels et technologiques</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_risques_miniers', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                        </td>
                        <td>  
                            <p class="ligne_simple">
                                <label>Diagnostic de performance énergétique</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_performance_energetique', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>État de l’installation intérieure d’électricité</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_installation_elec_interieure', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>État de l’installation intérieure de gaz</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_installation_gaz_interieure', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>Contrôle assainissement non collectif</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_controle_assainissement_non_collectif', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>
                            <p class="ligne_simple">
                                <label>Bornage</label><input type="checkbox"<?php echo (get_field('bien_diagnostics_dossier_complet', $mandat_id) || get_field('bien_diagnostics_bornage', $mandat_id)) ? ' checked="checked"' : ""; ?> />
                            </p>                      
                        </td>
                    </tr>
                </table>
            <?php }; ?>
            <?php echo get_field('bien_consommation_non_soumis_dpe', $mandat_id) ? '<p>Bien non soumis aux DPE.</p>' : "<p></p>"; ?>
    </div><?php 
}


function get_pdf_moyens_de_diffusion_des_annonces_commerciales($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — MOYENS DE DIFFUSION DES ANNONCES COMMERCIALES</span></div>
        <p><?php echo get_field('bien_notes_moyen_diffusion_annonces_commerciales', $mandat_id) ? get_field('bien_notes_moyen_diffusion_annonces_commerciales', $mandat_id) : 'Le site de l’agence, toute plateforme web partenaire de De La Cour Au Jardin (Seloger, Belles Demeures, Le Figaro Immobilier, LeBonCoin, Green Acres pour l’international…) et les réseaux sociaux De La Cour Au Jardin.'; ?></p>        
    </div><?php 
}


function get_pdf_actions_particulieres($number, $mandat_id, $mandat_type=false){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — ACTIONS PARTICULIÈRES</span></div>
            <?php if($mandat_type == "mandat_recherche") : 
                if(get_field('bien_notes_actions_particulieres', $mandat_id)) :
                    echo '<p>'.get_field('bien_notes_actions_particulieres', $mandat_id).'</p>'; 
                else : ?>
                    <p>Dans le cadre de la mission, De La Cour Au Jardin sera amené à&nbsp;:
                    <br>-   Rechercher ce bien, sur internet, au sein de notre réseau d’agences immobilières partenaires et à travers une recherche ciblée sur le terrain
                    <br>-   Pré-visiter les biens pour confirmer la conformité avec les caractéristiques énoncées en paragraphe 1
                    <br>-   Valider les éléments constitutifs du dossier de vente (auprès de l’agence ou du propriétaire en direct
                    <br>-   Négocier le prix de vente</p>
                    <p>Cet accompagnement fait partie intégrante de la mission tout au long du processus d’achat (de l’offre d’achat jusqu’à l’acte authentique).</p>
                <?php endif;             
            else : ?>
                <p><?php echo get_field('bien_notes_actions_particulieres', $mandat_id) ? get_field('bien_notes_actions_particulieres', $mandat_id) : 'Aucune action particulière.'; ?></p>
            <?php endif; ?>    
    </div><?php 
}


function get_pdf_modalite_et_periodicite_de_compte_rendu($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — MODALITÉS ET PÉRIODICITÉ DE COMPTES-RENDUS</span></div>
            <p><?php echo get_field('bien_notes_modalite_cr', $mandat_id) ? get_field('bien_notes_modalite_cr', $mandat_id) : 'Comptes rendus bi-hebdomadaires à minima.'; ?></p>
    </div><?php 
}


function get_pdf_plus_value_et_tva($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — PLUS-VALUE ET T.V.A.</span></div>
            <p>Les parties reconnaissent avoir été informées des dispositions fiscales concernant les plus-values et déclarent agir en toute connaissance de cause. Si la vente est assujettie à la T.V.A. le prix ci-dessus stipulé s’entend T.V.A. incluse.</p>
    </div><?php 
}


function get_pdf_duree_du_mandat_et_obligations_du_mandant($number, $mandat_id, $type_mandat){ 
    $titre = ($type_mandat == 'mandat_recherche') ? 'DURÉE DU MANDAT' : 'DURÉE DU MANDAT ET OBLIGATIONS DU MANDANT';?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — <?php echo $titre; ?></span></div>
            <?php if($type_mandat == 'mandat_simple') : ?>
                <p>Ce mandat vous est consenti pour une durée de douze mois prenant effet ce jour, dont les trois premiers mois sont irrévocables. Au terme de cette période d’irrévocabilité, le mandat pourra être dénoncé à tout moment par chacune des parties, à charge pour celle qui entend y mettre fin d’en aviser l’autre partie quinze jours à l’avance par lettre recommandée avec avis de réception.</p>
                <p>Pendant la durée du mandat, nous nous engageons à ratifier la vente à tout preneur que vous nous présenterez, acceptant les prix et conditions des présentes, et à libérer les lieux pour le jour de l’acte authentique.</p>
                <p>Si nous présentons les biens à vendre directement ou par l’intermédiaire d’un autre mandataire, nous le ferons au prix des présentes, de façon à ne pas vous gêner dans votre mission.</p>
                <p>Nous nous interdisons de vendre sans votre concours, y compris par un autre intermédiaire, à un acquéreur qui nous aurait été présenté par vous, pendant la durée du mandat et un an après son expiration.</p>
                <p class="bold">EN TOUTE CONFORMITÉ AVEC LE CODE CIVIL ET LES PRESCRIPTIONS D'ORDRE PUBLIC DE L'ARTICLE 78 DU DÉCRET N°72-678 DU 20/07/1972, VOTRE RÉMUNÉRATION SERA DUE EN CAS DE VENTE À UN ACQUÉREUR AYANT EU CONNAISSANCE DE LA VENTE DU BIEN PAR VOTRE INTERMÉDIAIRE, MÊME SI L'OPÉRATION EST CONCLUE SANS VOS SOINS.</p>
                <p>En cas de vente, pendant la durée du présent mandat et un an après son expiration, nous devrons obtenir de notre acquéreur l’assurance écrite que les biens ne lui ont pas été présentés par vous.</p>
                <p>Si nous vendons après l’expiration de ce mandat, comme nous en gardons le droit, à toute personne non présentée par vous, nous nous obligeons à vous avertir immédiatement par lettre recommandée, en vous précisant les coordonnées des acquéreurs, du notaire chargé d’authentifier la vente, et de l’agence éventuellement intervenue, ainsi que le prix de vente final, ce pendant deux ans.</p>
            <?php elseif($type_mandat == 'mandat_exclusif') : ?>
                <p>Ce mandat vous est consenti pour une durée de douze mois prenant effet ce jour, dont les trois premiers mois sont irrévocables. Au terme de cette période d’irrévocabilité, le mandat pourra être dénoncé à tout moment par chacune des parties, à charge pour celle qui entend y mettre fin d’en aviser l’autre partie quinze jours à l’avance par lettre recommandée avec avis de réception.</p>
            <?php elseif($type_mandat == 'mandat_exigence') : ?>
                <p>Ce mandat vous est consenti pour une durée d’un mois prenant effet ce jour, dont les trois premières semaines sont irrévocables. Il sera dénonçable par LRAR au plus tard au terme de la quatrième semaine (à la date de la première présentation par La Poste). Ce mandat sera reconduit tacitement dans les mêmes conditions pour une durée d’un mois et ce 2 fois jusqu’au terme des 3 premiers mois.</p>
            <?php elseif($type_mandat == 'mandat_recherche') : ?>
                <p>Ce mandat vous est consenti pour une durée de trois mois prenant effet ce jour. Au terme de cette période de trois mois, il pourra être dénoncé à tout moment, avec un préavis de quinze jours par lettre recommandée avec accusé de réception.</p>
            <?php endif; ?>
    </div><?php 
}


function get_pdf_exclusivite_et_obligation_du_mandant($number, $mandat_id, $type_mandat){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — EXCLUSIVITÉ ET OBLIGATIONS DU MANDANT</span></div>
            <p>Le présent mandat vous est consenti en exclusivité pour toute la durée du mandat. En conséquence, nous nous interdisons, pendant le cours du présent mandat, de négocier directement ou indirectement la vente des biens, ci-avant désignés, y compris par un autre intermédiaire ou par un office notarial, et nous nous engageons à diriger vers vous toutes les demandes qui nous seraient adressées personnellement.</p>
        <?php if($type_mandat == 'mandat_exclusif') : ?>            
            <p>Passé un délai de trois mois à compter de sa signature, le mandat contenant un telle clause peut être dénoncé à tout moment par chacune des parties, à charge pour celle qui entend y mettre fin d’en aviser l’autre partie quinze jours au moins à l’avance par lettre recommandée avec demande d’avis de réception.</p>
            <p class="bold">Mention expresse&nbsp;: en toute conformité avec le code civil et les prescriptions d’ordre public de l’article 78 du décret n° 72-678 du 20 juillet 1972, la rémunération du mandataire sera due même si l’opération est conclue sans les soins du mandataire.</p>
            <p>Pendant la durée du mandat, nous nous engageons à ratifier la vente à tout preneur que vous nous présenterez, acceptant les prix et conditions des présentes, et à libérer les lieux pour le jour de l’acte authentique.
            <br>Pendant une période de deux ans après expiration du mandat, nous nous interdisons de vendre sans votre concours, y compris par un autre intermédiaire, à un acquéreur à qui auriez présenté le bien pendant la période de validité du mandat.
            <br>Le mandant s’engage à faire réaliser et à fournir sans délai au mandataire l’ensemble des diagnostics obligatoires.
            <br>Pendant une période de deux ans après expiration du mandat, nous nous interdisons de vendre sans votre concours, y compris par un autre intermédiaire, à un acquéreur qui nous aurait été présenté par vous pendant la période de validité du mandat.</p>
        </div>
    </div>
    <div class="page">
        <div class="bloc">
            <p>En cas de vente, pendant deux ans après l’expiration du présent mandat, nous devrons obtenir de notre acquéreur l’assurance écrite que les biens ne lui ont pas été présentés par vous. Si nous vendons après l’expiration de ce mandat, comme nous en gardons le droit, à toute personne non présentée par vous, nous nous obligeons à vous avertir immédiatement par lettre recommandée, en vous précisant les coordonnées des acquéreurs, du notaire chargé d’authentifier la vente, et de l’agence éventuellement intervenue, ainsi que le prix de vente final, ce pendant deux ans.</p>
            <p class="bold">Clauses pénales&nbsp;: en cas de non respect de la clause ci-dessus, le mandant versera une indemnité compensatrice forfaitaire correspondant à la moitié des honoraires convenus.
            <br>Par ailleurs, en cas de vente à un acquéreur ayant eu connaissance de la vente du bien par l’intermédiaire de l’agence, ou de refus de vendre à un acquéreur qui aurait été présenté par l’agence, ou en cas d’infraction à une clause d’exclusivité, le mandat versera une indemnité compensatrice forfaitaire égale aux honoraires prévus au présent mandat.</p>
        <?php elseif($type_mandat == 'mandat_exigence') : ?>
            <p class="bold">Mention expresse&nbsp;: en toute conformité avec le code civil et les prescriptions d’ordre public de l’article 78 du décret n° 72-678 du 20 juillet 1972, la rémunération du mandataire sera due même si l’opération est conclue sans les soins du mandataire.</p>
            <p>Pendant la durée du mandat, nous nous engageons à ratifier la vente à tout preneur que vous nous présenterez, acceptant les prix et conditions des présentes, et à libérer les lieux pour le jour de l’acte authentique.</p>
            <p>Pendant une période d’un an après expiration du mandat, nous nous interdisons de vendre sans votre concours, y compris par un autre intermédiaire, à un acquéreur que vous nous auriez présenté le bien pendant la période de validité du mandat.</p>
            <p>Le mandant s’engage à faire réaliser et à fournir sans délai au mandataire l’ensemble des diagnostics obligatoires et les éléments constitutifs du dossier de vente.</p>
            <p>En cas de vente, pendant un an après l’expiration du présent mandat, nous devrons obtenir de notre acquéreur l’assurance écrite que les biens ne lui ont pas été présentés par vous.</p>
            <p>Si nous vendons après l’expiration de ce mandat, comme nous en gardons le droit, à toute personne non présentée par vous, nous nous obligeons à vous avertir immédiatement par lettre recommandée, en vous précisant les coordonnées des acquéreurs, du notaire chargé d’authentifier la vente, et de l’agence éventuellement intervenue, ainsi que le prix de vente final, ce pendant un an.</p>
            <p class="bold">Clauses pénales&nbsp;: en cas de non respect de la clause ci-dessus, le mandant versera une indemnité compensatrice forfaitaire correspondant à la moitié des honoraires convenus.
            <br>Par ailleurs, en cas de vente à un acquéreur ayant eu connaissance de la vente du bien par l’intermédiaire de l’agence, ou de refus de vendre à un acquéreur qui aurait été présenté par l’agence, ou en cas d’infraction à une clause d’exclusivité, le mandat versera une indemnité compensatrice forfaitaire égale aux honoraires prévus au présent mandat.</p>
        <?php endif; ?>
    </div><?php 
}


function get_pdf_declaration_du_mandant($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — DÉCLARATION DU MANDANT</span></div>
            <p>En considération du mandat présentement accordé, le mandant&nbsp;:</p>
            <p>- Déclare avoir la capacité pleine et entière de disposer des biens objets du présent mandat. Il déclare en outre et sous son entière responsabilité, ne faire l’objet d’aucune mesure de protection de la personne (curatelle, tutelle...) ni d’aucune procédure collective (redressement ou liquidation judiciaire).
            <br>- Déclare que les biens, objets du présent mandat ne font l’objet d’aucune procédure de saisie immobilière. 
            <br>- Déclare ne pas avoir consenti, par ailleurs, de mandat de vente non expiré ou dénoncé. 
            <br>- S’interdit de signer tout autre mandat ultérieurement via un autre intermédiaire sans avoir préalablement dénoncé le présent mandat.</p>
    </div><?php 
}


function get_pdf_pouvoir_du_mandataire($number, $mandat_id, $type_mandat=false){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — POUVOIRS DU MANDATAIRE</span></div> 
        <p>En considération du mandat présentement accordé, tous pouvoirs vous sont donnés pour mener à bien votre mission. Vous pourrez notamment&nbsp;:</p>
        <p>1) Faire tout ce qui vous sera utile pour parvenir à la vente, et notamment toute publicité sur tous supports à votre convenance, y compris sur fichiers informatiques librement accessibles (internet,...) mais à vos frais seulement ; apposer un panneau de mise en vente à l’endroit que vous jugerez le plus approprié ; publier toute photographie, étant entendu que nous sommes seuls propriétaires du droit à l’image de notre bien. Le mandant pourra exercer son droit d’accès et de rectification conformément à l’article 27 de la loi du 6 janvier 1978. Le bien ne pourra faire l’objet d’une campagne publicitaire publique qu’à compter de la transmission au mandataire du DPE.</p>
        <p>2) Réclamer toutes les pièces utiles auprès de toutes personnes privées ou publiques, notamment le certificat d’urbanisme.</p>
        <p>3) Indiquer, présenter et faire visiter les biens à vendre à toutes personnes que vous jugerez utile. A cet effet, nous nous obligeons à vous assurer le moyen de visiter pendant le cours du présent mandat.</p>
        <p>4) Établir en notre nom tous actes sous seing privé (compromis en particulier), éventuellement assortis d’une demande de prêt, aux clauses et conditions nécessaires à l’accomplissement des présentes et recueillir la signature de l’acquéreur.</p>
        <p>5) Satisfaire, s’il y a lieu, à la déclaration d’intention d’aliéner, exigée par la loi. En cas d’exercice du droit de préemption, négocier avec l’organisme préempteur, bénéficiaire de ce droit à la condition de nous en avertir, étant entendu que nous gardons le droit d’accepter ou refuser le prix proposé par le préempteur, si ce prix est inférieur au prix demandé.</p>
        <p>6) Séquestre&nbsp;: en vue de garantir la bonne exécution des présentes et de leur suite, les fonds ou valeurs qu’il est d’usage de faire verser par l’acquéreur seront détenus par tout séquestre habilité à cet effet (l’agence De La Cour Au Jardin ne détenant pas de fonds).</p>
        <p>7) Application de l’article 46 de la loi n°65-557 du 10 juillet 1965 et du décret n°97-532 du 23 mai 1997 (vente d’un lot ou d’une fraction de lot, dite loi Carrez) : si nous ne vous fournissons pas ce document sous huitaine, nous vous autorisons à faire établir à nos frais, par un homme de l’art, une attestation mentionnant la superficie exacte de la partie privative des biens objet du présent mandat.</p>
    <?php if($type_mandat == 'mandat_exclusif') echo'</div>
    </div>
    <div class="page">
        <div class="bloc">' ?>  
        <p>8) Dossier de diagnostics techniques&nbsp;: le vendeur fera effectuer sans délai l’ensemble des constats, états et diagnostics obligatoires. Ce dossier devra être annexé à l’engagement des parties.</p>
        <p>9) Vous adjoindre ou substituer tout professionnel de votre choix pour l’accomplissement des présentes.</p>
        <p>10) Copropriété&nbsp;: le mandant autorise expressément le mandataire à demander au syndic, en son nom et à ses frais, communication et copie des documents devant être présentés ou fournis à l’acquéreur, notamment le carnet d’entretien de l’immeuble, le diagnostic technique, les diagnostics amiante, plomb et termites concernant les parties communes et l’état daté prévu par l’article 5 du décret modifié du 17 mars 1967. Cette autorisation ne concerne que les documents que le vendeur copropriétaire n’aurait pas déjà fournis au mandataire. Les documents ainsi obtenus sont réputés la propriété du mandant et lui seront restitués en fin de mission.</p>
        <p>11) Le mandataire informera le mandant, par LRAR ou par tout autre écrit remis contre récépissé ou émargement, dans les huit jours de l’opération, de l’accomplissement du mandat, en joignant le cas échéant une copie de la quittance ou du reçu délivré ; ce, conformément à l’art. 77 du décret n° 72- 678 du 20 juillet 1972.</p>
    </div><?php 
}


function get_pdf_droit_de_retractation($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — DROIT DE RÉTRACTATION</span></div>
            <p>Le consommateur (Propriétaire mandant) dispose d’un délai de quatorze jours pour exercer son droit de rétractation d’un contrat conclu à distance, à la suite d’un démarchage téléphonique ou hors établissement, sans avoir à motiver sa décision.
            <br>Toute clause par laquelle le consommateur abandonne son droit de rétractation est nulle.
            <br>Le délai court à compter du jour de la conclusion du contrat.</p>
            <p>Pour exercer le droit de rétractation, le consommateur nous fera part de sa décision de rétractation du présent contrat, au moyen d’une déclaration dénuée d’ambiguïté (par exemple, lettre par la poste, ou par courrier électronique).</p>
            <p>Si le mandant a demandé à ce que le mandataire commence ses prestations avant le délai de rétractation, il pourra néanmoins se rétracter pendant ce délai contractuel, sauf si le mandataire a pleinement exécuté sa mission.</p>
    </div><?php 
}

 
function get_pdf_mediation_des_litiges_de_la_consommation($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — MÉDIATION DES LITIGES DE LA CONSOMMATION</span></div>
        <p>Tout consommateur a le droit de recourir gratuitement à un médiateur de la consommation en vue de la résolution amiable du litige qui l’oppose à un professionnel.</p>
        <p>A cet effet, nous vous garantissons le recours effectif à un dispositif de médiation de la consommation, en vertu des Articles L611-1 et suivants du Code de la consommation, créés par l’ordonnance n°2016- 301 du 14 mars 2016.
        <br>Notre organisme de médiation est AME Conso - 197 boulevard Saint-Germain 75007 Paris, auprès duquel nous sommes affiliés.</p>  
    </div><?php 
}


function get_pdf_informatique_liberte_rgpd($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — INFORMATIQUE ET LIBERTÉS, RGPD</span></div>
        <p>Les informations recueillies par le mandataire en considération du présent contrat font l’objet d’un traitement informatique, nécessaire à l’exécution de sa mission.
        <br>Les informations concernant le bien objet du présent contrat sont susceptibles d’être transmises à des partenaires commerciaux, sites internet notamment.</p>
        <p>En tant que professionnel de l’immobilier et conformément au règlement européen 2016/679, nous informons nos clients consommateurs que nous collectons et traitons des données personnelles nécessaires pour l’accomplissement de notre mission.</p>
        <p>Ces données pourront être transmises au notaire, au co-contractant, aux organismes financiers éventuellement chargés du financement, ainsi qu’aux administrations concernées (mairie pour DPU notamment…).</p>
        <p>Elles seront conservées pendant toute la durée de la relation commerciale et ensuite pendant une durée de cinq ans conformément à l’article L 561- 12 du Code monétaire et financier, et pendant dix ans en ce qui concerne les noms et adresses des mandants en vertu de l’article 53 du décret n° 72-78 du 20/07/1972.</p>
        <p>Nos clients consommateurs bénéficient d’un droit d’accès et de rectification de leurs données à caractère personnel traitées, ils peuvent demander leur effacement et leur portabilité, ou exercer leur droit à opposition dans les conditions prévues par le règlement européen 2016/679. </p>        
    </div><?php 
}

function get_pdf_informatique_liberte_rgpd_suite(){ ?>
    <div class="bloc">
        <p class="bold">DEMANDE EXPRESSE D’EXECUTION ANTICIPEE&nbsp;: Conformément à l’article L. 221-25 du Code de la consommation&nbsp;: le mandant souhaite expressément que le mandataire commence ses prestations avant l’expiration du délai de rétractation.</p>
        <p>Le mandant reconnaît expressément avoir pris connaissance, préalablement à la signature des présentes, de l’intégralité des caractéristiques des services définis au présent mandat, ainsi que toutes les informations prévues aux articles L 111-1 et suivants du Code de la consommation, ainsi que du traitement des données personnelles (RGPD) par le mandataire, lors de le remise du DIP (document d'informations précontractuelles).</p> 
    </div> <?php 
}


function get_pdf_mandat_simple($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — MANDAT SIMPLE</span></div>
        <p>Le présent mandat vous est consenti sans exclusivité, conformément au § "Durée du mandat et obligations du mandant" ci-dessus. En conséquence, nous gardons toute liberté de vendre par nous-mêmes ou par l’intermédiaire d’une autre agence, sauf à un acquéreur qui nous aurait été présenté par vous.</p>
    </div><?php 
} 


function get_pdf_vente_sans_votre_concours($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — VENTE SANS VOTRE CONCOURS</span></div>
        <p>Dans les cas autorisés aux présentes de vente sans votre concours, nous nous engageons à vous informer immédiatement par lettre recommandée avec accusé de réception, en vous précisant les noms et adresses de l’acquéreur, du notaire chargé de l’acte authentique et de l’agence éventuellement intervenue, ainsi que le prix de vente final, ce, pendant la durée du présent mandat et deux ans après son expiration.</p>
        <p>En cas de non respect de la clause ci-dessus, nous vous verserons une indemnité compensatrice forfaitaire correspondant à la moitié des honoraires convenus.
        <br>Par ailleurs, en cas de vente à un acquéreur ayant eu connaissance de la vente du bien par votre intermédiaire, ou de refus de vendre à un acquéreur qui nous aurait été présenté par vous, nous vous verserons une indemnité compensatrice forfaitaire égale aux honoraires prévus au présent mandat.
        <br>En cas de présentation du bien à vendre à un prix différent, en contradiction avec le paragraphe XIV, et si cette présentation est faite à un prix inférieur à celui qui est prévu au présent mandat, le mandant versera une indemnité compensatrice forfaitaire correspondant à la moitié des honoraires convenus, le mandataire subissant un préjudice par la perte d’une chance de vendre le bien.</p>
    </div><?php 
}


function get_pdf_caracteristique_du_bien($number, $mandat_id){ ?>
    <div class="bloc">
        <div class="titre_3"><span><?php echo $number; ?> — CARACTERISTIQUES DU BIEN RECHERCHE</span></div><br>
            <?php echo get_field('mandat_recherche_caracteristique_du_bien', $mandat_id) ? '<p>'.get_field('mandat_recherche_caracteristique_du_bien', $mandat_id).'</p>' : '<div class="espace_blanc"></div>'; ?> 
            <p>Prix Maximum souhaité&nbsp;: <?php echo get_field('mandat_recherche_prix_maximum_souhaite', $mandat_id) ? number_format(get_field('mandat_recherche_prix_maximum_souhaite', $mandat_id), 0, '', ' ').' Euros' : ''; ?></p>
    </div><?php 
}
 

function get_pdf_honoraires_mandat_recherche($number, $mandat_id){ 
    $honoraires = false;
    $format_h   = get_field('mandat_recherche_format_des_honoraires', $mandat_id);
    if( $format_h ) {
        if($format_h == 'euros') $honoraires = get_field('mandat_recherche_montant_des_honoraires_en_euros', $mandat_id) ? number_format(get_field('mandat_recherche_montant_des_honoraires_en_euros', $mandat_id), 0, '', ' ').'€.' : false;
        elseif($format_h == 'pourcentage') $honoraires = get_field('mandat_recherche_montant_des_honoraires', $mandat_id) ? get_field('mandat_recherche_montant_des_honoraires', $mandat_id).'% du prix net vendeur dudit bien.' : false;
    }  ?>
    <div class="bloc"> 
        <div class="titre_3"><span><?= $number; ?> — HONORAIRES TTC</span></div>
        <p>En cas d’achat d’un bien présenté par l’agence vos honoraires seront de&nbsp;: <?= $honoraires; ?></p>
            <p>Ils ne deviendront éligibles qu’après achat effectivement conclu, levée étant obligatoirement faite de toutes conditions suspensives, et seront à notre charge.</p>
    </div><?php 
} 


function get_pdf_exclusivite_du_mandat($number, $mandat_id){ ?>
    <div class="bloc"> 
        <div class="titre_3"><span><?= $number; ?> — EXCLUSIVITE DU MANDAT</span></div>
        <p>Le Mandant déclare ne pas avoir consenti, par ailleurs, d’autre mandat exclusif de recherche d’un bien immobilier en cours et s'interdit de le faire sans avoir préalablement dénoncé le présent mandat.</p>
        <p>Le présent mandat étant exclusif, pendant toute sa durée, le Mandant s’interdit de traiter directement ou par l’intermédiaire d’un autre mandataire la recherche de biens correspondant à la description figurant ci-dessus. En outre, il s’engage à informer et à adresser au Mandataire toutes les propositions qui lui seraient adressées personnellement.</p>
        <p>Concernant les affaires présentées par l'agence, en toute conformité avec le code civil et les prescriptions d'ordre public de l'article 78 du décret n°72-678 du 20/07/1972, la rémunération du mandataire sera due même si l'opération est conclue sans les soins du mandataire directement ou par un autre intermédiaire, pendant la durée du mandat et pendant douze mois après la fin du mandat.</p>
        <p>A défaut, le mandataire versera une indemnité compensatrice forfaitaire égale aux honoraires prévus au présent mandat.</p>
    </div><?php 
}


function get_pdf_information_du_mandant_recherche($number, $mandat_id){ ?>
    <div class="bloc"> 
        <div class="titre_3"><span><?= $number; ?> — INFORMATION DU MANDANT</span></div>
        <p>Le mandataire informera le mandant, par LRAR ou par tout autre écrit remis contre récépissé ou émargement, dans les huit jours de l'opération, de l'accomplissement du mandat, en joignant le cas échéant une copie de la quittance ou du reçu délivré, et ce, conformément à l'art. 77 du décret n° 72-678 du 20 juillet 1972.</p>
    </div><?php 
}


function get_pdf_mandat_delegation($mandat_id){ ?>
    <div class="bloc">
        <p class="bold">Il est convenu ce qui suit&nbsp;:</p>
        <p>Le Mandataire est titulaire d’un mandat enregistré dans son registre sous le N°<?php echo get_field('mandat_delegation_mandat_delegue', $mandat_id) ? get_field('mandat_delegation_mandat_delegue', $mandat_id) : ''; ?></p>
        <p>Et concernant&nbsp;:<br/>
            <?php echo get_field('mandat_delegation_mandat_delegue_detail', $mandat_id) ? get_field('mandat_delegation_mandat_delegue_detail', $mandat_id) : ''; ?>
        </p>   
        <p>Ce mandat, dont copie est annexée aux présentes avec toutes ses annexes, autorise le Mandataire à s’adjoindre ou se substituer à tout professionnel de son choix en vue de son accomplissement.</p>
        <p>En conséquence, le Mandataire délègue au Délégataire, qui accepte, ses droits et obligations résultant de ce mandat, tout en restant seul responsable vis à vis du Mandant.<br/>
        En cas de réalisation de l’objet du mandat par le délégataire, ses honoraires seront de&nbsp;: <?php echo get_field('mandat_delegation_honoraires', $mandat_id) ? get_field('mandat_delegation_honoraires', $mandat_id) : ''; ?>% des honoraires perçus</p>
        <p>à la charge du Mandataire, dès que celui-ci aura perçu ses propres honoraires.</p>
        <p>Si le Mandataire réalise lui même l’objet du mandat, ce qu’il lui est toujours possible de faire, il s’engage à en tenir informé immédiatement le Délégataire, mettant ainsi fin aux présentes sans indemnité de part ni d’autre.</p>
    </div>
    <div class="bloc">
        <div class="titre_3">CONDITIONS PARTICULIÈRES</div>
        <?php echo get_field('bien_notes_conditions_particulieres', $mandat_id) ? '<p>'.get_field('bien_notes_conditions_particulieres', $mandat_id).'</p>' : '<div class="espace_blanc"></div>'; ?>  
    </div><?php //echo signature_mandat_recherche();
}
