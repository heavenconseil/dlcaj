<?php
/* On inclue tous les fichiers du répertoire functions */
$functions_directory = get_template_directory().'/functions';
if( is_dir($functions_directory) ){
	$dossier = opendir($functions_directory);
	while($fichier = readdir($dossier)){
		if(is_file($functions_directory.'/'.$fichier) && $fichier !='/' && $fichier !='.' && $fichier != '..'){
			require_once($functions_directory.'/'.$fichier);
		}
	}
	closedir($dossier);
}