// Attach your callback function to the `window` object
window.initMap = function() {
    // JS API is loaded and available
    var map = null;
    var lat = 48.852969;
    var lon = 2.349903;

	map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(lat, lon),
		zoom: 11,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: true,
		scrollwheel: false,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
		},
			navigationControl: true,
			navigationControlOptions: {
			style: google.maps.NavigationControlStyle.ZOOM_PAN
		}
    });

	// Nous appelons la fonction ajax de jQuery
	$.ajax({

		url : "/GoogleServices",

	}).done(function(json){ 

		console.log(json);

	});

};
