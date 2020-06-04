<?php 
function formulaire_de_retractation($mandat_id, $mandat_type=false){
    $noms = $adresses = '';
    $total = count(get_field('proprietaires', $mandat_id));
    $limit = $mandat_type=='mandat_de_recherche' ? 3 : 5;
    if(have_rows('proprietaires', $mandat_id)) : ?>
        <?php while(have_rows('proprietaires', $mandat_id)): the_row(); 
            $r = get_row_index();
            if($r < $limit) : 
                 if($total>1 && $r==1) {                    
                    $noms .= '<br>'; 
                    $adresses .= '<br>';  
                }
                $noms .= get_sub_field('proprietaire_societe') ? get_sub_field('proprietaire_raison_social').' représentée par : '.get_sub_field('proprietaire_société_attention_de') : get_sub_field('proprietaire_nom').' '.get_sub_field('proprietaire_prenom');        
                $adresses .= get_sub_field('proprietaire_adresse') ? get_sub_field('proprietaire_adresse').' ' : "";
                $adresses .= get_sub_field('proprietaire_cp') ? get_sub_field('proprietaire_cp').' ' : "";
                $adresses .= get_sub_field('proprietaire_ville') ? get_sub_field('proprietaire_ville').' ' : "";
                $adresses .= get_sub_field('proprietaire_pays') ? get_sub_field('proprietaire_pays') : ""; 
                if($r<$total) {
                    $noms .= '<br>'; 
                    $adresses .= '<br>';  
                } ?>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
    <br><br>
    <div class="titre_mandat retractation">
        <p class="ligne_1 ligne_simple vert">modèle de formulaire de rétractation</p>                
    </div> 
      
    <div class="bloc">
        <p class="small">Code de la consommation - art. Annexe à l’article R121-1 Créé par décret n°2014-1061 du 17 septembre 2014.
            <br>Veuillez compléter et renvoyer le présent formulaire uniquement si vous souhaitez vous rétracter du contrat ci-avant.
        </p>
        <br>
        <p class="ligne_simple">A l’attention de : SAS SEVEN & SEVEN DE LA COUR AU JARDIN</p>
        <p class="ligne_simple">Adresse : 157 rue du Faubourg Saint Antoine 75011 PARIS</p>
        <p class="ligne_simple">E-mail : contact@delacouraujardin.com</p>
    </div>
    <div class="bloc">            
        <p>Je/nous* vous notifie/notifions* par la présente ma/notre* rétractation du contrat, portant sur la vente du bien ci-après (*rayer la mention inutile) :</p>
    </div>
    <div class="bloc">  
        <p class="ligne_simple">Commandé le :</p>                
        <p class="ligne_simple">Nom du (des) consommateur(s) : <?php echo $noms; ?></p>
        <br>
        <p class="ligne_simple">Adresse du (des) consommateur(s) : <?php echo $adresses; ?></p>
    </div>
    <div class="bloc">  
        <p class="ligne_simple">Date :</p>
        <p class="ligne_simple">Signature du (des) consommateur(s), uniquement en cas de notification du présent formulaire sur papier.</p> 
    </div>
    <br><br><br><br><br>
    <div class="bloc"> 
        <p class="small">Conditions :
            <br>• Compléter et signer ce formulaire.
            <br>• L’envoyer par lettre recommandée avec avis de réception.
            <br>• Utiliser l’adresse de l’agence ci-dessus.
            <br>• L’expédier au plus tard le quatorzième jour à partir du jour de la commande ou,
            <br>si ce délai expiré normalement un samedi, un dimanche ou un jour férié ou chômé,
            <br>le premier jour ouvrable suivant.
            <br>• L’envoyer par lettre recommandée avec avis de réception.
            <br>• Si une adresse mail ou un numéro de télécopie figurent, vous pouvez utiliser<br>l’un ou l’autre pour notifier votre rétractation
        </p>
    </div>
    <?php
}