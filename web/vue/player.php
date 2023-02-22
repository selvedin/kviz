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
      COLORS: ['btn-primary', 'btn-danger', 'btn-info', 'btn-warning', 'btn-success', 'btn-dark'],
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
      totalQuestions: <?= $model->num_of_questions ? $model->num_of_questions : 1 ?>,
      totalCorrect: 0,
      totalPercentage: 0,
      summary: [],
      isPlaying: false,
      showResults: false,
      canAnswer: false,
      lastAdded: null,
      isLastRight: false,
    },
    mounted() {
      $('#stopwatch').hide();
      this.questionTimeInSeconds = Math.round(this.duration / this.totalQuestions);
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
        this.questionIsStarted = false;
        this.canAnswer = false;
        // this.printResults();
      },
      printResults: function() {
        this.results.forEach(res => {
          console.log(res.leftContent, res.rightContent);
        })
      },
      answerQuestion: function(answer, content) {
        const self = this;
        if ([1, 2].includes(self.question.question_type)) {
          self.results = self.results.filter(res => res.question != self.question.id && r.answer != answer);
        } else if (self.question.question_type == 3) {
          const existingIndex = self.results.findIndex(r => r.question == self.question.id && r.answer == answer);
          if (existingIndex > -1) {
            self.results.splice(existingIndex, 1);
            return;
          }
        }

        this.results.push({
          question: this.question.id,
          answer,
          content
        });

        $(document).focus();
      },
      addPair: function(id, cont, el, isRight = false) {
        const self = this;
        if (isRight) { // ADDING RIGHT PAIR
          if (self.lastAdded) { // adding process has been started
            if (self.isLastRight) { // on starting process right side has been added first
              if (self.lastAdded == id) return; // if the same item is clicked nothing has to be changed
              //if not that we need to remove last added item - which is right and add start adding
              // with new right pair
              self.results = self.results.filter(res => res.question == self.question.id && res.right != self.lastAdded);
              self.results.push({
                question: self.question.id,
                right: id,
                rightContent: cont,
                left: null,
                leftContent: null
              });
              self.lastAdded = id;
            } else {
              // last added is not the right and in this case we need to find last added left pair and add right to it
              const existing = self.results.findIndex(res => res.question == self.question.id && res.left == self.lastAdded);
              if (existing > -1) {
                self.results[existing].right = id;
                self.results[existing].rightContent = cont;
                self.lastAdded = null;
              } else {
                errorNotification('Left pair does not exist');
              }

            }
          } else {
            // we are starting adding new pair with right as starting pair
            const existing = self.results.findIndex(res => res.question == self.question.id && res.right == id);
            if (existing > -1) {
              //in case that we have this pair already added we need to remove this pair from pairs
              self.results.splice(existing, 1);
            } else {
              // if this pair not added to results we add it
              self.results.push({
                question: self.question.id,
                right: id,
                rightContent: cont,
                left: null,
                leftContent: null
              });
              self.lastAdded = id;
            }
          }
          self.isLastRight = true;
        } else { // ADDING LEFT PAIR
          if (self.lastAdded) { // adding process has been started
            if (!self.isLastRight) { // on starting process left side has been added first
              if (self.lastAdded == id) return; // if the same item is clicked nothing has to be changed
              //if not that we need to remove last added item - which is left and add start adding
              // with new right pair
              self.results = self.results.filter(res => res.question == self.question.id && res.left != self.lastAdded);
              self.results.push({
                question: self.question.id,
                right: null,
                rightContent: null,
                left: id,
                leftContent: cont
              });
              self.lastAdded = id;
            } else {
              // last added is not the left and in this case we need to find last added right pair and add left to it
              const existing = self.results.findIndex(res => res.question == self.question.id && res.right == self.lastAdded);
              if (existing > -1) {
                self.results[existing].left = id;
                self.results[existing].leftContent = cont;
                self.lastAdded = null;
              } else {
                errorNotification('Left pair does not exist');
              }
            }
          } else {
            // we are starting adding new pair with lefy as starting pair
            const existing = self.results.findIndex(res => res.question == self.question.id && res.left == id);
            if (existing > -1) {
              //in case that we have this pair already added we need to remove this pair from pairs
              self.results.splice(existing, 1);
            } else {
              // if this pair has not been added to results we are adding it
              self.results.push({
                question: self.question.id,
                right: null,
                rightContent: null,
                left: id,
                leftContent: cont
              });
              self.lastAdded = id;
            }
          }
          self.isLastRight = false;
        }
      },
      summarize: function() {
        const self = this;
        let correctAnswers = [];
        let userAnswers = [];
        let correctTitle = '';
        let answerTitle = '';
        self.allQuestions.forEach(question => {
          switch (+question.question_type) {
            case 1:
              correctTitle = question.options[0].is_true ? '<?= __('True') ?>' : '<?= __('False') ?>';
              answerTitle = self.results.find(res => res.question == question.id)?.content;
              self.summary.push({
                id: question.id,
                label: question.content,
                correct: correctTitle,
                answer: answerTitle,
                isCorrect: correctTitle == answerTitle
              })
              break;
            case 2:
              correctAnswers = [];
              question.options.filter(option => option.is_true).forEach(option => correctAnswers.push(option.content));
              correctTitle = correctAnswers.join(', ');
              answerTitle = self.results.find(res => res.question == question.id)?.content;
              self.summary.push({
                id: question.id,
                label: question.content,
                correct: correctTitle,
                answer: answerTitle,
                isCorrect: correctTitle == answerTitle
              })
              break;
            case 3:
              correctAnswers = [];
              userAnswers = [];
              question.options.filter(option => option.is_true).forEach(option => correctAnswers.push(option.content));
              self.results.filter(res => res.question == question.id).forEach(res => userAnswers.push(res.content));
              correctAnswers.sort();
              userAnswers.sort();
              correctTitle = correctAnswers.join(', ');
              answerTitle = userAnswers.join(', ');

              self.summary.push({
                id: question.id,
                label: question.content,
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
          // 'btn-quiz-active': true
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