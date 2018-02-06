<div class="body-bg bg-none"></div>
<div class="page" id="page">
    <div class="blok">
        <p class="crumbs"><a href="/">главная</a> / <a
                class="crumbs_a"><?php echo $h1 ?></a></p>

        <form id="filter" method="get">
            <div class="blok_category">
                <div style="width:120px"><h1>Поиск</h1></div>
            </div>
        </form>
        <div class="page_the_category" id="suppliers">
            <div>
                <form action="/search" method="get">
                    <input name="query" type="text" class="search-field2" placeholder="Поиск..." style="width:610px"
                           value="<?php if (isset($_GET['query'])) {
                               echo $_GET['query'];
                           } ?>">
                    <input name="" type="submit" class="search-button2" style="position:absolute;right:initial">
                </form>
            </div>
            <br><br>

            <div class="page_the_category" id="suppliers">
                <?php echo $this->renderPartial('supplierList', array('model' => $model)); ?>
            </div>
        </div>
    </div>
</div>