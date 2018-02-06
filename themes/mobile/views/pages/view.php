<div class="topNav">
    <? /*<a href="" class="cartIcon">
			<span>1</span>
		</a>
		<a href="" class="shortLIcon"></a>
		<a href="" class="searchIcon"></a>*/ ?>

    <a href='#' class="backLink">
        <img src="<?= Yii::app()->theme->baseUrl; ?>/img/arrowBack.png" alt=""> <?= $model->name; ?>
    </a>
</div>

<main class="content static-page">
    <?php echo $model->text; ?>
</main>