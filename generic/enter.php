<script type="text/javascript">
   swal({
      title: "Добро пожаловать!",
      text: "<input style='display:block' type='email' placeholder='Email' id='email_swal' reguired><input style='display:block' type='text' placeholder='Пароль' id='password_swal' reguired><div style='text-align:center;' id='forgot_code_button' class='btn btn-link btn-sm'>забыл пароль</div>",
      confirmButtonText: "Войти",
      showCancelButton: false,
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      html: true
    },function(){
        var email = $('#email_swal').val();
        var password = $('#password_swal').val();

        if (email && password) {
            $.ajax({
                type:'POST',
                url: "/login",
                data: {
                    'email':email,
                    'password':password
                }
            }).done(function(data) {
                if (data == 1) {
                    document.location.href = '/<?php echo($cur_page);?>';
                } else if (data == 0) {
                    swal.showInputError("Некорректные данные");
                }
            });
        }
    });
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
$('#forgot_code_button').click(function(){
swal({
  title: "Введите свой Email",
  text: "",
  type: "input",
  showCancelButton: true,
  closeOnConfirm: false,
  showLoaderOnConfirm: true,
  inputPlaceholder: ""
},
function(inputValue){
  if (inputValue === false) return false;
  
  if (inputValue === "") {
    swal.showInputError("Необходимо ввести ваш email, если вы его указывали");
    return false
  }
$.ajax({
                url: "/restore_password_by_email.php",
                data: {
                    'email':inputValue               
                    }
            }).done(function(data) {
              
//если email не найден
if (data == 0) {
    
    swal.showInputError("Данный email не зарегистрирован на сайте.");
    return false
    
    
    
    
}
else if (data == 1) {

    swal.showInputError("Ошибка отправки.");
    return false

}    else {
    
   swal({
      title: "Готово",
      text: "Проверьте свой почтовый ящик",
      confirmButtonText: "Ok",
      showCancelButton: false,
      closeOnConfirm: false
    },function(){
        document.location.reload();
});
}
});
});
});

















</script>