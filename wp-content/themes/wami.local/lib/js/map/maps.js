(function($){

	var markers = [];

if( $('#localisation').length ){
	var my_ID = 'localisation-map';
	var typezoom = $('#localisation-map').data('zoom'); //console.log(typezoom);
	var zoom = return_zoom(typezoom); //console.log(zoom);	
	google.maps.event.addDomListener(window, 'load', initialize_map(zoom, my_ID, 0));
}


if( $('#city-map').length ){
	var my_ID = 'map'; 
	var typezoom = $('#map').data('zoom'); //console.log(typezoom);
	var zoom = return_zoom(typezoom);	//console.log(zoom);	
	google.maps.event.addDomListener(window, 'load', initialize_map(zoom, my_ID, 0));
}

function return_zoom(typezoom){
	var zoom = 13;	
	if(typezoom == 'region') zoom = 8;
	if(typezoom == 'ville') zoom = 10;
	if(typezoom == 'district') zoom = 15;
	if(typezoom == 'adresse_bien') zoom = 15;
	return zoom;	
}
	


$('#recommendations .lien_adresse_map').on('click', function(e){
	e.preventDefault();	
	if(markers.length){
		// On gere le lien actif
		$('#recommendations .lien_adresse_map.active').removeClass('active');
		$(this).addClass('active');

		// On gere le marqueur actif
		var marker_ID = $(this).data('markerid');

		var image_active  	= {
			url: wami_js.themeurl+'/lib/img/ico/map_marker_actif.png',
			size: new google.maps.Size(30,60),
			origin: new google.maps.Point(0,0),
			anchor: new google.maps.Point(15,60)
		};

		var image = {
			url: wami_js.themeurl+'/lib/img/ico/map_marker.png',
			size: new google.maps.Size(30,60),
			origin: new google.maps.Point(0,0),
			anchor: new google.maps.Point(15,60)
		};

		for(i=0; i<markers.length; i++){
			if(markers[i].id != 'marker_bien') {
				if( markers[i].id == marker_ID ) {	
					markers[i].setIcon(image_active);
				} else {				
					markers[i].setIcon(image);				
				}
			}
			
		}
	}	
});



/* ---------------------------------------------------------------------------------------
----------------------------- LES FONCTIONS ----------------------------------------------
--------------------------------------------------------------------------------------- */
	function getUrlParameter(sParam){
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;
	    for(i=0; i<sURLVariables.length; i++){
	        sParameterName = sURLVariables[i].split('=');
	        if(sParameterName[0] === sParam){
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	};


	function centreAutourDeTousLesMarkers(){
		var bounds = new google.maps.LatLngBounds();		
		for(i=0; i<markers.length; i++){
			bounds.extend(markers[i].getPosition());
		}
		map.fitBounds(bounds);
	};


	function initialize_map(zoom, my_ID, add_first_marker){
		//var title 		= $('#'+my_ID).data('adresse');
		var lat   		= $('#'+my_ID).data('lat');
		var lng   		= $('#'+my_ID).data('lng');	
		var marker_ID   = $('#'+my_ID).data('markerid');
		if(/*title &&*/ lat && lng) {
			geocoder = new google.maps.Geocoder();
			var max_zoom_map = $('#localisation').length ? zoom : false;
			//console.log('plop : '+max_zoom_map);
			map = new google.maps.Map(document.getElementById(my_ID), {
				center: {lat:lat, lng:lng},
				zoom: zoom,
				maxZoom: max_zoom_map,
			    disableDefaultUI: true,
			    mapTypeControl: false,
				mapTypeControlOptions: {
					mapTypeIds: [google.maps.MapTypeId.ROADMAP]
				},
				mapTypeId: 'wami_theme'
			});
			var customMapType = new google.maps.StyledMapType([{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#a7a7a7"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"color":"#737373"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#efefef"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#dadada"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#696969"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#b3b3b3"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#d6d6d6"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"weight":1.8}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"color":"#d7d7d7"}]},{"featureType":"transit","elementType":"all","stylers":[{"color":"#808080"},{"visibility":"off"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#d3d3d3"}]}] );
			map.mapTypes.set('wami_theme', customMapType);

			if(add_first_marker == true){
				var image = {
					url: wami_js.themeurl+'/lib/img/ico/map_marker.png',
					size: new google.maps.Size(30,60),
					origin: new google.maps.Point(0,0),
					anchor: new google.maps.Point(15,60)
				};
				var markerPos = new google.maps.LatLng(lat, lng);	
				var marker = new google.maps.Marker({
					position: markerPos,
					//title: title,
					icon: image,
					id: marker_ID,
				});
				markers.push(marker);
			}

	    	var mc = new MarkerClusterer(map, markers);  	    	 
	    	
	    	//if( $('#recommendations .lien_adresse_map').length ) chargeToutesLesAdresses(mc); 
	    	chargeToutesLesAdresses(mc);

		}    	
	};


	function chargeToutesLesAdresses(mc){
		// Si c'est un bien on ajoute son picto
		if( $('#localisation-map').length ) {
			if( $('#localisation-map').data('lat') && $('#localisation-map').data('lng') && $('#localisation-map').data('adresse') && $('#localisation-map').data('markerid')){
				var image_active = {
					url: wami_js.themeurl+'/lib/img/ico/map_marker_rond.png',
					size: new google.maps.Size(200,200), // 128,128
					origin: new google.maps.Point(0,0),
					anchor: new google.maps.Point(64,64)
				};
				/*if($('#localisation-map').data('typem') == 'mandat_exclusif'){
					image_active = {
						url: wami_js.themeurl+'/lib/img/ico/map_marker_actif.png',
						size: new google.maps.Size(30,60),
						origin: new google.maps.Point(0,0),
						anchor: new google.maps.Point(15,60)
					};
				}	*/			
				var markerPos = new google.maps.LatLng($('#localisation-map').data('lat'), $('#localisation-map').data('lng'));
				var marker = new google.maps.Marker({
					position: markerPos,
					//title: $('#localisation-map').data('adresse'),
					icon: image_active,
					id: $('#localisation-map').data('markerid')
				});
				markers.push(marker);
			}
		}
		var image = {
			url: wami_js.themeurl+'/lib/img/ico/map_marker.png',
			size: new google.maps.Size(30,60),
			origin: new google.maps.Point(0,0),
			anchor: new google.maps.Point(15,60)
		}; 
		// Et on parcours toutes les éventuelles autres adresses
		if( $('#recommendations .lien_adresse_map').length ){
			$('#recommendations .lien_adresse_map').each(function(){
				if( $(this).data('lat') && $(this).data('lng') && $(this).data('adresse') && $(this).data('markerid')){
					var markerPos = new google.maps.LatLng($(this).data('lat'), $(this).data('lng'));
					var marker = new google.maps.Marker({
						position: markerPos,
						//title: $(this).data('adresse'),
						icon: image,
						id: $(this).data('markerid'),
					});
					markers.push(marker);
				}
			});	
		};
		//mc = new MarkerClusterer(map, markers); 
		//var mcOptions = {imagePath: 'images/m'};
		mc = new MarkerClusterer(map, markers/*, mcOptions*/);  
		if(markers.length>1) centreAutourDeTousLesMarkers();

	};




})(jQuery);