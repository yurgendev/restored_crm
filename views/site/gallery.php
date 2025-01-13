<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $allImages array */
/* @var $pageImages array */
/* @var $lot app\models\Lot */
/* @var $type string */
/* @var $pagination yii\data\Pagination */

$this->title = 'Photo - ' . strtoupper($type);
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="photo-gallery-container d-flex">
    <!-- Основное изображение с кнопками -->
    <div class="main-photo-wrapper">
        <div class="main-photo-container position-relative">
            <img id="mainPhoto" src="<?= Url::to('@web/' . $allImages[0]) ?>" class="img-fluid main-photo" alt="Main Photo">
            <button class="btn btn-primary btn-prev" onclick="prevPhoto()">‹</button>
            <button class="btn btn-primary btn-next" onclick="nextPhoto()">›</button>
        </div>
        <div class="thumbnails-wrapper position-relative mt-3 d-flex justify-content-center">
            <div class="thumbnails d-flex justify-content-center">
                <?php foreach ($allImages as $index => $image): ?>
                    <a href="#" class="thumbnail-link mx-1" data-image="<?= Url::to('@web/' . $image) ?>" onclick="changeMainPhoto(event, <?= $index ?>)">
                        <img src="<?= Url::to('@web/' . $image) ?>" class="img-thumbnail <?= $index === 0 ? 'selected-thumbnail' : '' ?>" alt="Thumbnail">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Вертикальный список с фото -->
    <div class="side-list-container ms-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th> <!-- Порядковый номер -->
                    <th>Photo</th> <!-- Миниатюра -->
                    <th>Url</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pageImages as $index => $image): ?>
                    <tr>
                        <!-- Порядковый номер -->
                        <td><?= $index + 1 ?></td>
                        
                        <!-- Миниатюра -->
                        <td>
                            <img src="<?= Url::to('@web/' . $image) ?>" class="img-thumbnail" alt="Thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        
                        <!-- Ссылка на фото -->
                        <td>
                            <a href="<?= Url::to('@web/' . $image) ?>" target="_blank">Open in new tab</a>
                        </td>
                        
                        <!-- Удаление -->
                        <td>
                            <?= Html::beginForm(['site/delete-image'], 'post', [
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить это изображение?',
                                ],
                            ]) ?>
                                <?= Html::hiddenInput('id', $lot->id) ?>
                                <?= Html::hiddenInput('type', $type) ?>
                                <?= Html::hiddenInput('image', $image) ?>
                                <?= Html::submitButton(Html::tag('i', '', ['class' => 'fas fa-trash-alt']), ['class' => 'btn btn-danger btn-sm']) ?>
                            <?= Html::endForm() ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Пагинация -->
        <?= $this->render('//partials/_pagination', ['pagination' => $pagination]) ?>
    </div>
</div>

<script>
let currentPhotoIndex = 0;
const images = <?= json_encode($allImages) ?>;
const maxVisibleThumbnails = 10;
let thumbnailStartIndex = 0;

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    updateThumbnails();
    highlightThumbnail(currentPhotoIndex);
});

// Обновление основного фото
function updateMainPhoto() {
    const newSrc = '<?= Url::to('@web/') ?>' + images[currentPhotoIndex];
    document.getElementById('mainPhoto').setAttribute('src', newSrc);
}

// Смена фото при клике на миниатюру
function changeMainPhoto(event, index) {
    event.preventDefault();
    currentPhotoIndex = index;
    updateMainPhoto();
    adjustThumbnailScroll();
}

// Переход к предыдущему фото
function prevPhoto() {
    currentPhotoIndex = (currentPhotoIndex - 1 + images.length) % images.length;
    updateMainPhoto();
    adjustThumbnailScroll();
}

// Переход к следующему фото
function nextPhoto() {
    currentPhotoIndex = (currentPhotoIndex + 1) % images.length;
    updateMainPhoto();
    adjustThumbnailScroll();
}

// Выделение активной миниатюры
function highlightThumbnail(index) {
    const thumbnails = document.querySelectorAll('.thumbnail-link img');
    thumbnails.forEach((thumb, i) => {
        const absoluteIndex = i + thumbnailStartIndex;
        thumb.classList.toggle('selected-thumbnail', absoluteIndex === index);
    });
}

// Прокрутка миниатюр
function adjustThumbnailScroll() {
    // Если текущее фото за пределами видимой области
    if (currentPhotoIndex < thumbnailStartIndex || 
        currentPhotoIndex >= thumbnailStartIndex + maxVisibleThumbnails) {
        
        // Центрируем текущее фото
        const center = Math.floor(maxVisibleThumbnails / 2);
        thumbnailStartIndex = Math.max(0, Math.min(
            currentPhotoIndex - center,
            images.length - maxVisibleThumbnails
        ));
    }
    updateThumbnails();
}

// Обновление списка миниатюр
function updateThumbnails() {
    const thumbnailsContainer = document.querySelector('.thumbnails');
    thumbnailsContainer.innerHTML = '';
    
    const end = Math.min(thumbnailStartIndex + maxVisibleThumbnails, images.length);
    
    for (let i = thumbnailStartIndex; i < end; i++) {
        const thumbnailLink = document.createElement('a');
        thumbnailLink.href = '#';
        thumbnailLink.className = 'thumbnail-link mx-1';
        thumbnailLink.dataset.image = '<?= Url::to('@web/') ?>' + images[i];
        thumbnailLink.onclick = (event) => changeMainPhoto(event, i);

        const thumbnailImg = document.createElement('img');
        thumbnailImg.src = '<?= Url::to('@web/') ?>' + images[i];
        thumbnailImg.className = `img-thumbnail${i === currentPhotoIndex ? ' selected-thumbnail' : ''}`;
        thumbnailImg.alt = 'Thumbnail';

        thumbnailLink.appendChild(thumbnailImg);
        thumbnailsContainer.appendChild(thumbnailLink);
    }
    
    highlightThumbnail(currentPhotoIndex);
}
</script>

<style>
.photo-gallery-container {
    display: flex;
    align-items: flex-start;
}

.main-photo-wrapper {
    flex: 2;
    text-align: center;
}

.main-photo-container {
    position: relative;
}

.main-photo {
    max-width: 100%;
    max-height: 600px; /* Увеличиваем размер основного фото */
    object-fit: cover;
}

.btn-prev, .btn-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.7;
}

.btn-prev {
    left: 10px;
}

.btn-next {
    right: 10px;
}

.thumbnails-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.thumbnails {
    overflow-x: hidden;
    white-space: nowrap;
    flex-grow: 1;
}

.thumbnail-link img {
    width: 60px; /* Уменьшаем размер миниатюр */
    height: 60px;
    object-fit: cover;
    border: 2px solid transparent;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.thumbnail-link img:hover,
.selected-thumbnail {
    border-color: #007bff;
    border-width: 2px;
    border-style: solid;
    box-shadow: 0 0 10px #007bff;  /* Единый тип обводки */
}

.side-list-container {
    flex: 1;
    max-height: 600px;
    overflow-y: auto;
}

.side-list-container .img-thumbnail {
    max-width: 50px;
    max-height: 50px;
    object-fit: cover;
}
</style>