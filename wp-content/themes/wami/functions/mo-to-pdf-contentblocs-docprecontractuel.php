<?php

function get_pdf_modalite_et_honoraires($mandat_id){
    $type_mandat = get_field("bien_mandat", $mandat_id);
    ?>
    <div class="bloc"> 
        <p class="">Votre interlocuteur exerce l’activité d’entremise sur les immeubles et fonds de commerce, conformément à la loi n° 70-9 du 2 janvier 1970, dite « loi Hoguet » et au décret n°72-678 du 20 juillet 1972, consultables sur le site <span class="underline">www.legifrance.gouv.fr</span>.</p>
        <?php // si le type de mandat est exigence alors pas les mm delais
        if( $type_mandat['value']=='mandat_exigence' ) :
            ?>
            <p class="">Le service proposé consiste en la vente d'un bien immobilier via le mandat n°<?php echo $mandat_id; ?>, comprenant une première période irrévocable de 1 mois.<br>Modalités de dénonciation : par lettre de recommandée avec demande d’avis de réception (LRAR), avec un préavis de 7 jours.</p><?php 
        // sinon
        else : ?>
            <p class="">Le service proposé consiste en la vente d'un bien immobilier via le mandat n°<?php echo $mandat_id; ?>, comprenant une première période irrévocable de 3 mois.<br>Modalités de dénonciation : par lettre de recommandée avec demande d’avis de réception (LRAR), avec un préavis de 15 jours.</p>
        <?php endif; ?>
        <p class="">Honoraires, en cas de pleine réussite de la mission confiée : <?php echo get_field('bien_honoraires_montant', $mandat_id) ? number_format(get_field('bien_honoraires_montant', $mandat_id), 0, '', ' ')."euros" : ""; ?>.<br>Modalités de règlement : chèque ou virement.</p>
    </div><?php
}

function get_pdf_droit_retractation(){
    ?>
    <div class="bloc"> 
        <div class="titre_3"><span>DROIT DE RETRACTATION</span></div>
        <p class="">Si le mandat est signé « hors établissement » ou « à distance », le mandant pourra se rétracter pendant un délai de 14 jours à compter de la signature du mandat, en renvoyant au professionnel le coupon de rétractation attaché au mandat ou toute déclaration dénuée d’ambiguïté, exprimant sa volonté de se rétracter, par lettre recommandée avec demande d’accusé réception ; ce, sans avoir à motiver sa décision.</p>
        <p class="">Le mandant pourra, s’il le souhaite, lors de la signature du mandat, demander à ce que le mandataire commence ses prestations avant l’expiration du délai de rétractation.</p>
        <p class="">Il pourra toutefois se rétracter durant cette période, sauf si le mandataire a pleinement exécuté sa mission. Le mandant reconnaît avoir pris connaissance du formulaire de rétractation attaché au mandat (modèle de formulaire de rétractation type ci-après). Si le mandat est signé à l’agence, le mandant ne bénéficie d’aucun droit de rétractation.</p>
    </div><?php
}

function get_pdf_rgpd(){
    ?>
    <div class="bloc"> 
        <div class="titre_3"><span>RGPD, PROTECTION DES DONNEES PERSONNELLES</span></div>
        <p class="">En tant que professionnel de l'immobilier, nous traitons des données personnelles (état civil, adresses, adresses e-mail, n° de téléphone, photos, plans et géolocalisation des biens…).
        <br>Conformément au règlement européen 2016/679, nous informons nos clients consommateurs que nous collectons et traitons des données personnelles nécessaires pour l'accomplissement de notre mission.
        <br>Ces données pourront être transmises au notaire, au co-contractant, aux organismes financiers éventuellement chargés du financement, ainsi qu'aux administrations concernées (mairie pour DPU notamment…).
        <br>Les photos, vidéos, plans et géolocalisation des biens à vendre ou à louer pourront être diffusés sur tous supports publicitaires. Elles seront conservées pendant toute la durée de la relation commerciale et ensuite pendant une durée de cinq ans conformément à l'article L 561-12 du Code monétaire et financier, et pendant dix ans en ce qui concerne les noms et adresses des mandants en vertu de l'article 53 du décret n° 72-78 du 20 juillet 1972.
        <br>Nos clients consommateurs bénéficient d'un droit d'accès et de rectification de leurs données à caractère personnel traitées, ils peuvent demander leur effacement et leur portabilité, ou exercer leur droit à opposition dans les conditions prévues par le règlement européen 2016/679.</p>
        <br>
        <table>
            <tr>
                <td>
                    <p>En conséquence, le client consommateur autorise le professionnel à utiliser ses données personnelles comme ci-dessus, par sa signature dans ce cadre ></p>
                </td>
                <td class="signature_cadre"><p class="bold">Signature</p>                   
                </td>
            </tr>
        </table><br>
    </div><?php 
}

function get_pdf_prevention_et_reglement_litiges(){
    ?>
    <div class="bloc"> 
        <div class="titre_3"><span>PRÉVENTION ET RÈGLEMENT DES LITIGES ET RÉCLAMATIONS</span></div>
        <p class="">Pour toute éventuelle réclamation, nous vous remercions de la faire à votre choix par courrier à notre adresse postale en tête des présentes, par téléphone, ou par mail ; nous la traiterons dans les meilleurs délais. En cas de litige, la législation applicable sera la loi française, et la juridiction compétente celle du lieu du domicile du consommateur.</p>
    </div><?php
}


function get_pdf_mediation_litiges(){
    ?>
    <div class="bloc"> 
        <div class="titre_3"><span>MÉDITATION DES LITIGES DE LA CONSOMMATION</span></div>
        <p>Tout consommateur a le droit de recourir gratuitement à un médiateur de la consommation en vue de la résolution amiable du litige qui l’oppose à un professionnel.</p>
        <p>A cet effet, nous vous garantissons le recours effectif à un dispositif de médiation de la consommation, en vertu des Articles L611-1 et suivants du Code de la consommation, créés par l’ordonnance n°2016- 301 du 14 mars 2016.
        <br>Notre organisme de médiation est AME Conso - 197 boulevard Saint-Germain 75007 Paris, auprès duquel nous sommes affiliés.</p>
    </div><?php 
}


function get_pdf_signature(){
    ?><div class="signature">
        <p class="ligne_simple bold">Le consommateur reconnaît avoir pris connaissance de l'ensemble des conditions générales et particulières du mandat proposé, par la remise préalable qui lui a été faite d'un exemplaire.</p>
        <table>
            <tr>
                <td class="left">
                    <p>Fait à Paris<br>(en deux exemplaires, dont un pour<br>le propriétaire et un pour l’agence.)</p>
                </td>
                <td>  
                   <p class="bold">Signer et parapher l'ensemble des documents<br>édités ce jour ayant seule valeur d’originaux.</p>                        
                </td>
            </tr>
        </table> 
        <p>Date :</p>      
        <table>
            <tr>
                <td class="signature_cadre">
                    <p class="bold">Le(s) Consommateur(s) :</p>
                </td>
                <td class="signature_cadre">
                   <p class="bold">le Mandataire : </p>                      
                </td>
            </tr>
        </table>
    </div><?php 
}