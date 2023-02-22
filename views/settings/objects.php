<?php

use yii\bootstrap5\ActiveForm;

$this->title = __('Settings') . ' - ' . __('Objects');
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-10 col-sm-12 mb-1">
                        <?php ActiveForm::begin(['id' => 'settings-form']) ?>
                        <input v-model="setting.id" type="hidden" name="Settings[id]">
                        <input type="hidden" name="Settings[type]" value="Objects">
                        <input type="hidden" name="Settings[text_value]" v-model="fields">
                        <div class="form-group">
                            <div class="input-group" id="settings-name" data-target-input="nearest">
                                <input v-model="setting.name" type="text" name="Settings[name]" class="form-control" placeholder="<?= __("Object") ?>..." data-target="#settings-name">
                                <div class="input-group-append" data-target="#settings-name">
                                    <div class="input-group-text text-danger" @click="resetSetting()"><i class="fas fa-times"></i></div>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end() ?>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <button v-if="setting.name" class="btn btn-dark w-100" @click="saveSetting()">
                            <i v-if="setting.id" class="fas fa-save"></i>
                            <i v-else class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-6 col-md-12" v-for="sett in settings">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-tag"></i></span>

                                <div class="info-box-content">
                                    <h4 class="info-box-text">{{sett.name}}</h4>
                                    <div class="info-box-number">
                                        <small></small>
                                        <a href="#" @click="editSetting(sett)" class="btn btn-link btn-sm float-right" title="<?= __("Update object") ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" @click="deleteSetting(sett)" class="btn btn-link text-danger btn-sm float-right" title="<?= __("Delete object") ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= __('Required fields') ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div v-for="(field, index) in setting.text_value" class="col-12">
                        <div class="form-group">
                            <div class="input-group" :id="'field-name'+index" data-target-input="nearest">
                                <input type="text" v-model="field.val" class="form-control" :data-target="'#field-name'+index">
                                <div class="input-group-append" :data-target="'#field-name'+index">
                                    <div class="input-group-text text-danger" @click="deleteField(index)"><i class="fas fa-times"></i></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div v-if="setting.id" class="row mt-2">
                    <div class="col-10">
                        <input type="text" v-model="newField" class="form-control">
                    </div>
                    <div class="col-2 pt-1">
                        <a href="#" class="btn btn-dark btn-sm" @click="addField()" title="<?= __('Add field') ?>"><i class="fas fa-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>