<div class="mainBox shop">

    <div class="OfficeNav">
        <a href="/user/profile" class='shopOpenNavActive'> профиль </a>
        <a href="/user/orders"> заказы</a>
        <a href="/user/address"> адреса </a>
        <a href="/user/bonus"> баллы </a>

    </div>
</div>

<div class="shopOpenInfo mainBox">
    <? /*<div class="editNav">
			<a href="#" class=""><img src="/img/editIcon.png" alt=""></a>
			<a href="#" class=""><img src="/img/deleteIcon.png" alt=""></a>		  			
  		</div>*/ ?>


    <div class="shopOpenInfoBlock">
        <span>Имя Фамилия::</span>
        <?php echo CHtml::encode($model->name); ?>
    </div>

    <div class="shopOpenInfoBlock">
        <span>E-mail:</span>
        <?php echo CHtml::encode($model->email); ?>
    </div>

    <div class="shopOpenInfoBlock">
        <span>Дата регистрации:</span>
        <?php echo CHtml::encode($model->reg_date); ?>
    </div>

    <? /* <div class="shopOpenInfoBlock">
	   	<span>E-mail:</span>		 
	 	 <input type="text">  
	</div>*/ ?>



    <center>
        <a href="/user/profile/edit" class='submit'>Редактировать данные</a>
        <? /*<input type="submit" value='Редактировать данные'>*/ ?>
    </center>


</div>
<center>
    <a href="/user/logout" style="float:none;" class="forgotPassword submit">Выход</a>
</center>
	