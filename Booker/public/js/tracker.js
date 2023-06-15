if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(
    function (position) {
      let { latitude, longitude } = position.coords;
      console.log(latitude, longitude);
      let map = L.map("map").setView([latitude, longitude], 15);

      L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution:
          '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      }).addTo(map);

      var packageMarker = L.marker([latitude, longitude]).addTo(map);
      var packagePolyline = L.polyline([], { color: "red" }).addTo(map);

      var startPointInput = document.getElementById("startPoint");
      var destinationPointInput = document.getElementById("destinationPoint");
      var convertButton = document.getElementById("convertButton");

      convertButton.addEventListener("click", function (e) {
        e.preventDefault();
        var startPoint = startPointInput.value;
        var destinationPoint = destinationPointInput.value;

        // if (startPoint && destinationPoint) {
        //   geocodePlace(startPoint, function (startPointCoords) {
        //     geocodePlace(destinationPoint, function (destinationPointCoords) {
        //       if (startPointCoords && destinationPointCoords) {
        //         updatePackageRoute(startPointCoords, destinationPointCoords);
        //       }
        //     });
        //   });
        // }

        if (startPoint && destinationPoint) {
          geocodePlace(startPoint, function (coords) {
            startPointCoords = coords;
            checkStartAndDestinationPoints();
          });

          geocodePlace(destinationPoint, function (coords) {
            destinationPointCoords = coords;
            checkStartAndDestinationPoints();
          });
        }
      });

      function checkStartAndDestinationPoints() {
        if (startPointCoords && destinationPointCoords) {
          currentLatLng = startPointCoords;
          destinationLatLng = destinationPointCoords;
          animateMarker();
        }
      }

      function animateMarker() {
        if (animationInterval) {
          clearInterval(animationInterval);
        }

        var duration = 10300; // Animation duration in milliseconds
        var steps = 100; // Number of animation steps

        var latStep = (destinationLatLng.lat - currentLatLng.lat) / steps;
        var lngStep = (destinationLatLng.lng - currentLatLng.lng) / steps;

        var step = 1;

        animationInterval = setInterval(function () {
          if (step > steps) {
            clearInterval(animationInterval);
            return;
          }

          var lat = currentLatLng.lat + latStep * step;
          var lng = currentLatLng.lng + lngStep * step;

          var newPosition = L.latLng(lat, lng);

          packageMarker.setLatLng(newPosition);
          map.panTo(newPosition);

          step++;
        }, duration / steps);
      }

      // Function to geocode a place name to coordinates
      function geocodePlace(place, callback) {
        var apiKey = "352fc6f8cd8e4093962317c22f3f48fa";
        var url =
          "https://api.opencagedata.com/geocode/v1/json?q=" +
          encodeURIComponent(place) +
          "&key=" +
          apiKey;

        fetch(url)
          .then(function (response) {
            return response.json();
          })
          .then(function (data) {
            if (data.results.length > 0) {
              var coords = data.results[0].geometry;
              callback(coords);
            } else {
              callback(null);
            }
          })
          .catch(function (error) {
            console.error("Error geocoding place:", error);
            callback(null);
          });
      }

      // Function to update the package route
      // function updatePackageRoute(startPointCoords, destinationPointCoords) {
      //   packageMarker.setLatLng(startPointCoords);
      //   packagePolyline.setLatLngs([startPointCoords, destinationPointCoords]);
      //   map.fitBounds([startPointCoords, destinationPointCoords]);
      // }
    },
    function () {
      console.log("cannot get location");
    }
  );
} else {
  // tells user to activate data connection so as to track
  console.log("Please enable data connection to track your products");
}

// document.addEventListener("DOMContentLoaded", function () {
//   // Initialize the map
//   var map = L.map("map").setView([51.505, -0.09], 13);

//   // Add a tile layer to the map (you can use any tile provider)
//   L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
//     attribution:
//       'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
//   }).addTo(map);

//   // Define package marker and polyline
//   var packageMarker = L.marker([51.5, -0.09]).addTo(map);
//   var packagePolyline = L.polyline([], { color: "red" }).addTo(map);

//   // Function to update package location and route
//   function updatePackageLocation(lat, lng) {
//     // Update package marker position
//     packageMarker.setLatLng([lat, lng]);

//     // Add current location to the polyline route
//     packagePolyline.addLatLng([lat, lng]);
//   }

//   // Simulate package location updates
//   setInterval(function () {
//     // Generate random latitude and longitude values for demonstration
//     var lat = 51.5 + (Math.random() - 0.5) * 0.1;
//     var lng = -0.09 + (Math.random() - 0.5) * 0.1;

//     updatePackageLocation(lat, lng);
//   }, 3000); // Update every 3 seconds (adjust as needed)
// });
