<?php


use app\models\Quiz;
use yii\helpers\Url;

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$model = $id ? Quiz::findOne($id) : null;
$isNewRecord = isset($model) ? (int)$model->isNewRecord : true;
$config =  [];
if ($model && !$isNewRecord) {
  foreach ($model->config as $conf) $config[] = [
    'id' => $conf->id,
    'num_of_questions' => $conf->num_of_questions,
    'grade' => $conf->grade,
    'gradeLabel' => $conf->gradeLabel,
    'level' => $conf->level,
    'levelLabel' => $conf->levelLabel,
    'category_id' => $conf->category_id,
    'category' => $conf->category?->name
  ];
}
?>
<script>
  //VUE APP
  const mainApp = new Vue({
    el: '#quizApp',
    data: {
      title: '<?= __('Quiz') ?>',
      isNewRecord: <?= $isNewRecord ?>,
      config: <?= json_encode($config) ?>,
    },
    mounted() {},
    methods: {
      addConfig: function() {
        const num_of_questions = $('#quiz-config-num_of_questions').val();
        const grade = $('#quiz-config-grade').val();
        const gradeLabel = $('#quiz-config-grade option:selected').text();
        const level = $('#quiz-config-level').val();
        const levelLabel = $('#quiz-config-level option:selected').text();
        const category_id = $('#quiz-config-category').val();
        const category = $('#quiz-config-category option:selected').text();
        if (num_of_questions)
          this.config.push({
            num_of_questions,
            grade,
            gradeLabel,
            level,
            levelLabel,
            category_id,
            category
          });
        $('#quiz-config-num_of_questions').val(null);
        $('#quiz-config-grade').val(null);
        $('#quiz-config-level').val(null);
        $('#quiz-config-category').val(null);
      },
      removeConfig: function(id) {
        const self = this;
        Swal.fire(SWAL_DELETE_OPTIONS).then((result) => {
          if (result.isConfirmed) {
            if (!this.isNewRecord)
              $.post(`<?= Url::to(['quiz/delete-config']) ?>?id=${id}`, {},
                function(data) {
                  successNotification();
                }).fail((err) => errorNotification(err.responseJSON.message));
            this.config = this.config.filter(opt => opt.id !== id);
          }
        });
      },
      runQuiz: function() {
        alert('Running a quiz');
      }
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