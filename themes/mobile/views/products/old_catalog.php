<script type="text/javascript" src="/js/jquery.checkbox.js"></script>
<script>
    $(document).ready(function () {
        $(".up").click(function () {
            $('html, body').animate({scrollTop: 0}, 500);
        });
    });
</script>
<div class="page">
    <div class="blok">
        <p class="crumbs"><a href="/">главная</a> / <a
                class="crumbs_a"><?php echo $h1 ?></a></p>
        <form id="filter">
            <div class="blok_category">
                <h1><?php echo $h1 ?></h1>
                <ul class="dishes">
                    <input type="hidden" name="Direction" value="<?php echo $direction ?>">
                    <?php foreach ($specs as $s) { ?>
                        <li>
                            <label>
                                <span class="niceCheck" onclick="js:changeCheck(this)"
                                      style="background-position: 0px <?php echo (!empty($_GET) && $_GET['id'] == $s->tname) ? "-19px" : "0px" ?>">
                                    <?php echo CHtml::checkbox('Spec[' . $s->id . ']', !empty($_GET) && $_GET['id'] == $s->tname ? true : false, array(
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('/products/ajaxCheckSpecs'),
                                            'update' => '#suppliers',
                                        ),
                                    ));
                                    ?>
                                    <b><?php echo $s->name; ?></b>
                                </span>
                            </label>
                        </li>
                    <?php } ?>
                </ul>
                <div class="line_cat"></div>
                <h1>Критерии</h1>
                <ul class="dishes">
                    <li>
                        <label>
                            <span class="niceCheck" onclick="js:changeCheck(this)" id="niceCheckbox7">
                                <?php echo CHtml::checkbox('Criteria[1]', '', array(
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => CController::createUrl('/products/ajaxCheckSpecs'),
                                        'update' => '#suppliers',
                                    ),
                                )); ?>
                                <b>с бесплатной доставкой</b>
                            </span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <span class="niceCheck" onclick="js:changeCheck(this)" id="niceCheckbox8">
                            <?php echo CHtml::checkbox('Criteria[2]', '', array(
                                'ajax' => array(
                                    'type' => 'POST',
                                    'url' => CController::createUrl('/products/ajaxCheckSpecs'),
                                    'update' => '#suppliers',
                                ),
                            )); ?>
                                <b>без минимальной суммы заказа</b>
                            </span>
                        </label>
                    </li>
                </ul>
            </div>
        </form>
        <div class="page_the_category" id="suppliers">
            <?php echo $this->renderPartial('supplierList', array('model' => $model)); ?>
        </div>
    </div>
</div>