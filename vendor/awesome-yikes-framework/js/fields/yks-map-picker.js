let map;
let markers = [];

jQuery( document ).ready( function() {

	jQuery( '#map-picker-api-key' ).focusout( function() {
		const api_key = jQuery( this ).val();
		if ( api_key.length > 0 && typeof google === 'undefined' ) {
			jQuery.getScript( 'https://maps.googleapis.com/maps/api/js?key=' + api_key + '&libraries=places&callback=yks_init_map' );
		}
	});

	jQuery( '.map-search' ).keypress( function( event ) {

		// If the enter key (13) was clicked and the input field has a value
		if ( parseInt( event.which ) === 13 && this.value.trim().length > 0 ) {

			event.preventDefault();
			event.stopPropagation();

			let search = this.value;
			yks_find_place( search );
		}

	});

	jQuery( '.map-reset' ).click( function() {
		yks_set_search( '' );
		yks_set_lat( '' );
		yks_set_lng( '' );
	});

	jQuery( '.map-search-submit' ).click( function() {
		let search = jQuery( this ).siblings( '.map-search' ).val();
		yks_find_place( search );
	});
	
});

function yks_init_map() {

	let lat = yks_get_lat();
	let lng = yks_get_lng();

	let myLatLng = new google.maps.LatLng( lng, lat );

	console.log( lat );
	console.log( lng );

	let mapOptions = {
	  center: myLatLng,
	  zoom: 16
	};

	map = new google.maps.Map( document.getElementById( 'map-canvas' ), mapOptions );

	let marker = new google.maps.Marker( {
		position: myLatLng, 
		map: map, 
		draggable: false
	});

	marker.setMap( map );

    google.maps.event.addListener( map, 'click', function( event ) {
        yks_click_map( event );
    });
}

function yks_click_map( event ) {

	// Clear the search when a marker is manually moved
	if ( typeof event.placeId !== 'undefined' ) {

		// If a place was clicked, let's try to find out what that place is...
		let place_id = event.placeId;
		yks_find_place_by_id( place_id );
	} else {

		yks_set_search( '' );
		yks_place_marker( event.latLng );
	}
}

// function yks_clear_markers() {
// 	yks_remove_markers_from_map();
// 	markers = [];
// }

// // Sets the map on all markers in the array.
// function setMapOnAll( map ) {
// 	for ( var i = 0; i < markers.length; i++ ) {
// 		markers[i].setMap( map );
// 	}
// }

// // Removes the markers from the map, but keeps them in the array.
// function yks_remove_markers_from_map() {
// 	setMapOnAll( null );
// }

function yks_place_marker( location ) {

	if ( typeof marker === 'undefined' ) {
		marker = new google.maps.Marker({
			position: location,
			map: map,
			animation: google.maps.Animation.DROP
		});
	} else {
		marker.setPosition( location );
	}

	markers.push( marker );

	map.setCenter( location );

	yks_set_lat( location.lat() );
	yks_set_lng( location.lng() );
}

function yks_places_api_callback( results, status ) {

	if ( status === google.maps.places.PlacesServiceStatus.OK ) {

		results = typeof results[0] !== 'undefined' ? results[0] : results;

		console.log( results );
		yks_place_marker( results.geometry.location );
		yks_set_search( results.formatted_address, results.name );
	}
}

function yks_find_place_by_id( place_id ) {

	var request = { placeId: place_id }

	service = new google.maps.places.PlacesService( map );
	service.getDetails( request, yks_places_api_callback );
}

function yks_find_place( search ) {

	var request = {
		query: search,
		fields: ['formatted_address', 'name', 'geometry'],
	}

	service = new google.maps.places.PlacesService( map );
	service.findPlaceFromQuery( request, yks_places_api_callback );

}

function yks_set_search( address, name ) {

	if ( typeof name !== 'undefined' && name.length > 0 ) {
		document.getElementById( "google-maps-search" ).value = name + ', ' + address;
	} else {
		document.getElementById( "google-maps-search" ).value = address;
	}
	
}


function yks_get_lng( lng ) {
	return document.getElementById( "google-maps-lng" ).value;
}

function yks_set_lng( lng ) {
	document.getElementById( "google-maps-lng" ).value = lng;
}

function yks_get_lat( lat ) {
	return document.getElementById( "google-maps-lat" ).value;
}

function yks_set_lat( lat ) {
	document.getElementById( "google-maps-lat" ).value = lat;
}