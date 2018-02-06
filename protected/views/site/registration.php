<div id="registration_form">
    <div id="reg-form">
        <form action="" method="post" id="regForm2">
            <p class="reg-title">РЕГИСТРАЦИЯ</p>
            <span id="reg-error2" style="color:#DD3333"></span>

            <p><span id="reg-name-field">ВАШЕ ИМЯ*</span><input name="RegistrationForm[name]"
                                                                id="RegistrationForm_name" type="text"></p>

            <p><span id="reg-email-field">E-MAIL*</span><input name="RegistrationForm[email]"
                                                               id="RegistrationForm_email" type="text"></p>

            <p><span id="reg-pass-field">ПАРОЛЬ*</span><input name="RegistrationForm[password]"
                                                              id="RegistrationForm_password"
                                                              type="password"></p>

            <p><span id="reg-phone-field">Телефон*</span><input name="RegistrationForm[phone]"
                                                                id="RegistrationForm_phone" type="phone">
            </p>
            <button class="reg_button2"></button>
        </form>
    </div>
</div>
<script>
    $(document).on("click", ".reg_button2", function (event) {
        $.ajax({
            url: '<? echo CController::createUrl('/site/registrationAjax');?>',
            type: "post",
            dataType: "json",
            cache: false,
            data: $("#regForm2").serialize(),
            <?/*//data:{"name":$('#RegistrationForm_name').attr('value'), "email":$('#RegistrationForm_email').attr('value'), "pass":$('#RegistrationForm_password').attr('value')},*/?>
            success: function (data) {
                if (data['error']) {
                    $("#reg-error2").html(data['error']);
                    //$("#reg-error").remove();
                }
                if (data['redirect']) {
                    //alert(111);
                    window.location.href = '' + data['redirect'];
                }
            }
        });
        return false;
    });
</script>
<style>
    #registration_form {
        min-height: 500px;
        padding: 10px 30px 70px;
        margin: 40px auto auto;
        width: 920px;
        border: 1px solid #D9D4D4;
        background: #FFF none repeat scroll 0% 0%;
        border-radius: 6px;
        position: relative;
    }

    #reg-form {
        display: block;
        margin-top: 20px;
    }

    .reg_button2 {
        margin-left: 75px;
        margin-top: 20px;
        width: 215px;
        height: 35px;
        background: transparent url("../images/reg-button.png") repeat scroll 0% 0%;
        cursor: pointer;
        border: 0px none;
    }
</style>