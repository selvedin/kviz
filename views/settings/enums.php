<?php

use app\helpers\Buttons;

$this->title = __('Settings') . ' - ' . __('Enumerations');
?>
<div class="container-fluid">
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title"><?= __('Enumerations') ?></h3>
        </div>
        <div class="card-body" style="min-height:80vh;">
            <div class="row">
                <div class="col-md-6" v-for="en in enums">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{en.title}}</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-default btn-sm m-0" @click="addEnum(en)" data-toggle="modal" data-target="#enums-modal">
                                    <?= __('Add') ?>
                                </button>
                                <span class="badge badge-warning">{{en.items.length}}</span>
                                <?= Buttons::toggleCard() ?>
                            </div>
                        </div>
                        <div class="card-body" style="display:none;">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th v-for="fld in en.fields">{{fld.val}}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in en.items">
                                        <td>{{index+1}}.</td>
                                        <td v-for="val in item.text_value">{{val}}</td>
                                        <td class="text-right">
                                            <a href="#" @click="editEnum(en, item)" class="btn btn-link btn-sm" data-toggle="modal" data-target="#enums-modal"><i class="fas fa-eye"></i></a>
                                            <a href="#" @click="deleteEnum(item)" class="btn btn-link btn-sm text-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="enums-modal" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?= __('Enumeration') ?> - {{en.title}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div v-for="(field, index) in en.fields" class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control field-value" :value="item.text_value ? item.text_value[index] : ''" :placeholder="field.val">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" @click="saveEnum()"><?= __('Save') ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>