<?php

use app\models\QuizResults;
use app\models\QuizTemp;
use yii\helpers\Url;

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$model = $id ? QuizTemp::findOne($id) : null;

$allQuestions = unserialize($model->quiz);
$tempId = $id;
$seconds = isset($_GET['test']) ? 10 : 60;
$didPlay = $model->isPlayed();

?>
<script>
  //VUE APP
  const mainApp = new Vue({
    el: '#mainApp',
    data: {
      title: '<?= __('Player') ?>',
      tempId: <?= $tempId ? $tempId : 0 ?>, // running quiz
      COLORS: ['btn-primary', 'btn-danger', 'btn-info', 'btn-warning',
        'btn-success', 'btn-dark'
      ], // colors for matched pairs
      allQuestions: <?= json_encode($allQuestions) ?>, // after filtering questions keeps track of loaded questions
      questions: <?= json_encode($allQuestions) ?>, // questions for traversal
      pastQuestions: [], // keeping track of passed questions
      question: {}, // current Question 
      duration: <?= $model->quizObject->duration * $seconds ?>, // duration of the Quiz
      questionTimeInSeconds: 5, //default time for question to answer
      questionIsStarted: false,
      results: [],
      totalQuestions: <?= $model->quizObject->num_of_questions ? $model->quizObject->num_of_questions : 1 ?>,
      totalCorrect: 0,
      totalPercentage: 0,
      summary: [],
      isPlaying: false,
      isRunning: false,
      showResults: false,
      canAnswer: false,
      lastAdded: null,
      isLastRight: false,
      didPlay: <?= $didPlay ? 'true' : 'false' ?>,
      isRemote: <?= (int)$model->quizObject->quiz_type - 1 ?>,
      isModerator: <?= isset($_GET['moderate']) ? 1 : 0 ?>,
      started: [],
      runningQuiz: null,
      nextQuestion: null,
    },
    mounted() {
      $('#stopwatch').hide();
      if (this.didPlay) toastr.error('You already played this quiz');
      this.questionTimeInSeconds = Math.round(this.duration / this.questions
        .length);
    },
    methods: {
      saveToLocal: function() {
        localStorage.setItem(`QUIZ_<?= $id ?>_RESULTS`, JSON.stringify(this.results))
        localStorage.setItem(`QUIZ_<?= $id ?>_QUESTIONS`, JSON.stringify(this.questions))
        localStorage.setItem(`QUIZ_<?= $id ?>_QUESTION`, JSON.stringify(this.question))
      },
      getFromLocal: function() {
        localStorage.setItem(`QUIZ_<?= $id ?>_RESULTS`, JSON.stringify(this.results))
        localStorage.setItem(`QUIZ_<?= $id ?>_QUESTIONS`, JSON.stringify(this.questions))
        localStorage.setItem(`QUIZ_<?= $id ?>_QUESTION`, JSON.stringify(this.question))
      },
      clearLocal: function() {
        localStorage.removeItem(`QUIZ_<?= $id ?>_RESULTS`);
        localStorage.removeItem(`QUIZ_<?= $id ?>_QUESTIONS`);
        localStorage.removeItem(`QUIZ_<?= $id ?>_QUESTION`);
      },
      runQuiz: function() {
        const elem = document.getElementById("mainApp");
        openFullscreen(elem);
        this.isRunning = true;
        if (this.isRemote) return;
        this.startQuestion();
        this.isPlaying = true;
      },
      stopQuiz: function() {
        document.exitFullscreen();
        this.isPlaying = false;
        this.isRunning = false;
        this.showResults = false;
        $('#stopwatch').hide();
        window.location = '<?= Url::to(['site/home']) ?>'
      },
      startQuestion: function() {
        const self = this;
        $('#stopwatch').show();
        $('#input-result-text').val(null);
        if (self.questions.length) {
          self.question = self.questions.shift();
          self.pastQuestions.push(self.question);
          self.questionIsStarted = true;
          startTimer(self.questionTimeInSeconds);
          self.canAnswer = true;
          $.post(`<?= Url::to(['quiz/start']) ?>?id=<?= $id ?>&question=${self.question.id}`, {
            results: self.results
          }, function(data) {}).fail(error => console.error(error))
          return;
        }
        self.canAnswer = false;
        self.showResults = true;
        self.questionIsStarted = false;
        self.question = {};
        $('#stopwatch').hide();
        if (!self.isRemote || !this.isModerator) {
          $.post(`<?= Url::to(['quiz/save-results', 'id' => $id]) ?>&temp=${self.tempId}`, {
            results: self.results
          }, function(data) {
            self.summary = data;
          }).fail(error => console.error(error))
        } else {
          $.post(`<?= Url::to(['quiz/stop', 'id' => $id]) ?>`, {}, function(data) {
            window.location = `<?= Url::to(['site/home']) ?>`;
          }).fail(error => console.error(error))
        }
      },
      stopQuestion: function() {
        $('#stopwatch').hide();
        this.questionIsStarted = false;
        this.canAnswer = false;
        this.nextQuestion = null;
      },
      answerQuestion: function(answer, content = '') {
        const self = this;
        if ([1, 2].includes(self.question.question_type)) {
          self.results = self.results.filter(res => res.question != self
            .question.id);
        } else if (self.question.question_type == 3) { // MULTI
          //TODO - nesto ne kupi rezultate - kad je kratko vrijeme - brzo se odgovara
          // vidjeti da se ne moze proci dalje kad se klikne dok se ne ubiljeze rezultati
          const existingIndex = self.results.findIndex(res => res
            .question == self.question.id && res.answer == answer);
          if (existingIndex > -1) {
            self.results.splice(existingIndex, 1);
            return;
          }
        } else if (self.question.question_type == 5) { //input result
          const existing = self.results.find(res => res.question == self.question.id);
          if (existing) {
            existing.answer = answer.target.value;
            existing.content = answer.target.value;
            return;
          }
          self.results.push({
            question: self.question.id,
            answer: answer.target.value,
            content: answer.target.value
          });
          return;
        }
        const newAnswer = {
          question: self.question.id,
          answer,
          content
        };
        self.results.push(newAnswer);
        $(document).focus();
      },
      addPair: function(id, cont, el, isRight = false, index = 0) { //question_type = 4 - JOIN PAIRS
        const self = this;
        if (isRight) { // ADDING RIGHT PAIR
          if (self.lastAdded) { // adding process has been started
            if (self
              .isLastRight
            ) { // on starting process right side has been added first
              if (self.lastAdded == id)
                return; // if the same item is clicked nothing has to be changed
              //if not that we need to remove last added item - which is right and add start adding
              // with new right pair
              self.results = self.results.filter(res => res.question == self
                .question.id && res.right != self.lastAdded);
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
              const existing = self.results.findIndex(res => res.question ==
                self.question.id && res.left == self.lastAdded);
              if (existing > -1) {
                self.results[existing].right = id;
                self.results[existing].rightContent = cont;
                self.lastAdded = null;
              } else {
                if (self.questions.length)
                  errorNotification('Left pair does not exist');
              }
            }
          } else {
            // we are starting adding new pair with right as starting pair
            const existing = self.results.findIndex(res => res.question ==
              self.question.id && res.right == id);
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
            if (!self
              .isLastRight
            ) { // on starting process left side has been added first
              if (self.lastAdded == id)
                return; // if the same item is clicked nothing has to be changed
              //if not that we need to remove last added item - which is left and add start adding
              // with new right pair
              self.results = self.results.filter(res => res.question == self
                .question.id && res.left != self.lastAdded);
              self.results.push({
                index,
                question: self.question.id,
                right: null,
                rightContent: null,
                left: id,
                leftContent: cont
              });
              self.lastAdded = id;
            } else {
              // last added is not the left and in this case we need to find last added right pair and add left to it
              const existing = self.results.findIndex(res => res.question ==
                self.question.id && res.right == self.lastAdded);
              if (existing > -1) {
                self.results[existing].left = id;
                self.results[existing].index = index;
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
                index,
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
      getPairIndex: function(id, isRight = false) {
        let result;
        if (isRight)
          result = this.results.find(res => res.question == this.question.id && res.right == id);
        else
          result = this.results.find(res => res.question == this.question.id && res.left == id);
        return result ? result.index : null;
      }
    },
    computed: {
      classObject() {
        return {
          'active': false,
          'ms-auto': true,
          'me-auto': true,
          'rounded-pill': true,
          'btn-quiz': true,
          // 'btn-quiz-active': true
        }
      },
      showQuestion: function() {
        return this.question.id && !this.showResults;
      },
      showInfo: function() {
        return !this.question.id;
      },
      showRunButton: function() {
        return !this.isRemote && !this.showResults;
      },
      disableRunButton: function() {
        return !this.questions.length || this.didPlay;
      },
      showSummary: function() {
        return this.showResults && !this.questions.length;
      },
      showQuizDetails: function() {
        return !this.question.id && !this.isRunning;
      }
    },
    watch: {
      summary: function(val) {
        this.totalCorrect = this.summary.items.filter(s => s.isCorrect).length;
        this.totalPercentage = Math.round(this.totalCorrect / this
          .allQuestions.length * 100);
      },
      runningQuiz: function(val) {
        console.log(val);
      },
      started: function(newVal, oldVal) {
        const quizStarted = newVal[this.tempId];
        if (quizStarted == 'stopped') {
          this.startQuestion();
          this.isRunning = false;
          this.nextQuestion = null;
          return;
        }
        if (oldVal[this.tempId] != newVal[this.tempId] && !this.isModerator && this.nextQuestion === null && quizStarted) {
          if (newVal[this.tempId] == undefined)
            this.isRunning = true;
          this.nextQuestion = newVal[this.tempId];
          this.startQuestion();
        }
      }
    },
  });

  $(document).on('keyup', function(e) {
    if (e.key === 'Space' || e.keyCode === 32) {
      if (mainApp.questionIsStarted || (mainApp.isRemote && !mainApp.isModerator)) return;
      else mainApp.startQuestion();
    }
  });
</script>