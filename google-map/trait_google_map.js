var trait_google_map = function( map_convas_id )
{
   const METERS_PER_MILE = 1609.34;

   let self = this;
   let map_widget, marker_widget, circle_widget;
   let widget_coordinates, zoom_in, mark_circle;

   this.widget_initialize = function( mark_circle )
   {
       this.map_convas();
       this.pin_markar();
       this.circle_radius( mark_circle );
   };

   this.widget_coordinates_by_address = function ( address )
   {
       let geocoder = new google.maps.Geocoder();

       geocoder.geocode({'address': address}, function (results, status) {

           if (status == google.maps.GeocoderStatus.OK)
           {
               let geometry_coordinates = results[0].geometry.location;

               self.map_coordinates(geometry_coordinates.lat(), geometry_coordinates.lng() );
               self.widget_initialize();
           }
       });
   },

   this.map_coordinates = function(latitude, longitude)
   {
       widget_coordinates = { lat: latitude, lng: longitude };

       return widget_coordinates;
   },

   this.get_map_coordinates = function() {
       return widget_coordinates;
   },

   this.map_convas = function(){

       let zoom_in = 8;

       let map_options = {
           mapTypeControl: false,
           zoom: zoom_in,
           center: widget_coordinates
       };

       map_widget = new google.maps.Map(map_convas_id, map_options);

       return map_widget;
   },

   this.pin_markar = function()
   {
       if (marker_widget !== undefined)
       {
           marker_widget.setPosition(widget_coordinates);
       }
       else
       {
           marker_widget = new google.maps.Marker({
               position: widget_coordinates,
               animation: google.maps.Animation.DROP,
               map: map_widget
           });
       }

       return marker_widget;
   },

   this.circle_radius = function( mark_circle )
   {
       if( !mark_circle ) return ;

       let circle_opts = {
           map: map_widget,
           center: widget_coordinates,
           strokeColor: "#F26836",
           strokeOpacity: 0.8,
           strokeWeight: 0,
           fillColor: "#322F6A",
           fillOpacity: 0.35,
           radius: (parseFloat(mark_circle) * METERS_PER_MILE) // in miles
           //radius: parseFloat(mark_circle) // in miles
       };

       if( circle_widget ) circle_widget.setMap(null);

       circle_widget = new google.maps.Circle(circle_opts);
       console.log('circle_widget 1');

       circle_widget.bindTo('center', marker_widget, 'position');
       map_widget.fitBounds(circle_widget.getBounds());

       return circle_widget;
   }
};
