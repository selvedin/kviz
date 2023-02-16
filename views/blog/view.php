<?php

use app\helpers\Buttons;
use app\models\Blog;
use app\models\User;
use app\widgets\CardView;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Blog $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => __('Blog'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$publishedButton = User::isAdmin() ?
    ($model->status == Blog::STATUS_PUBLISHED ?
        Buttons::customButton(
            __('Unpublish'),
            ['blog/publish', 'id' => $model->id, 'published' => false],
            [
                'data-method' => 'POST',
                'data-confirm' => __('Are You sure')
            ],
            'outline-danger rounded-pill'
        ) :
        Buttons::customButton(
            __('Publish'),
            ['blog/publish', 'id' => $model->id, 'published' => true],
            [
                'data-method' => 'POST',
                'data-confirm' => __('Are You sure')
            ],
            'outline-success rounded-pill'
        )
    ) : NULL;
?>
<?= CardView::begin([
    'title' => $this->title,
    'buttons' => [
        Buttons::List(),
        Buttons::Pdf($model->id),
        __isUser(Buttons::Update('id', $model->id)),
        $publishedButton
    ],
]) ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th colspan="2">
                <h3><?= $model->title ?></h3>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="col-md-2 fw-bold"><?= __('Title') ?></td>
            <td class="col-md-10"><?= $model->title ?></td>
        </tr>
        <tr>
            <td class="col-md-2 fw-bold"><?= __('Author') ?></td>
            <td class="col-md-10"><?= $model->author ?></td>
        </tr>
        <tr>
            <td class="col-md-2 fw-bold"><?= __('Status') ?></td>
            <td class="col-md-10"><?= $model->getStatuses()[$model->status] ?></td>
        </tr>
        <tr>
            <td class="col-md-2 fw-bold"><?= __('Summary') ?></td>
            <td class="col-md-10"><?= $model->summary ?></td>
        </tr>
        <tr>
            <td class="col-md-2 fw-bold"><?= __('Content') ?></td>
            <td class="col-md-10"><?= $model->content ?></td>
        </tr>
    </tbody>
</table>

<?= CardView::end() ?>