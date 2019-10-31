/**

 @Name：layuiAdmin 公共业务
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */
 
layui.define(['form','table','myUtils'], function(exports){
  var $ = layui.$
  ,setter = layui.setter
  ,admin = layui.admin;

  // 设置标题等
  if(layui.data(setter.tableName).config!==undefined){
      $('.sitename').text(layui.data(setter.tableName).config.managename);
  }
  //空路由跳转首页
  if(layui.router().href==undefined){
    location.hash = '/index';
  }

  //修复左侧导航不自动暂开的bug
  layui.data.leftMenuDone = function (d) {
    $('.layui-nav .layui-this').parent('dl').parent('.layui-nav-item').addClass('layui-nav-itemed');
    layui.element.render('nav', 'layadmin-system-side-menu');
  }

  //设置右侧昵称
  $('#nickname').text(layui.myUtils.cache('session').nickname);

  //自定义验证
  layui.form.verify({
    nickname: function(value, item){ //value：表单的值、item：表单的DOM对象
      if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
        return '用户名不能有特殊字符';
      }
      if(/(^\_)|(\__)|(\_+$)/.test(value)){
        return '用户名首尾不能出现下划线\'_\'';
      }
      if(/^\d+\d+\d$/.test(value)){
        return '用户名不能全为数字';
      }
    }
    
    //我们既支持上述函数式的方式，也支持下述数组的形式
    //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
    ,password: [
      /^[\S]{6,12}$/
      ,'密码必须6到12位，且不能出现空格'
    ] 
  });

  $('#changepswd').click(function(event) {
     layui.admin.popup({
       title: '修改密码',
       id: 'LAY-popup-user-changepswd',
       area: '500px',
       offset: '100px',
       shadeClose:false,
       success: function(layero, index){
        layui.view(this.id).render('template/changepswd', {}).done(function(){
          layui.form.render(null, 'layui-editform-filter');
          $('.edit-form-close').click(function(event) {
              layer.close(index); //执行关闭 
          });
          layui.form.on('submit(layui-changepswd-subbtn)', function(data){
                var field = data.field; //获取提交的字段
                if(field.newpassword!==field.reppassword){
                  layui.myUtils.errorMsg('两次密码不一致!');
                  return false;
                }
                field.uid = layui.myUtils.cache('session').uid;
                var index2 = layer.confirm('确定修改密码?', { icon:3,
                  btn: ['提交','取消'] //按钮
                }, function(){
                    layer.closeAll();
                    layui.admin.req({
                        url:'system/aduser/changePswd',
                        type:'post',
                        data:field,
                        done:function (res) {
                          layui.myUtils.Msg(res);
                        }
                    })
                });
              });

        });
      }
    });
  });
    
  layui.form.on('submit(dosubmit)', function(data){
      admin.req({
          url:$(data.form).attr('action'),
          type:'post',
          data:data.field,
          done:function(data){
              if(data.status=='success'){
                  layui.layer.msg(data.msg, {icon: 1,time: 1000 });
              }else{
                  layui.layer.msg(data.msg, {icon: 2,time: 2000 });
              }
          }
      })
      return false;
  });

  
  //退出
  admin.events.logout = function(){
    //执行退出接口
    admin.req({
      url: 'start/json/user/logout.js'
      ,type: 'get'
      ,data: {}
      ,done: function(res){ //这里要说明一下：done 是只有 response 的 code 正常才会执行。而 succese 则是只要 http 为 200 就会执行
        //清空本地记录的 token，并跳转到登入页
        admin.exit();
      }
    });
  };

  //对外暴露的接口
  exports('common', {});
});