<div class="topNav">
    <a href='<?= Yii::app()->request->urlReferrer ?>' class="backLink">
        <img src="<?= Yii::app()->theme->baseUrl; ?>/img/arrowBack.png" alt=""> <?= $h1 ?>
    </a>
</div>
<div class="nav-rest">
    <span id="opened_rest"
          <? if (Yii::app()->request->cookies['open_rest']->value == 'opened'){ ?>class="rest-active"<? } ?>>Открытые</span>
    <span id="all_rest" <? if (Yii::app()->request->cookies['open_rest']->value == 'all'){ ?>class="rest-active"<? } ?>>Все</span>
</div>
<form id="filter">
    <input type="hidden" name="Direction" value="<?php echo $direction ?>">
</form>

<main class="content">
    <?php echo $this->renderPartial('supplierList', array('model' => $model)); ?>
</main>

<div class="popShort">
    <strong>Сортировать по:</strong>
    <div class="shortLink">
        <a href="" class='shortLinkActive'>Алфавиту</a>
        <a href="">Цене доставки</a>
    </div>
</div>

<div class="popSearch">
    <strong>Поиск по названию:</strong>
    <input type="text"><br><br>
    <input type='submit' value='Искать'>
</div>

<script>
    $(document).on("click", "#all_rest", function (event) {
        if (!$("#all_rest").hasClass("rest-active")) {
            $.ajax({
                url: '<? echo CController::createUrl('/site/allRest')?>',
                type: "post",
                //dataType:"json",
                cache: false,
                data: $("#filter").serialize(),
                success: function (data) {
                    $("#all_rest").toggleClass("rest-active");
                    $("#opened_rest").toggleClass("rest-active");
                    $.ajax({
                        url: '<? echo CController::createUrl('/products/ajaxCheckSpecsMobile')?>',
                        type: "post",
                        cache: false,
                        data: $("form").serialize(),
                        success: function (data2) {
                            $(".content").html(data2);
                        }
                    });
                }
            });
        }
    });

    $(document).on("click", "#opened_rest", function (event) {
        if (!$('#opened_rest').hasClass('rest-active')) {
            $.ajax({
                url: '<? echo CController::createUrl('/site/openRest')?>',
                type: "post",
                //dataType:"json",
                cache: false,
                data: $("#filter").serialize(),
                success: function (data) {
                    $("#opened_rest").toggleClass("rest-active");
                    $("#all_rest").toggleClass("rest-active");
                    $.ajax({
                        url: '<? echo CController::createUrl('/products/ajaxCheckSpecsMobile')?>',
                        type: "post",
                        cache: false,
                        data: $("form").serialize(),
                        success: function (data2) {
                            $(".content").html(data2);
                        }
                    });
                }
            });
        }
    });
</script>
