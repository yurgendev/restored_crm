<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\LotSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $customers */
/** @var array $warehouses */
/** @var array $companies */

$this->title = 'Terminal Lots';
?>
<div class="site-terminal-lots">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Форма поиска -->
    <form method="get" action="<?= Url::to(['site/terminal']) ?>" class="mb-3">
        <div class="input-group">
            <?= Html::activeTextInput($searchModel, 'search', ['class' => 'form-control', 'placeholder' => 'Type VIN, Lot or Auto']) ?>
            <?= Html::hiddenInput('LotSearch[status]', $searchModel->status) ?>
            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>

    <!-- Форма фильтрации -->
    <form method="get" action="<?= Url::to(['site/terminal']) ?>" class="mb-3">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date Warehouse</th>
                        <th>Wait (days)</th>
                        <th>Auto</th>
                        <th>VIN</th>
                        <th>LOT</th>
                        <th>Account</th>
                        <th>
                            Customer
                            <?= Html::dropDownList('LotSearch[customer_id]', $searchModel->customer_id, ['' => 'All'] + ArrayHelper::map($customers, 'id', 'name'), [
                                'class' => 'form-control',
                                'onchange' => 'this.form.submit()',
                            ]) ?>
                        </th>
                        <th>
                            Warehouse
                            <?= Html::dropDownList('LotSearch[warehouse_id]', $searchModel->warehouse_id, ['' => 'All'] + ArrayHelper::map($warehouses, 'id', 'name'), [
                                'class' => 'form-control',
                                'onchange' => 'this.form.submit()',
                            ]) ?>
                        </th>
                        <th>
                            Company
                            <?= Html::dropDownList('LotSearch[company_id]', $searchModel->company_id, ['' => 'All'] + ArrayHelper::map($companies, 'id', 'name'), [
                                'class' => 'form-control',
                                'onchange' => 'this.form.submit()',
                            ]) ?>
                        </th>
                        <th>
                            Keys
                            <?= Html::dropDownList('LotSearch[has_keys]', $searchModel->has_keys, ['' => 'All', '1' => 'Yes', '0' => 'No'], [
                                'class' => 'form-control',
                                'onchange' => 'this.form.submit()',
                            ]) ?>
                        </th>
                        <th>Photo D</th>
                        <th>Photo W</th>
                        <th>Video</th>
                        <th>Title</th>
                        <th>BOS</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataProvider->getModels() as $lot): ?>
                        <tr>
                            <td><?= Yii::$app->formatter->asDate($lot->date_warehouse) ?></td>
                            <td><?= Html::encode($lot->wait) ?></td>
                            <td><?= Html::encode($lot->auto) ?></td>
                            <td><?= Html::encode($lot->vin) ?></td>
                            <td><?= Html::encode($lot->lot) ?></td>
                            <td><?= Html::encode($lot->account->name) ?></td>
                            <td><?= Html::encode($lot->customer->name) ?></td>
                            <td><?= Html::encode($lot->warehouse->name) ?></td>
                            <td><?= Html::encode($lot->company->name) ?></td>
                            <td><?= $lot->has_keys ? 'Yes' : 'No' ?></td>
                            <td>
                                <?php $photoDCount = $lot->getPhotoDFileCount(); ?>
                                <?= $photoDCount > 0 ? Html::a('<span class="photo-count-circle">' . $photoDCount . '</span>', ['site/gallery', 'id' => $lot->id, 'type' => 'd'], ['target' => '_blank']) : '' ?>
                            </td>
                            <td>
                                <?php $photoWCount = $lot->getPhotoWFileCount(); ?>
                                <?= $photoWCount > 0 ? Html::a('<span class="photo-count-circle">' . $photoWCount . '</span>', ['site/gallery', 'id' => $lot->id, 'type' => 'w'], ['target' => '_blank']) : '' ?>
                            </td>
                            <td><?= $lot->video ? '<i class="fas fa-check"></i>' : '' ?></td>
                            <td><?= $lot->title ? '<i class="fas fa-check"></i>' : '' ?></td>
                            <td><?= $lot->bos ? '<i class="fas fa-check"></i>' : '' ?></td>
                            <td>
                                <?= Html::a('<i class="fas fa-edit"></i>', ['site/update-lot', 'id' => $lot->id], ['class' => 'btn btn-outline-primary btn-sm', 'title' => 'Update']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </form>

    <!-- Пагинация -->
    <?= $this->render('//partials/_pagination', ['pagination' => $dataProvider->pagination]) ?>
</div>