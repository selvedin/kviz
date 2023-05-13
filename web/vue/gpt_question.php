<?php

use app\models\Question;
use yii\helpers\Url;

?>
<script>
  //VUE APP
  const mainApp = new Vue({
    el: '#questionGeneratorApp',
    data: {
      title: '<?= __('Question Generator') ?>',
      prompt: '',
      questionType: <?= Question::TYPE_ESSAI ?>,
      isUploading: false,
      subject: 0,
      grade: 0,
    },
    mounted() {},
    methods: {
      getContent: function(filename) {
        const self = this;
        $.get(`<?= Url::to(['gpt/get-file-content']) ?>?filename=${filename}`, function(data) {
          self.content = data;
        });
      },
      scanImage: function() {
        const self = this;
        const file = document.getElementById('scan-image');
        if (file.files.length && file.files[0].size > <?= (int)Yii::$app->params['fileSize'] ?>) {
          errorNotification('<?= __('Allowed files up to') . ' ' . Yii::$app->params['fileSizeText'] ?>');
          return;
        }

        const formData = new FormData();
        formData.append("ocr", file.files[0]);
        $.ajax({
          url: '<?= Url::to(['gpt/ocr-ajax']) ?>',
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
            self.prompt = data;
            self.isUploading = false;
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
    },
    computed: {
      canGenerate: function() {
        console.log("Razred:", this.grade);
        if (this.questionType == <?= Question::TYPE_PROMPT ?> && !Boolean(this.prompt))
          return false;
        return this.subject > 0 && this.grade > 0 && this.questionType > 0;
      }
    },
    watch: {}
  });
</script>