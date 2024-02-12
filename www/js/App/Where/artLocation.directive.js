App.directive('artLocation', function() {
      return {
         restrict: 'E',
         transclude: true,
         scope:{
             'geodata':'=geodata'
         },
         templateUrl: 'http://'+document.domain+'/templates/website/widgets/art_location_template.html',
         link: function (scope, element, attrs) {
                /***********************************/
            var art_overlay;
            var art_map;
            var art_infoWindow;
            var art_marker;
            var art_locationControl;
            var art_locationControlDiv = document.createElement('div');
            var art_showAddress = true; // set to false to omit reverse geocoding
            var art_location = {}; // contains the coordinates of the geolocalized point. The fields are: lat, lng, formatted_address
            
            scope.$watch('geodata', function (newValue) {                
                if (newValue.geometry.location.lat && newValue.geometry.location.lng) {
                   if (! art_marker) {
                      art_marker = new google.maps.Marker({map: art_map});
                   }
                  var pos = {'lat': newValue.geometry.location.lat(), 'lng': newValue.geometry.location.lng()};
                  window.console.log(pos);
                  art_marker.setPosition(pos);
                  art_map.setCenter(pos);
                  art_map.setZoom(15);
                }
            });
            
            function displayError(error) {
                art_overlay.className += ' art_hidden';
                alert (error);
            }

            function displayLocation(position, address) {
                var content = '';

                art_overlay.className += ' art_hidden';

//                if (! art_infoWindow) {
//                    art_infoWindow = new google.maps.InfoWindow({map: art_map});
//                } else {
//                    art_infoWindow.open(art_map);
//                }
                if (! art_marker) {
                   art_marker = new google.maps.Marker({map: art_map});
                }
               if (address) {
                    content += address.formatted_address + '<br>';
                }
//                content += '(' + position.lat + ', ' + position.lng + ')';
//                art_infoWindow.setContent(content);
//                art_infoWindow.setPosition(position);
                art_marker.setPosition(position);

                art_location.lat = position.lat;
                art_location.lng = position.lng;
                art_location.formatted_address = address.formatted_address;
                art_location.place_id = address.place_id;

                scope.geodata.geometry.location = art_location;
                scope.geodata.place_id=address.place_id;
                scope.geodata.formatted_address=art_location.formatted_address;
                //scope.geoData.formatted_address=art_location.formatted_address;
                scope.$apply();
                $('input[name="place"]').val(art_location.formatted_address);
            }

            function showPosition(position) {

                if (typeof(position.lat) == 'function') {
                    // user clicked a point on map
                    var pos = {lat: position.lat(), lng: position.lng()};
                } else {
                    // user clicked My position button
                    var pos = position;
                    art_map.setCenter(pos);
                    art_map.setZoom(15);
                }

                if (art_showAddress) {
                   var geocoder = new google.maps.Geocoder;
                    geocoder.geocode({'location': pos}, function(results, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                           //~ var t = ''; results.forEach(function(item) {t += item.formatted_address + '\n'}); alert(t);
                           if (results[0]) {
                                displayLocation(pos, results[0]);
                            } else {
                                displayError('No results found');
                            }
                        } else {
                            displayError('Geocoder failed due to: ' + status);
                        };
                    });
                } else {
                    displayLocation(pos);
                }
            }

            function art_LocationControl(controlDiv) {
                // Set CSS for the control border
                var controlUI = document.createElement('div');
                controlUI.style.backgroundColor = '#fff';
                controlUI.style.border = '2px solid #fff';
                controlUI.style.borderRadius = '2px';
                controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.2)';
                controlUI.style.cursor = 'pointer';
                controlUI.style.marginRight = '11px';
                controlUI.style.textAlign = 'center';
                controlUI.style.verticalAlign = 'middle';
                controlUI.title = 'My location';
                controlDiv.appendChild(controlUI);

                // Set CSS for the control interior
                var controlText = document.createElement('div');
                controlText.style.color = 'rgb(100,100,100)';
                //~ controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
                //~ controlText.style.fontSize = '24px';
                //~ controlText.style.fontWeight = 'bold';
                //~ controlText.style.paddingLeft = '4px';
                //~ controlText.style.paddingRight = '4px';
                //~ controlText.style.paddingTop = '2px';
                controlText.style.fontSize = '0';
                controlText.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" width="24px" height="24px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path d="M256,162.909c-51.432,0-93.091,41.659-93.091,93.091s41.659,93.091,93.091,93.091s93.091-41.659,93.091-93.091  S307.432,162.909,256,162.909z M464.057,232.728c-10.704-97.046-87.738-174.08-184.784-184.784V0h-46.545v47.943  c-97.046,10.704-174.08,87.738-184.784,184.784H0v46.545h47.943c10.704,97.046,87.738,174.08,184.784,184.784V512h46.545v-47.943  c97.046-10.704,174.08-87.738,184.784-184.784H512v-46.545H464.057z M256,418.909c-89.949,0-162.909-72.955-162.909-162.909  c0-89.949,72.96-162.909,162.909-162.909c89.954,0,162.909,72.96,162.909,162.909C418.909,345.954,345.954,418.909,256,418.909z" fill="#696969"/></svg>';
                controlUI.appendChild(controlText);

                // Setup the click event listeners
                controlUI.addEventListener('click', function() {
                    if (navigator.geolocation) {
                        art_location = {};
                        navigator.geolocation.getCurrentPosition(
                           function(position) {showPosition({lat: position.coords.latitude, lng: position.coords.longitude})},
                           function() {displayError('The Geolocation service failed.')},
                           {timeout: 5000});
                        art_overlay.className = art_overlay.className.replace(/\bart_hidden\b/g,'');
                    } else {
                        displayError('Your browser doesn\'t support geolocation.');
                    }
                });
            }

            function initMap() {
                art_overlay = document.getElementById("art_map_overlay");

                art_map = new google.maps.Map(document.getElementById('art_map'), {
                    draggableCursor: 'pointer',
                    disableDefaultUI: true,
                    zoomControl: true,
                    center: {lat: 42.80, lng: 14.04},
                    zoom: 1
                });

                art_locationControl = new art_LocationControl(art_locationControlDiv);
                art_locationControl.index = 1;
                art_map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(art_locationControlDiv);

                art_map.addListener('click', function(e) {art_location = {}; showPosition(e.latLng);} );
            }

            google.maps.event.addDomListener(window, "load", initMap);
            /***********************************/

         }
      }
   });
