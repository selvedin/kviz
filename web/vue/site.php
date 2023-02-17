<?php

use yii\helpers\Url;

$formID = Yii::$app->params['fileFormName'];
$object = 'places';
$id = null;
?>
<script>
  //VUE APP
  const mainApp = new Vue({
    el: '#siteApp',
    data: {
      title: '<?= __('Quiz') ?>',
    },
    mounted() {},
    methods: {},
    computed: {},
    watch: {}
  });

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