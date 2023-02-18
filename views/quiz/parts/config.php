i<?php

  use app\helpers\Icons;
  use app\models\Categories;
  use app\models\Question;
  use app\widgets\CardView;
  use kartik\select2\Select2;
  use yii\bootstrap5\Html;

  $title = __('Config');
  $numLabel = __('Num Of Questions');
  $gradeLabel = __('Grade');
  $leveLabel = __('Level');
  $categoryLabel = __('Category');

  $config = Html::tag(
    'div',
    Html::label($numLabel, 'quiz-config-num_of_questions', ['class' => 'control-label']) .
      Html::textInput(
        'QuizConfig[num_of_questions]',
        '',
        ['type' => 'number', 'min' => 1, 'id' => 'quiz-config-num_of_questions', 'class' => 'form-control']
      ),
    ['class' => 'col-sm-12 col-md-2']
  );

  $config .= Html::tag(
    'div',
    Html::label($gradeLabel, 'quiz-config-grade', ['class' => 'control-label']) .
      Select2::widget(
        [
          'name' => 'QuizConfig[grade]',
          'data' => Question::Grades(),
          'options' => ['id' => 'quiz-config-grade', 'placeholder' => __('Select grade')],
          'pluginOptions' => [
            'allowClear' => true
          ]
        ]
      ),
    ['class' => 'col-sm-12 col-md-3']
  );

  $config .= Html::tag(
    'div',
    Html::label($leveLabel, 'quiz-config-level', ['class' => 'control-label']) .
      Select2::widget(
        [
          'name' => 'QuizConfig[level]',
          'data' => Question::Levels(),
          'options' => ['id' => 'quiz-config-level', 'placeholder' => __('Select level')],
          'pluginOptions' => [
            'allowClear' => true
          ]
        ]
      ),
    ['class' => 'col-sm-12 col-md-3']
  );

  $config .= Html::tag(
    'div',
    Html::label($leveLabel, 'quiz-config-category', ['class' => 'control-label']) .
      Select2::widget(
        [
          'name' => 'QuizConfig[category]',
          'data' => Categories::getRoot(),
          'options' => ['id' => 'quiz-config-category', 'placeholder' => __('Select category')],
          'pluginOptions' => [
            'allowClear' => true
          ]
        ]
      ),
    ['class' => 'col-sm-12 col-md-3']
  );

  $config .= Html::tag('div', Html::a(
    Icons::faIcon('plus'),
    'javascript:void(0)',
    ['class' => 'btn btn-primary', '@click' => 'addConfig()']
  ), ['class' => 'col-sm-12 col-md-1 text-end mt-4']);

  $config .= Html::tag(
    'div',
    "<table class='table table-striped'><thead><tr><th>#</th>
    <th>$numLabel</th><th>$gradeLabel</th><th>$leveLabel</th><th>$categoryLabel</th>
    <th></th></tr></thead>
  <tbody><tr v-for='(conf,ind) in config'>
  <td>
  <input type='hidden' :name='\"Quiz[QuizConfig][\"+ind+\"][id]\"' :value='conf.id' readonly/>
  <input type='hidden' :name='\"Quiz[QuizConfig][\"+ind+\"][num_of_questions]\"' :value='conf.num_of_questions' readonly/>
  <input type='hidden' :name='\"Quiz[QuizConfig][\"+ind+\"][grade]\"' :value='conf.grade' readonly/>
  <input type='hidden' :name='\"Quiz[QuizConfig][\"+ind+\"][level]\"' :value='conf.level' readonly/>
  <input type='hidden' :name='\"Quiz[QuizConfig][\"+ind+\"][category_id]\"' :value='conf.category_id' readonly/>

  {{ind+1}}.</td>
  <td>{{conf.num_of_questions}}</td>
  <td>{{conf.gradeLabel}}</td>
  <td>{{conf.levelLabel}}</td>
  <td>{{conf.category}}</td>
  <td class='text-end'><a href='javascript:void(0)' class='btn rounded-pill btn-icon btn-outline-danger btn-sm' @click='removeConfig(conf.id)'>
  <i class='fa fa-trash'></i></a></td>
  </tr>
  </tbody></table></div></div>",
    ['id' => 'configList', 'class' => 'col-md-12 mt-4']
  );

  echo  Html::tag('div', CardView::widget([
    'title' => $title,
    'buttons' => [],
    'content' => Html::tag('div', $config, ['class' => 'row m-4'])
  ]), array_merge(['class' => 'col-md-12 quizFormPart'], ['id' => 'quiz-config-card']));
