<?php

use app\models\Places;
use yii\helpers\Url;

$formID = Yii::$app->params['fileFormName'];
$object = 'places';
$id = null;
?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4-c4d9_g4sTICuaqNYTta4EisCg871Jg&callback=initMap&v=weekly" defer></script>

<script>
  const markerIcon =
    "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png";
  const svgMarker = {
    path: "M-1.547 12l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM0 0q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z",
    fillColor: "blue",
    fillOpacity: 0.6,
    strokeWeight: 0,
    rotation: 0,
    scale: 2,
    // anchor: new google.maps.Point(0, 20),
  };
  const locations = <?= json_encode(Places::getLocations()) ?>;

  const initPosition = {
    lat: parseFloat(<?= Yii::$app->params['lat'] ?>),
    lng: parseFloat(<?= Yii::$app->params['lng'] ?>)
  };
  let canSelectPlaceOnMap = false;

  let marker, map, infoWindow;
  const markers = [];

  //VUE APP
  const mainApp = new Vue({
    el: '#siteApp',
    data: {
      title: '<?= __('Places') ?>',
      files: [],
      isEdit: false,
      emptyLocation: {
        id: null,
        title: null,
        address: null,
        location: null,
        visit_date: null,
        description: null
      },
      location: {
        id: null,
        title: null,
        address: null,
        location: null,
        visit_date: null,
        description: null
      }
    },
    mounted() {
      // this.location = {
      //   ...this.emptyLocation
      // };
      $('#place-view, #filesSidebar').hide();

    },
    methods: {
      setLocation: function(locationId) {
        this.location = locations.find(l => l.id == locationId);
        this.getObjectFiles();
      },
      getObjectFiles: function() {
        this.files.splice(0);
        <?php
        if (Yii::$app->user->isGuest) echo "return;";
        ?>
        const self = this;
        $.get(`<?= Url::to(['files/get-files', 'object' => $object]) ?>&id=${this.location.id}`, function(data) {
          console.log(data);
          self.files = data;
        });
      }
    },
    computed: {
      locationText: function() {
        return this.location.location ? this.location.location.lat + ',' + this.location.location.lng : null;
      }
    },
    watch: {
      location: function(newValue) {
        $('#place-form-container, #place-view, #filesSidebar').hide();
        if (newValue.title == null && !this.isEdit) {
          $('#place-form-container').show();
          $("#places-description").summernote("code", null);
          this.files = [];
        } else {
          $('#place-view, #filesSidebar').show();
        }
      },
      isEdit: function(newValue) {
        if (newValue) {
          $('#place-view, #filesSidebar').hide();
          $('#place-form-container').show();
          $("#places-description").summernote("code", this.location.description);
          return;
        }
        if (this.location.title) {
          $('#place-form-container').hide();
          $('#place-view, #filesSidebar').show();
          return;
        }
        this.files = [];
      }
    }
  });

  //GOOGLE MAPS
  function initMap() {

    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 8.5,
      center: initPosition,
      mapTypeId: 'terrain'
    });

    infoWindow = new google.maps.InfoWindow();

    google.maps.event.addListener(map, "click", function(e) {
      if (canSelectPlaceOnMap) {
        placeMarker(e.latLng, map);
        let geocoder = new google.maps.Geocoder();
        geocodeAddress(geocoder, e.latLng);
        $('#places-location').val(`${e.latLng.lat()},${e.latLng.lng()}`);
      }
    });

    locations.forEach(place => {
      addMarker(place, map);
    });
  }

  function addMarker(place, map) {
    let newMarker = new google.maps.Marker({
      position: place.location,
      title: "<div><strong>" + place.title + "</strong><br/>" + place.address + "</div>",
      map: map,
      icon: svgMarker,
      zIndex: place.id
    });

    newMarker.addListener("click", () => {
      mainApp.setLocation(newMarker.zIndex);
      mainApp.isEdit = false;
      infoWindow.close();
      infoWindow.setContent(newMarker.getTitle());
      infoWindow.open(newMarker.getMap(), newMarker);
    });
    markers.push(newMarker);
  }

  function placeMarker(position, map) {
    if (marker != null) marker.setPosition(position);
    else
      marker = new google.maps.Marker({
        position: position,
        map: map
      });
    // map.panTo(position);
  }


  function geocodeAddress(geocoder, latLng) {
    geocoder.geocode({
        latLng: latLng
      },
      function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
          if (results[1]) {
            $("#places-address").val(results[1].formatted_address);
            // infoWindow.setContent(results[1].formatted_address);
          } else {
            errorNotification("No results found.");
          }
        } else {
          errorNotification(
            "Geocode was not successful for the following reason" + status
          );
        }
      }
    );
  }

  window.initMap = initMap;

  function showToast(message) {
    canSelectPlaceOnMap = true;
    Swal.fire({
      position: 'top-end',
      icon: 'info',
      title: message,
      showConfirmButton: false,
      timer: 1500
    })
  }


  //FILES SCRIPTS

  function getFiles() {
    $.get(`<?= Url::to(['files/get-files', 'object' => $object, 'id' => $id]) ?>`, function(data) {
      mainApp.files = data;
    });
  }

  function deleteFile(el) {
    Swal.fire(SWAL_DELETE_OPTIONS).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `<?= Url::to(['files/unlink-file']) ?>`,
          type: 'POST',
          data: {
            id: mainApp.location.id,
            name: el.data('id'),
            _csrf: "<?= Yii::$app->request->csrfToken ?>"
          },
          datatype: 'json',
          success: function(data) {
            mainApp.files = data;
            successNotification();
          },
          error: function(data) {
            errorNotification('<?= __('Error on deleting file. Please try again') ?>.');
          }
        });
      }
    });
  }

  function downloadFile(el) {
    window.open(`<?= Url::to(['files/download']) ?>?id=${mainApp.location.id}&name=${el.data('id')}`);
  }

  const MAX_FILE_SIZE = <?= (int)Yii::$app->params['fileSize'] ?>;

  function uploadFile() {
    const self = this;
    const file = document.getElementById('<?= $formID ?>Input');

    if (file.files.length) {
      for (let i = 0; i < file.files.length; i++) {
        if (file.files[i].size > MAX_FILE_SIZE) {
          errorNotification('<?= __('Allowed files up to') . ' ' . Yii::$app->params['fileSizeText'] ?>');
          return;
        }
      }
    }
    const formData = new FormData($("#<?= $formID ?>Form")[0]);
    $.ajax({
      url: `<?= Url::to(['files/upload']) ?>?id=${mainApp.location.id}`,
      xhr: function() {
        let xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function(evt) {
          if (evt.lengthComputable) {
            let percentComplete = ((evt.loaded / evt.total) * 100);
            percentComplete = percentComplete.toFixed(2);
            $(".progress-bar").width(percentComplete + '%');
            $(".progress-bar").html(percentComplete + '%');
          }
        }, false);
        return xhr;
      },
      type: 'POST',
      data: formData,
      datatype: 'json',
      // async: false,
      beforeSend: function() {
        //
      },
      success: function(data) {
        mainApp.files = data;
        successNotification();
      },
      complete: function() {
        $(".progress-bar").width(0 + '%');
        $(".progress-bar").html(0 + '%');
      },
      error: function(data) {
        errorNotification(data.responseJSON.message);
      },
      cache: false,
      contentType: false,
      processData: false
    });
    return false;
  }

  function showImage(el) {
    $('#image-modal').attr('src', `<?= Url::to(['files/show-image']) ?>?id=${mainApp.location.id}&name=${el.data('file')}`);
    $('#imageModal').modal('show');
  }

  $('#fileTagsModal').on('hidden.bs.modal', function(event) {
    mainApp.fileId = null;
  })
</script>