<?php

use yii\helpers\Url;
?>

<script>
  const mainApp = new Vue({
    el: "#mainApp",
    data: {
      basePath: '<?= Yii::$app->request->baseUrl ?>/images/',
      logoImage: 'logo.png',
      isUploading: false,
    },
    mounted: function() {},
    methods: {
      selectImage: function() {
        document.getElementById('logoImage').click();
      },
      uploadImage: function() {
        const self = this;
        const file = document.getElementById('logoImage');
        if (file.files.length && file.files[0].size > <?= (int)Yii::$app->params['fileSize'] ?>) {
          errorNotification('<?= __('Allowed files up to') . ' ' . Yii::$app->params['fileSizeText'] ?>');
          return;
        }
        const formData = new FormData($("#imageForm")[0]);
        $.ajax({
          url: '<?= Url::to(['settings/add-image']) ?>',
          xhr: function() {
            let xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
              if (evt.lengthComputable) {
                self.isUploading = true;
                var percentComplete = ((evt.loaded / evt.total) * 100);
                $(".progress-bar").width(percentComplete + '%');
                $(".progress-bar").html(percentComplete + '%');
              }
            }, false);
            return xhr;
          },
          type: 'POST',
          data: formData,
          datatype: 'json',
          success: function(data) {
            self.logoImage = data + '?<?= time() ?>';
            self.isUploading = false;
            successNotification();
          },
          error: function(data) {
            self.isUploading = false;
            errorNotification(data.responseText);
          },
          cache: false,
          contentType: false,
          processData: false
        });
        return false;
      }
    }
  });
</script>