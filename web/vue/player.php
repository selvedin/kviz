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
      allQuestions: <?= json_encode($model->generateQuestions()) ?>,
      questions: <?= json_encode($model->generateQuestions()) ?>,
      pastQuestions: [],
      question: {},
      duration: <?= $model->duration * 60 ?>,
      questionTimeInSeconds: 5,
      questionIsStarted: false,
      results: [],
      totalCorrect: 0,
      totalPercentage: 0,
      summary: [],
      isPlaying: false,
      showResults: false,
      canAnswer: false,
    },
    mounted() {
      $('#stopwatch').hide();
      this.questionTimeInSeconds = Math.round(this.duration / this.allQuestions.length);
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
        this.showResults = false;
        $('#stopwatch').hide();
      },
      startQuestion: function() {
        $('#stopwatch').show();
        if (this.questions.length) {
          this.question = this.questions.shift();
          this.pastQuestions.push(this.question);
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
      answerQuestion: function(a, t) {
        const self = this;
        if ([1, 2].includes(self.question.question_type)) {
          self.results = self.results.filter(r => r.q != self.question.id && r.a != a);
        } else if (self.question.question_type == 3) {
          const existingIndex = self.results.findIndex(r => r.q == self.question.id && r.a == a);
          if (existingIndex > -1) {
            self.results.splice(existingIndex, 1);
            return;
          }
        }

        this.results.push({
          q: this.question.id,
          a,
          t
        });

        $(document).focus();
      },
      summarize: function() {
        const self = this;
        let correctAnswers = [];
        let userAnswers = [];
        let correctTitle = '';
        let answerTitle = '';
        self.allQuestions.forEach(q => {
          switch (+q.question_type) {
            case 1:
              correctTitle = q.options[0].is_true ? '<?= __('True') ?>' : '<?= __('False') ?>';
              answerTitle = self.results.find(r => r.q == q.id).t;
              self.summary.push({
                id: q.id,
                label: q.content,
                correct: correctTitle,
                answer: answerTitle,
                isCorrect: correctTitle == answerTitle
              })
              break;
            case 2:
              correctAnswers = [];
              q.options.filter(o => o.is_true).forEach(o => correctAnswers.push(o.content));
              correctTitle = correctAnswers.join(', ');
              answerTitle = self.results.find(r => r.q == q.id).t;
              self.summary.push({
                id: q.id,
                label: q.content,
                correct: correctTitle,
                answer: answerTitle,
                isCorrect: correctTitle == answerTitle
              })
              break;
            case 3:
              correctAnswers = [];
              userAnswers = [];
              q.options.filter(o => o.is_true).forEach(o => correctAnswers.push(o.content));
              self.results.filter(r => r.q == q.id).forEach(r => userAnswers.push(r.t));
              correctAnswers.sort();
              userAnswers.sort();
              correctTitle = correctAnswers.join(', ');
              answerTitle = userAnswers.join(', ');

              self.summary.push({
                id: q.id,
                label: q.content,
                correct: correctTitle,
                answer: answerTitle,
                isCorrect: correctTitle == answerTitle
              })
              break;
            default:
              break;
          }
        });
      }
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
    watch: {
      showResults: function(val) {
        if (val) this.summarize();
      },
      summary: function(val) {
        this.totalCorrect = this.summary.filter(s => s.isCorrect).length;
        this.totalPercentage = Math.round(this.totalCorrect / this.allQuestions.length * 100);
      },
    },
  });

  $(document).on('keyup', function(e) {
    if (e.key === 'Space' || e.keyCode === 32) {
      if (mainApp.questionIsStarted) return;
      else mainApp.startQuestion();
    }
  });
</script>