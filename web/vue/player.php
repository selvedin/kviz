<?php

use app\models\Quiz;

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$model = $id ? Quiz::findOne($id) : null;
$isNewRecord = isset($model) ? (int)$model->isNewRecord : true;
$config =  [];
if ($model && !$isNewRecord) {
  foreach ($model->config as $conf) $config[] = [
    'id' => $conf->id,
    'num_of_questions' => $conf->num_of_questions
  ];
}
?>
<script>
  //VUE APP
  const mainApp = new Vue({
    el: '#mainApp',
    data: {
      title: '<?= __('Player') ?>',
      isNewRecord: <?= $isNewRecord ?>,
      config: <?= json_encode($config) ?>,
      questions: <?= json_encode($model->generateQuestions()) ?>,
      question: {},
      results: [],
      isPlaying: true,
      showResults: false
    },
    mounted() {
      this.question = this.questions.shift();
    },
    methods: {
      runQuiz: function() {
        const elem = document.getElementById("mainApp");
        openFullscreen(elem);
        this.isPlaying = true;
      },
      answerQuestion: function(id) {
        this.results.push({
          q: this.question.id,
          a: id
        });
        if (this.questions.length) {
          this.question = this.questions.shift();
          return;
        }
        this.showResults = true;
        this.question = {};
      }
    },
    computed: {},
    watch: {}
  });
</script>