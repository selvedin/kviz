<?php

use app\models\Question;
use yii\helpers\Url;

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$model = $id ? Question::findOne($id) : null;
$isNewRecord = isset($model) ? (int)$model->isNewRecord : true;
$options = $pairs = [];
if ($model && !$isNewRecord) {
  foreach ($model->options as $opt) $options[] = ['id' => $opt->id, 'content' => $opt->content, 'is_true' => $opt->is_true];
  foreach ($model->pairs as $pair) $pairs[] = ['id' => $pair->id, 'one' => $pair->one, 'two' => $pair->two];
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
      options: <?= json_encode($options) ?>,
      pairs: <?= json_encode($pairs) ?>,
    },
    mounted() {},
    methods: {
      addOption: function(isPair = false) {
        if (isPair) {
          const one = $('#pairs-one').val();
          const two = $('#pairs-two').val();
          if (one && two)
            this.pairs.push({
              one,
              two,
            });
          $('#pairs-one').val(null);
          $('#pairs-two').val(null);
          return;
        }
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
      removeOption: function(id, isPair = false) {
        const self = this;
        Swal.fire(SWAL_DELETE_OPTIONS).then((result) => {
          if (result.isConfirmed) {
            if (!this.isNewRecord)
              $.post(`<?= Url::to(['question/delete-options']) ?>?id=${id}&isPair=${isPair}`, {},
                function(data) {
                  successNotification();
                }).fail((err) => errorNotification(err.responseJSON.message));
            if (isPair) this.pairs = this.pairs.filter(opt => opt.id !== id);
            else this.options = this.options.filter(opt => opt.id !== id);
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