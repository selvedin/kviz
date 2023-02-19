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
      questionTimeInSeconds: 5,
      questionIsStarted: false,
      results: [],
      isPlaying: false,
      showResults: false,
      canAnswer: false,
    },
    mounted() {
      $('#stopwatch').hide();
    },
    methods: {
      runQuiz: function() {
        const elem = document.getElementById("mainApp");
        openFullscreen(elem);
        this.startQuestion();
        this.isPlaying = true;
      },
      stopQuiz: function() {
        document.exitFullscreen();
        this.isPlaying = false;
        $('#stopwatch').hide();
      },
      startQuestion: function() {
        $('#stopwatch').show();
        if (this.questions.length) {
          this.question = this.questions.shift();
          this.questionIsStarted = true;
          startTimer(this.questionTimeInSeconds);
          this.canAnswer = true;
          return;
        }
        this.canAnswer = false;
        this.showResults = true;
        this.question = {};
      },
      stopQuestion: function() {
        $('#stopwatch').hide();
        // this.questionTimeInSeconds = 10;
        this.questionIsStarted = false;
        this.canAnswer = false;
      },
      answerQuestion: function(id) {
        const self = this;
        if ([1, 2].includes(self.question.question_type)) {
          self.results = self.results.filter(r => r.q != self.question.id && r.a != id);
        } else if (self.question.question_type == 3) {
          const existingIndex = self.results.findIndex(r => r.q == self.question.id && r.a == id);
          if (existingIndex > -1) {
            self.results.splice(existingIndex, 1);
            return;
          }
        }

        this.results.push({
          q: this.question.id,
          a: id
        });

        $(document).focus();
      },
    },
    computed: {
      classObject() {
        return {
          active: false,
          'ms-auto': true,
          'me-auto': true,
          'rounded-pill': true,
          'btn-quiz': true,
        }
      }
    },
    watch: {},
  });

  $(document).on('keyup', function(e) {
    if (e.key === 'Space' || e.keyCode === 32) {
      if (mainApp.questionIsStarted) return;
      else mainApp.startQuestion();
    }
  });
</script>