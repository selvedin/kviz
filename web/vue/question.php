<?php

use app\models\Question;
use yii\helpers\Url;

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$model = $id ? Question::findOne($id) : null;
$isNewRecord = isset($model) ? (int)$model->isNewRecord : true;
$options = [];
if ($model && !$isNewRecord) {
  foreach ($model->options as $opt) $options[] = ['id' => $opt->id, 'content' => $opt->content, 'is_true' => $opt->is_true];
}
?>
<script>
  //VUE APP
  const mainApp = new Vue({
    el: '#questionApp',
    data: {
      title: '<?= __('Question') ?>',
      isNewRecord: <?= $isNewRecord ?>,
      questionType: <?= $model ? (int)$model->question_type : 0 ?>,
      contentType: <?= $model ? (int)$model->content_type : 0 ?>,
      options: <?= json_encode($options) ?>
    },
    mounted() {},
    methods: {
      addOption: function() {
        const content = $('#options-content').val();
        const is_true = $('#options-is_true').is(':checked');
        if (content)
          this.options.push({
            content,
            is_true,
          });
        $('#options-content').val(null);
        $('#options-is_true').prop('checked', false);
      },
      removeOption: function(id) {
        const self = this;
        Swal.fire(SWAL_DELETE_OPTIONS).then((result) => {
          if (result.isConfirmed) {
            if (!this.isNewRecord)
              $.post(`<?= Url::to(['question/delete-options']) ?>?id=${id}`, {}, function(data) {
                successNotification();
              }).fail((err) => errorNotification(err.responseJSON.message));
            this.options = this.options.filter(opt => opt.id !== id);
          }
        });
      },
    },
    computed: {},
    watch: {}
  });

  function showToast(message) {
    Swal.fire({
      position: 'top-end',
      icon: 'info',
      title: message,
      showConfirmButton: false,
      timer: 1500
    })
  }
</script>