
<div class="well" style="margin-left: 20px;">
    <h1>Забаненные пользователи</h1>
    <form action="" method="post">
        <p>Добавить cookies пользователя</p>
        Cookie:<input type="text" name="cookie_user_id" width="100px">
        <input type="submit" style="vertical-align: top; height: 28px;">
    </form>
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $dataProvider,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered', 'style' => 'float:left;'),
        'summaryText' => '',
        'columns' => array(
            'id',
            'cookie_user_id'
        ),
    ));
    ?>
    </div>
