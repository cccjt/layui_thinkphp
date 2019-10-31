/**

 @Name：layuiAdmin 用户登入和注册等
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License: LPPL
    
 */
 
layui.define('layedit', function(exports){
  var $ = layui.$;
  var layedit = layui.layedit;


  var actionUrl = layui.setter.apiUrl+'wechat/article/';
  layedit.set({
      //暴露layupload参数设置接口 --详细查看layupload参数说明
      uploadImage: {
          url: actionUrl+'upLoadImg',
          accept: 'image',
          acceptMime: 'image/*',
          exts: 'jpg|png|gif|bmp|jpeg',
          size: 1024 * 10,
          data: {
              name: "测试参数",
              age:99
          }
          ,done: function (data) {
              console.log(data);
          }
      },
      uploadVideo: {
          url: 'add',
          accept: 'video',
          acceptMime: 'video/*',
          exts: 'mp4|flv|avi|rm|rmvb',
          size: 1024 * 10 * 2,
          before:function (d) {
            console.log(d);
          },
          done: function (data) {
              console.log(data);
          }
      }
      , uploadFiles: {
          url: 'ccc',
          accept: 'file',
          acceptMime: 'file/*',
          size: '20480',
          autoInsert: true , //自动插入编辑器设置
          done: function (data) {
              console.log(data);
          }
      }
      //右键删除图片/视频时的回调参数，post到后台删除服务器文件等操作，
      //传递参数：
      //图片： imgpath --图片路径
      //视频： filepath --视频路径 imgpath --封面路径
      //附件： filepath --附件路径
      , calldel: {
          url: actionUrl+'delFile',
          done: function (data) {
              console.log(data);
          }
      }
      , rightBtn: {
          type: "layBtn",//default|layBtn|custom  浏览器默认/layedit右键面板/自定义菜单 default和layBtn无需配置customEvent
          customEvent: function (targetName, event) {
              //根据tagName分类型设置
              switch (targetName) {
                  case "img":
                      alert("this is img");
                      break;
                  default:
                      alert("hello world");
                      break;
              };
              //或者直接统一设定
              //alert("all in one");
          }
      }
      //测试参数
      , backDelImg: true
      //开发者模式 --默认为false
      , devmode: true
      //是否自动同步到textarea
      , autoSync: true
      //内容改变监听事件
      , onchange: function (content) {
          console.log(content);
      }
      //插入代码设置 --hide:false 等同于不配置codeConfig
      , codeConfig: {
          hide: true,  //是否隐藏编码语言选择框
          default: 'javascript', //hide为true时的默认语言格式
          encode: true //是否转义
          ,class:'layui-code' //默认样式
      }
      //新增iframe外置样式和js
      , quote:{
          style: ['Content/css.css'],
          //js: ['/Content/Layui-KnifeZ/lay/modules/jquery.js']
      }
      //自定义样式-暂只支持video添加
      //, customTheme: {
      //    video: {
      //        title: ['原版', 'custom_1', 'custom_2']
      //        , content: ['', 'theme1', 'theme2']
      //        , preview: ['', '/images/prive.jpg', '/images/prive2.jpg']
      //    }
      //}
      //插入自定义链接
      , customlink:{
          title: '链接'
          , href: 'https://www.layui.com'
          ,onmouseup:''
      }
      , facePath: 'http://knifez.gitee.io/kz.layedit/Content/Layui-KnifeZ/'
      , devmode: true
      , videoAttr: ' preload="none" ' 
      //预览样式设置，等同layer的offset和area规则，暂时只支持offset ,area两个参数
      //默认为 offset:['r'],area:['50%','100%']
      //, previewAttr: {
      //    offset: 'r'
      //    ,area:['50%','100%']
      //}
      , tool: [
          'html', 'undo', 'redo', 'code', 'strong', 'italic', 'underline', 'del', 'addhr', '|','removeformat', 'fontFomatt', 'fontfamily','fontSize', 'fontBackColor', 'colorpicker', 'face'
          , '|', 'left', 'center', 'right', '|', 'link', 'unlink', 'images', 'image_alt', 'video','attachment', 'anchors'
          , '|'
          , 'table','customlink'
          , 'fullScreen','preview'
      ]
      , height: '500px'
  });

  
  $("#openlayer").click(function () {
      layer.open({
          type: 2,
          area: ['700px', '500px'],
          fix: false, //不固定
          maxmin: true,
          shadeClose: true,
          shade: 0.5,
          title: "title",
          content: 'add.html'
      });
  })



  
  //对外暴露的接口
  exports('ieditorConfig', layedit);
});