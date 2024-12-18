<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\FilterHelper;

/** @var yii\web\View $this */
/** @var app\models\LotSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $statuses */
/** @var array $customers */
/** @var array $warehouses */
/** @var array $companies */

$this->title = 'All Lots';
?>
<div class="site-all-lots">
    <h1><?= Html::encode($this->title) ?></h1>

<!-- Форма поиска -->
<form method="get" action="<?= Url::to(['site/all-lots']) ?>" class="mb-3">
    <div class="input-group">
        <?= Html::activeTextInput($searchModel, 'search', ['class' => 'form-control', 'placeholder' => 'Type VIN, Lot or Auto']) ?>
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
    </div>
</form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        Status
                        <?= Html::beginForm(['site/all-lots'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[status]', $searchModel->status, ['' => 'All'] + $statuses, [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>Auto</th>
                    <th>VIN</th>
                    <th>Lot</th> 
                    <th>
                        Company
                        <?= Html::beginForm(['site/all-lots'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[company_id]', $searchModel->company_id, ['' => 'All'] + ArrayHelper::map($companies, 'id', 'name'), [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>
                        Customer
                        <?= Html::beginForm(['site/all-lots'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[customer_id]', $searchModel->customer_id, ['' => 'All'] + ArrayHelper::map($customers, 'id', 'name'), [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>
                        Warehouse
                        <?= Html::beginForm(['site/all-lots'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[warehouse_id]', $searchModel->warehouse_id, ['' => 'All'] + ArrayHelper::map($warehouses, 'id', 'name'), [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>Keys</th>
                    <th>BOS</th>
                    <th>Title</th>
                    <th>Photo A
                        <?= Html::beginForm(['site/all-lots'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[photoA_filter]', $searchModel->photoA_filter, ['' => 'All', 'Yes' => 'Yes', 'No' => 'No'], [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>Photo D
                        <?= Html::beginForm(['site/all-lots'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[photoD_filter]', $searchModel->photoD_filter, ['' => 'All', 'Yes' => 'Yes', 'No' => 'No'], [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>Photo W
                        <?= Html::beginForm(['site/all-lots'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[photoW_filter]', $searchModel->photoW_filter, ['' => 'All', 'Yes' => 'Yes', 'No' => 'No'], [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>Photo L
                        <?= Html::beginForm(['site/all-lots'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[photoL_filter]', $searchModel->photoL_filter, ['' => 'All', 'Yes' => 'Yes', 'No' => 'No'], [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataProvider->getModels() as $lot): ?>
                    <tr>
                        <td><?= Html::encode($lot->status) ?></td>
                        <td><?= Html::encode($lot->auto) ?></td>
                        <td><?= Html::encode($lot->vin) ?></td>
                        <td><?= Html::encode($lot->lot) ?></td>
                        <td><?= Html::encode($lot->company->name) ?></td>
                        <td><?= Html::encode($lot->customer->name) ?></td>
                        <td><?= Html::encode($lot->warehouse->name) ?></td>
                        <td><?= $lot->has_keys ? '<i class="fas fa-check"></i>' : '' ?></td>
                        <td><?= $lot->bos ? Html::a('<i class="fas fa-check"></i>', ['site/view-pdf', 'id' => $lot->id, 'type' => 'bos'], ['target' => '_blank']) : '' ?></td>
                        <td><?= $lot->title ? Html::a('<i class="fas fa-check"></i>', ['site/view-pdf', 'id' => $lot->id, 'type' => 'title'], ['target' => '_blank']) : '' ?></td>
                        <td>
                            <?php $photoACount = $lot->getPhotoAFileCount(); ?>
                            <?= $photoACount > 0 ? Html::a('<span class="photo-count-circle">' . $photoACount . '</span>', ['site/gallery', 'id' => $lot->id, 'type' => 'a'], ['target' => '_blank']) : '' ?>
                        </td>
                        <td>
                            <?php $photoDCount = $lot->getPhotoDFileCount(); ?>
                            <?= $photoDCount > 0 ? Html::a('<span class="photo-count-circle">' . $photoDCount . '</span>', ['site/gallery', 'id' => $lot->id, 'type' => 'd'], ['target' => '_blank']) : '' ?>
                        </td>
                        <td>
                            <?php $photoWCount = $lot->getPhotoWFileCount(); ?>
                            <?= $photoWCount > 0 ? Html::a('<span class="photo-count-circle">' . $photoWCount . '</span>', ['site/gallery', 'id' => $lot->id, 'type' => 'w'], ['target' => '_blank']) : '' ?>
                        </td>
                        <td>
                            <?php $photoLCount = $lot->getPhotoLFileCount(); ?>
                            <?= $photoLCount > 0 ? Html::a('<span class="photo-count-circle">' . $photoLCount . '</span>', ['site/gallery', 'id' => $lot->id, 'type' => 'l'], ['target' => '_blank']) : '' ?>
                        </td>
                        <td>
                            <?= Html::a('<i class="fas fa-edit"></i>', ['site/update-lot', 'id' => $lot->id], ['class' => 'btn btn-outline-primary btn-sm', 'title' => 'Update']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Пагинация -->
    <?= $this->render('//partials/_pagination', ['pagination' => $dataProvider->pagination]) ?>
</div>