<script>
    $('.relation_partner').change(function () {
        window.location = '/partner/swappartner/' + this.value;
    });
</script>

<script type="text/javascript">
    $(function () {
        $('.openMenu').on('click', function () {
            $('.mobileMenu').slideToggle('slow');
        })
    })
</script>

<?
$sql = "SELECT id FROM tbl_relation_partner WHERE (owner_id=" . $model->user->id . " OR user_id=" . $model->user->id . ")";
$command = Yii::app()->db->createCommand($sql);
$data = $command->query();

if ($data->read()) {
    $items_array = array(
        array('label' => 'Информация', 'url' => array('/partner/info'), 'active' => $this->id == 'info' ? true : false),
        array('label' => 'Меню', 'url' => array('/partner/menu'), 'active' => $this->id == 'menu' ? true : false),
        array('label' => 'Отзывы', 'url' => array('/partner/review'), 'active' => $this->id == 'review' ? true : false),
        array('label' => 'Заказы', 'url' => array('/partner/orders'), 'active' => ($this->id == 'orders' && $this->action->id == 'index') ? true : false),
        array('label' => 'Заказы группы', 'url' => array('/partner/orders/group'), 'active' => $this->action->id == 'group' ? true : false),
        array('label' => 'Профиль', 'url' => array('/partner/profile'), 'active' => $this->id == 'profile' ? true : false),
        array('label' => '<img src="/images/icon2.png" />', 'url' => array('/partner/message'), 'active' => $this->id == 'message' ? true : false),
        array('label' => 'Выход', 'url' => array('/user/logout')),
    );
} else {
    $items_array = array(
        array('label' => 'Информация', 'url' => array('/partner/info'), 'active' => $this->id == 'info' ? true : false),
        array('label' => 'Меню', 'url' => array('/partner/menu'), 'active' => $this->id == 'menu' ? true : false),
        array('label' => 'Отзывы', 'url' => array('/partner/review'), 'active' => $this->id == 'review' ? true : false),
        array('label' => 'Заказы', 'url' => array('/partner/orders'), 'active' => $this->id == 'orders' ? true : false),
        array('label' => 'Профиль', 'url' => array('/partner/profile'), 'active' => $this->id == 'profile' ? true : false),
        array('label' => '<img src="/images/icon2.png" />', 'url' => array('/partner/message'), 'active' => $this->id == 'message' ? true : false),
        array('label' => 'Выход', 'url' => array('/user/logout')),
    );
}
$data->readAll();
//print_r();
?>


<div class="mobileMenu" style="display:none;">
    <?php
    $this->widget('zii.widgets.CMenu', array(
        'encodeLabel' => false,
        'items' => $items_array,
        'htmlOptions' => array('class' => "mobileUl"),
    ));
    ?>
</div>
<h1><?php echo $h1 ?></h1>