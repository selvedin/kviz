<?php

use app\helpers\Buttons;
use app\models\Categories;
use app\models\Grade;
use app\widgets\CardView;
use yii\bootstrap5\Html;

$this->title = __('Ocr List');
$id = Yii::$app->user->id;
$folder = Yii::getAlias('@runtime') . "/ocrs/$id/";
?>
<div id="ocrApp">
  <?= CardView::begin($this->title, 'info', [
    Buttons::customButton(__('Ocr'), ['gpt/ocr'], [], 'dark rounded-pill btn-sm')
  ]) ?>
  <br />
  <div class="row">
    <div class="col-md-6">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th><?= __('Subject') ?></th>
              <th><?= __('Grade') ?></th>
              <th><?= __('Unit group') ?></th>
              <th><?= __('Unit item') ?></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($files as $key => $file) {
              $content = file_get_contents("$folder/$file");
              $content = explode("\n", $content);
              if (count($content) < 1) continue;
              $title = explode(',', $content[0]);
              echo Html::tag(
                'tr',
                Html::tag('td', $key + 1 . ".") .
                  Html::tag('td', Categories::getName(str_replace('SUBJECT:', '', $title[0]))) .
                  Html::tag('td', Grade::getName(str_replace('GRADE:', '', $title[1]))) .
                  Html::tag('td', str_replace('UNIT_TITLE:', '', $title[2])) .
                  Html::tag('td', str_replace('LESSON_TITLE:', '', $title[3])) .
                  Html::tag(
                    'td',
                    Buttons::icon('eye', 'info', __('Load content'), ['@click' => "getContent('$file')"]) .
                      Buttons::icon(
                        'trash',
                        'danger',
                        __('Delete file'),
                        ['data' => [
                          'confirm' => __('Are you sure you want to delete this item?'),
                          'method' => 'post',
                        ]],
                        ['gpt/delete-ocr', 'file' => $file]
                      ),
                    ['class' => 'text-end']
                  )
              );
            } ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-6 p-4">
      <textarea class="form-control" v-model='content' rows="15"></textarea>
    </div>
  </div>
  <?= CardView::end(); ?>
</div>