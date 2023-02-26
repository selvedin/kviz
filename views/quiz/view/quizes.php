<?php
$quizes = ['pending', 'active', 'started', 'running', 'archived'];
$types = ['warning', 'primary', 'success', 'danger', 'secondary'];
$icons = ['stopwatch', 'flag', 'rocket', 'play', 'archive'];
foreach ($quizes as $i => $type) :
  if ($perms->canView(ucfirst($type) . 'Quiz') && $model->$type && count($model->$type)) :
?>
    <div class='col-12'>
      <div class="card h-100">
        <div class="card-header bg-<?= $types[$i] ?> d-flex justify-content-between">
          <div class="card-title mb-0">
            <h5 class="mb-0 text-white">
              <i class="fas fa-<?= $icons[$i] ?>"></i>
              <?= __(ucfirst($type)) . ' ' . __('Quiz') ?>
            </h5>
            <small class="text-muted"></small>
          </div>
        </div>
        <div class="card-body pt-4">
          <div class="row">
            <div class="col-12">
              <div class="table-responsive text-nowrap">
                <table class="table">
                  <thead>
                    <?= getTableHeader() ?>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    <?php foreach ($model->$type as $k => $quiz)
                      printRow($k, $quiz);
                    ?>
                  </tbody>
                </table>
              </div>
              <hr>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php
  endif;
endforeach;
?>