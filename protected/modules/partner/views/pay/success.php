<style type="text/css">
    .successPay {
        text-align: center;
        color: #1FCD35;
        font-size: 21px;
        line-height: 35px;
        display: block;
        border: 1px solid #ddd;
        position: absolute;
        left: 50%;
        top: 50%;
        padding: 30px;
        border-radius: 20px;
        transform: translate(-50%, -50%);

    }

    .successPay .balance {

    }

    .successPay a.back {
        display: inline-block;
        padding: 10px;
        border: 1px solid #ddd;
        margin-top: 10px;
        text-decoration: none;
    }

    .successPay a.back:hover {
        background: #ddd;
    }
</style>
<div class="successPay">
    <h1 style='text-align: center; color: #3D3030;'>Платеж для "<?= $partner->name ?>"</h1>
    Платеж в размере <?= $vars->amount ?> <?php echo City::getMoneyKod(); ?> успешно внесен на ваш лицевой счет.<br>

    <div class="balance">На данный момент ваш лицевой счет
        составляет <?= $partner->balance ?> <?php echo City::getMoneyKod(); ?></div>
    <a class="back" href="/partner/info">Вернуться</a>
</div>