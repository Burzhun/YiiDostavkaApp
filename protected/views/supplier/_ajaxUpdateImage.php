<span class="closePop">X</span>
<div style="position:relative;height: 600px;    overflow: auto;">
    <input type="text" class="good-name" value='<?= $name ?>'>
    <span class="submit-search-image">Поиск</span>
    <? if (!$models) { ?>
        Картинок не найдено
    <? } ?>
    <ul class="update-images">
        <?php foreach ($models as $data) { ?>
            <li>
                <span><?= $data['name'] ?></span>

                <div class="update-image-helper">
                    <img src="/upload/goods/<?= $data['img'] ?>" alt="">
                </div>
                <div class="save-image" data-id="<?= $data['img'] ?>">Сохранить</div>
            </li>
        <?php } ?>
    </ul>
    <input type="hidden" id="orig-update-image" value="<?= $id ?>">
</div>