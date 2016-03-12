function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 40.19886899999999, lng: 44.528594},
          zoom: 17,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('name');
        var searchBox = new google.maps.places.SearchBox(input);
        //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
			var places = searchBox.getPlaces();

			if (places.length == 0) {
				return;
			}
		  
			markers.forEach(function(marker) {
				marker.setMap(null);
			});
			markers = [];
			var address;
			  // For each place, get the icon, name and location.
			var bounds = new google.maps.LatLngBounds();
			places.forEach(function(place) {

				address = place.formatted_address;
				var icon = {
				  url: place.icon,
				  size: new google.maps.Size(71, 71),
				  origin: new google.maps.Point(0, 0),
				  anchor: new google.maps.Point(17, 34),
				  scaledSize: new google.maps.Size(25, 25)
				};
				document.getElementById('address').value = address;
				if(typeof(place.formatted_phone_number) != 'undefined')
					document.getElementById('contact').value = place.formatted_phone_number;
				
				if(typeof(place.website) != 'undefined')
					document.getElementById('site').value = place.website;
					
				document.getElementById('latitude').value = place.geometry.location.lat();
				document.getElementById('longitude').value = place.geometry.location.lng();
				
				// Create a marker for each place.
				markers.push(new google.maps.Marker({
				  map: map,
				  icon: icon,
				  title: place.name,
				  position: place.geometry.location
				}));

				if (place.geometry.viewport) {
				  // Only geocodes have viewport.
				  bounds.union(place.geometry.viewport);
				} else {
				  bounds.extend(place.geometry.location);
				}
			});
			map.fitBounds(bounds);
        });
}
	  
$(document).keypress(
	function(event){
		if (event.which == '13') {
			event.preventDefault();
		}
});