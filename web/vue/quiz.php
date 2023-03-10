<?php


use app\models\Quiz;
use yii\helpers\Url;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$model = $id ? Quiz::findOne($id) : null;
$isNewRecord = isset($model) ? (int)$model->isNewRecord : 0;
$config =  [];
if ($model && !$isNewRecord) {
  foreach ($model->config as $conf) $config[] = [
    'id' => $conf->id,
    'num_of_questions' => $conf->num_of_questions,
    'question_type' => $conf->question_type,
    'questionType' => $conf->questionType,
    'grade' => $conf->grade,
    'gradeLabel' => $conf->gradeLabel?->title,
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
      tempId: null,
      userSummary: []
    },
    mounted() {},
    methods: {
      addConfig: function() {
        const num_of_questions = $('#quiz-config-num_of_questions').val();
        const grade = $('#quiz-config-grade').val();
        const question_type = $('#quiz-config-question_type').val();
        const questionType = $('#quiz-config-question_type  option:selected').text();
        const gradeLabel = $('#quiz-config-grade option:selected').text();
        const level = $('#quiz-config-level').val();
        const levelLabel = $('#quiz-config-level option:selected').text();
        const category_id = $('#quiz-config-category').val();
        const category = $('#quiz-config-category option:selected').text();
        if (num_of_questions)
          this.config.push({
            num_of_questions,
            question_type,
            questionType,
            grade,
            gradeLabel,
            level,
            levelLabel,
            category_id,
            category
          });
        $('#quiz-config-num_of_questions, #quiz-config-question_type, #quiz-config-grade, #quiz-config-level, #quiz-config-category').val(null);
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
      addCompetitor: function() {
        const self = this;
        const competitor = $('#quiz-competitor').val();
        if (competitor) {
          $.post(`<?= Url::to(['quiz-temp/add-competitor']) ?>?id=${self.tempId}`, {
              quiz_id: <?= $id ?>,
              competitor_id: competitor
            },
            function(data) {
              successNotification();
              window.location.reload();
            }).fail((err) => errorNotification(err.responseJSON.message));
        }
      },
      removeCompetitor: function(id) {
        const self = this;
        Swal.fire(SWAL_DELETE_OPTIONS).then((result) => {
          if (result.isConfirmed) {
            $.post(`<?= Url::to(['quiz-temp/delete-competitor']) ?>?id=${id}`, {},
              function(data) {
                successNotification();
                window.location.reload();
              }).fail((err) => errorNotification(err.responseJSON.message));
          }
        });
      },
      toggleModal: function(id) {
        this.tempId = id;
        $('#competitorsModal').modal('show');
      },
      getUserSummary: function(id) {
        const self = this;
        $.get(`<?= Url::to(['quiz/get-user-summary']) ?>?id=${id}`, function(data) {
          self.userSummary = data;
          $('#summaryModal').modal('show');
        }).fail(error => toastr.error(error.message))
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