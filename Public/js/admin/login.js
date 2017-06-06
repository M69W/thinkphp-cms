// 前端登陆业务类
var login = {
  check:function(){
    //获取登陆页面中的用户名和密码
    var username = $("input[name='username']").val();
    var password = $("input[name='password']").val();
    if (!username) {
      dialog.error("用户名不能为空");
    }else if (!password) {
      dialog.error("密码不能为空");
    }else{
      var url = "/admin.php?m=admin&c=login&a=check";
      //执行异步请求
      $.post(
        url,
        {
          username:username,
          password:password
        },
        function(result){
          if(result.status == 0){
            return dialog.error(result.message);
          }else if (result.status == 1) {
            return dialog.success(result.message,'/admin.php?m=admin&c=index');
          }
      },'json');
    }
  }
}
