<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);


//$user_id;
//$email;

if (!$user_id) {
    return;
}


$stmt = $connect->prepare("SELECT * FROM users WHERE id=:user_id");
$stmt->execute(array('user_id' => $user_id));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
?>




<style>
    .border-color-warning{
        border-color: #ff6d02
    }
</style>




<div class="dropdown">
<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="outline: none; border: 0;padding: 0;font-size: 30px; line-height: 30px; background-color: rgba(0, 120, 201, 0);color: #fff; box-shadow: none;">
    <span style="outline:none" aria-hidden="true" class="glyphicon glyphicon-menu-hamburger"></span>
  </button>
  <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
    <li><a class="pv-10" style="cursor:pointer" data-toggle="modal" data-target="#userDataModal"><span class="glyphicon glyphicon-user" style="margin-right:5px;"></span>Персональные данные</a></li>
    <li><a class="pv-10" href="/help"><span class="glyphicon glyphicon-question-sign" style="margin-right:5px;"></span>Помощь</a></li>
    <li role="separator" class="divider"></li>
    <li><a class="pv-10" href="/logout"><span class="glyphicon glyphicon-log-out" style="margin-right:5px;"></span>Выход</a></li>
  </ul>
</div>




<!-- Modal -->
<div class="modal fade" id="userDataModal" tabindex="-1" role="dialog" style="text-align:left;">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0;">
            <div class="modal-header" style="background-color:#4C77AF; color:#fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;
                        opacity: 1;
                        border: 0;
                        font-weight: 400;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Персональные данные</h4>
            </div>
            <div class="modal-body">

                <div style="margin-bottom:10px;">

                    <span style="line-height:34px; margin-right:10px;"><img src="/img/password.png" style="margin-right:10px;" width="20"><span class='btn btn-xs btn-warning' data-toggle="collapse" data-target="#new_password_expander">Сменить пароль</span></span>
                    <div id="new_password_expander" style="margin-top:10px" class="collapse">

                        <div class="row well">

                            <div class="col-xs-6" style="padding-left:0;">
                                <input type="text" value='' class="form-control" placeholder="Старый пароль" style="margin-bottom:5px" id="old_password">
                                <input type="text" value='' class="form-control" placeholder="Новый пароль" style="margin-bottom:5px" id="new_password">
                                <input type="text" value='' class="form-control" placeholder="Новый пароль (еще раз)" style="margin-bottom:10px" id="new_password_repeat">
                                <button class="btn btn-sm btn-warning" id="change_password"><i class="glyphicon glyphicon-ok" style="width:16px; margin-right:5px;"></i>Сменить</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div style="margin-bottom:10px;">

                    <span style="line-height:34px; margin-right:10px;"><img src="/img/mail.png" style="margin-right:10px;" width="20"><span style="color:#636363;">Ваш email:</span> <b><?php echo($result['email'] ? : ''); ?></b></span>

                </div>
                <div>
                    <img src="/img/balance.png" width="20" style="margin-right:10px;"><span style="color:#636363;">Баланс:</span> <big id='balance'><?php echo($result['balance']); ?> руб.</big>
                    <a style="cursor:pointer;" data-toggle="collapse" data-target="#balance_expander">пополнить</a>
                    <div id="balance_expander" style="margin-top:10px" class="collapse">
                        <div class="well">
                            <?php require('generic' . MY_DS . 'balance_deposit.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    $('#change_password').click(function () {

        var old_password = $('#old_password').val();
        var new_password = $('#new_password').val();
        var new_password_repeat = $('#new_password_repeat').val();

        if (!old_password || !new_password || !new_password_repeat) {
            swal("", "Не все данные введены", "warning");
        } else if ((new_password !== new_password_repeat) || (new_password == old_password)) {
            swal("", "Неверно введен новый пароль", "warning");
        } else {
            $('#change_password i').attr('class', 'icon-spinner');
            $.ajax({
                type: 'POST',
                url: "/change_password",
                data: {
                    'old_password': old_password,
                    'new_password': new_password
                }
            }).done(function (data) {
                $('#change_password i').attr('class', 'glyphicon glyphicon-ok');
                if (data == 1) {
                    swal("", "Пароль успешно обновлен", "success");
                } else {
                    swal("", "Переданы некорректные данные", "warning");
                }
            });
        }




    });


</script>



