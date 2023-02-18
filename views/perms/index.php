<?php

use app\models\Perms;
use app\models\Roles;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = __("Permissions");
$roles = Roles::getNames();
$perms = Perms::getObjects();
ActiveForm::begin(['id' => 'permissions-form']);
?>
<div id="permissionsApp">
  <div class="card card-secondary">
    <div class="card-body">
      <div class='row'>
        <div class="col-md-4">

          <?= Html::label(__('Object') . ':', 'params-object', ['class' => 'control-label']) ?>
          <?= Select2::widget(
            [
              'name' => 'object',
              'data' => $perms ? $perms : [],
              'options' => [
                'placeholder' => __("Select object"), 'class' => 'form-control', 'v-model' => 'permObject',
                'onchange' => 'mainApp.permObject=this.value'
              ],
              'pluginOptions' => [
                'allowClear' => true
              ],
            ]
          );
          ?>

        </div>
        <div class="col-md-4">
          <?= Html::label(__('Role') . ':', 'params-role', ['class' => 'control-label']) ?>
          <?= Select2::widget(
            [
              'name' => 'role',
              'data' => $roles ? $roles : [],
              'options' => [
                'placeholder' => __("Select role"), 'class' => 'form-control', 'v-model' => 'role',
                'onchange' => 'mainApp.role=this.value'
              ],
              'pluginOptions' => [
                'allowClear' => true
              ],
            ]
          );
          ?>
        </div>
        <div class="col-md-3">
          <?= Html::label(__('Permission') . ':', 'params-permission', ['class' => 'control-label']) ?>
          <?= Select2::widget(
            [
              'name' => 'permission',
              'data' => PERMISSION_LEVELS,
              'options' => [
                'id' => 'permissionsLevel',
                'class' => 'form-control', 'v-model' => 'permission',
                'onchange' => 'mainApp.permission=this.value'
              ],
              'pluginOptions' => [
                'allowClear' => false
              ],
            ]
          );
          ?>
        </div>
        <div class="col-md-1 pt-4">
          <button v-if="permObject && role" type="button" class="btn btn-primary mt-2" @click="savePermissions()"><i class="fa fa-save"></i></button>
        </div>
      </div>
    </div>
  </div>
  <div v-if="Object.keys(rolePermissions).length" class="card card-info">
    <div class="card-header">
      <h3 class="card-title">{{roles[role]}} <?= __('has following permissions') ?>:</h3>
    </div>
    <div class="card-body">
      <div class='row'>
        <div class="col-12">
          <table class='table table-striped'>
            <thead>
              <tr>
                <th><?= __('Object') ?></th>
                <th><?= __('Level') ?></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(perm, index) in rolePermissions">
                <td>{{index}}</td>
                <td>{{levels[perm]}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php ActiveForm::end(); ?>