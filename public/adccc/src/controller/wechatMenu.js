/**

 @Name：layuiAdmin 主页示例
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：GPL-2
    
 */


layui.define(['admin', 'form','myUtils','laytpl'], function(exports){
  var $ = layui.$;
  var myUtils = layui.myUtils;
  var MENUDATA = {};
  MENUDATA.menus = layui.data('wechat_menu')['menu']?layui.data('wechat_menu')['menu']:[];
  MENUDATA.curindex = 0;
  MENUDATA.subindex = null;
  MENUDATA.isSort = false;
 
  randerMenus();
  randerOperation();

  $('body').on('click', '.menus', function(event) {
      MENUDATA.subindex = null;
      if($(this).hasClass('menu-add')){
          changeMenus('top');
      }else{
          MENUDATA.curindex = $(this).index();
          randerMenus();
      }
      if(!MENUDATA.isSort){
          randerOperation();
      }
  });

  //子菜单点击
  $('body').on('click', '.addsubmenu li', function(event) {
      event.stopPropagation();
      if($(this).hasClass('add-sub-menu')){
          MENUDATA.subindex = null;
          changeMenus('sub');
      }else{
          MENUDATA.subindex = $(this).parent('ul').children('.sub-menu').length<5?$(this).index()-1:$(this).index();
          randerMenus();
      }
      MENUDATA.isSort || randerOperation();
  });

  //删除菜单
  $('body').on('click', '.del-menu', function(event) {
      if(MENUDATA.subindex!=null){  //子菜单
          MENUDATA.menus[MENUDATA.curindex]['sub_button'].splice(MENUDATA.subindex,1);
          if(MENUDATA.menus[MENUDATA.curindex]['sub_button'].length==0){
            delete MENUDATA.menus[MENUDATA.curindex]['sub_button'];
            MENUDATA.menus[MENUDATA.curindex].type = 'click';
            MENUDATA.menus[MENUDATA.curindex].key = "menu_click_"+MENUDATA.curindex;
          }
          MENUDATA.subindex = null;
          randerMenus();
          randerOperation();
      }else{
         if(MENUDATA.menus[MENUDATA.curindex]['sub_button']){
            layer.alert('必须先删除子菜单', {icon: 2,title:'错误'});
         }else{
            MENUDATA.menus.splice(MENUDATA.curindex,1);
            randerMenus();
            randerOperation();
         }
      }
      layui.data('wechat_menu', {key:'menu', value: MENUDATA.menus});
  });

  /**
   * 修改MENUS
   * @author CCC  2019-08-17T20:09:39+0800
   * @param  {Number} index 顺序
   * @param  {String} level top/sub
   */
  function changeMenus(level) {
      var _menus = MENUDATA.menus;
      if(level=='top'){
          MENUDATA.menus.push({"name":'菜单名称', "type":"click", "key":'menu_click_'+_menus.length});
          MENUDATA.curindex = _menus.length;
      }else{
          var parentIndex = MENUDATA.curindex;
          var subMenu = MENUDATA.menus[parentIndex].sub_button || [];
          var iName = MENUDATA.menus[parentIndex].name;
          subMenu.push({"name":'子菜单名称', "type":"click", "key":'menu_click_'+parentIndex+'_'+subMenu.length});
          MENUDATA.menus[parentIndex] = {
            name: iName,
            sub_button: subMenu
          }
      }
      layui.data('wechat_menu', {key:'menu', value: MENUDATA.menus});
      randerMenus();
  }
  
  /**
   * 重新渲染左侧菜单
   * @author CCC  2019-08-17T20:11:59+0800
   * @return {[type]} [description]
   */
  function randerMenus() {
      var data = $.extend(true, {}, MENUDATA);
      if(MENUDATA.menus.length<3){
          data.subWidth = 250/(MENUDATA.menus.length+1);
      }else if(MENUDATA.menus.length==3){
          data.subWidth = 250/(MENUDATA.menus.length);
      }else{
          data.subWidth = 250;
      }
      layui.laytpl($('#phone-footer-tpl').html()).render(data, function(html){
          $('.phone-menu-box').html(html);
      });
  }

  /**
   * 重新渲染右侧操作
   * @author CCC  2019-08-17T23:39:58+0800
   * @return {[type]} [description]
   */
  function randerOperation(operation_data) {
      if(MENUDATA.curindex!=MENUDATA.menus.length){
          if(MENUDATA.subindex!=null){   //子菜单
              var data = MENUDATA.menus[MENUDATA.curindex]['sub_button'][MENUDATA.subindex];
              data['sub'] = MENUDATA.subindex;
          }else{      //主菜单
              var data = MENUDATA.menus[MENUDATA.curindex];            
          }

          data = $.extend(true, data, operation_data);
          
          layui.laytpl($('#operation-tpl').html()).render(data, function(html){
              $('#operation-box').html(html);
          });
          layui.form.render(null, 'operation-form');
      }else{
          $('#operation-box').html('');
      }
      $('#operation-box').show();
      $('.json-data').hide();
  }


  //////////////////////右侧菜单修改////////////////////////////
  //点击菜单内容rediao
  layui.form.on("radio(menu-type-filter)", function (data) {
      randerOperation({type: data.value});
      layui.data('wechat_menu', {key:'menu', value: MENUDATA.menus});
  })

  $('body').on('keyup', '.operation-input', function(event) {
      var _ac = $(this).attr('name');
      var _val = $(this).val();

      if(MENUDATA.subindex!=null){   //子菜单
           MENUDATA.menus[MENUDATA.curindex]['sub_button'][MENUDATA.subindex][_ac] = _val;
      }else{      //主菜单
          MENUDATA.menus[MENUDATA.curindex][_ac] = _val;
      }
      randerMenus();
      layui.data('wechat_menu', {key:'menu', value: MENUDATA.menus});
  });

  ////////////////////////按钮功能///////////////////
  //查看json
  $('.show-json').click(function(event) {
    if($('.json-data').is(':visible')){
      try{
        var jsond = $.parseJSON($('.json-data-content').val());
      }catch(err){
         layer.alert('json格式错误', {icon: 2,title:'错误'});
         return false;
      }
      MENUDATA.menus = jsond['button'];
      $('.json-data').hide();
      randerMenus();
      randerOperation();
      layui.data('wechat_menu', {key:'menu', value: MENUDATA.menus});
      $('.show-json').text('查看json');
    }else{
      layui.admin.req({
        url:'wechat/menu/JsonPretiy',
        data:{button:MENUDATA.menus},
        type:'post',
        done:function (res) {
          $('.json-data-content').val(res.data);
          $('.json-data').show();
          $('#operation-box').hide();
          $('.show-json').text('保存json');
        }
      })
    }
  });

  //菜单排序
  $('.menu-sort').click(function(event) {
       MENUDATA.isSort = !MENUDATA.isSort;
      if(MENUDATA.isSort){
        $('#operation-box').hide();
          randerMenus();
          doMove();
      }else{
          $(document).off('mousemove.DR');
          $(document).off('mouseup.DR');
          $(document).off('mousedown.DR');
          randerMenus();
      }
  });

  function doMove() {
      $(document).on('mousedown.DR', '[drmove]', function(e) {
          if(e.which!=1 && e.which!=0) return false;
          var $p = $(this).parent();
          $p.parent().find('.move-placeholder').remove();
          var moveIndex = $p.index();

          var STclientX = e.clientX;
          var pX = $p.position().left;
          var pH = $p.height();
          var maxX = $p.parent().width();

          $p.css({
              'width': $p.width()-4,    
              'position':'absolute',
              'left': pX+2,
              'z-index':999,
              'background': '#fff',
              'top':2,
              'height': pH-4
          });
          $p.before('<div class="move-placeholder"></div>');

          $(document).on('mousemove.DR', function(e) {
              var posX = e.clientX - STclientX + pX;   //当前移动的距离box的距离

              //移动限制
              if(posX<-$p.width() || posX>maxX){
                  posX = $p.position().left;
                  $(document).off('mousemove.DR');
                    $p.parent().find('.move-placeholder').remove();
                    $p.css({
                        'position':'relative',
                        'left': pX  
                    });
              }else{
                  $p.css({'left': posX+2});
              }

              backIndex = parseInt(posX/$p.width());   //移动到的元素
              if($p.index()-1!=backIndex){
                  $p.parent().children('.menus').eq(backIndex).addClass('readmove').siblings().removeClass('readmove');
              }else{
                  $p.parent().children('.menus').siblings().removeClass('readmove');
              }
          })

            $(document).on('mouseup.DR', function() {  
                $(document).off('mousemove.DR');
                MENUDATA.menus[backIndex] = MENUDATA.menus.splice(moveIndex, 1, MENUDATA.menus[backIndex])[0];
                $p.parent().children('.menus').eq(backIndex).insertBefore($p.parent().children('.menus').eq(moveIndex));
                $p.parent().children('.menus').css({'position':'relative', 'left':0});

                $(document).off('mouseup.DR');
                $p.parent().find('.move-placeholder').remove();
                moveIndex = 0;
                backIndex = 0;
            })
      });

      //子菜单移动
      $(document).on('mousedown.DR', '[dsmove]', function(e) {
          if(e.which!=1 && e.which!=0) return false;
          var $p = $(this).parent();
          $p.parent().find('.move-placeholder').remove();
          var moveIndex = $p.index();
          var parentBoxIndex = $p.parents('.menus').index();

          var STclientY = e.clientY;
          
          var pY = $p.position().top;
          var pH = $p.height();
          var maxY = $p.parent().width();

          var posY = pY;
          $p.css({
              'width': $p.width()-6, 
              'left': 2,   
              'position':'absolute',
              'z-index':99999,
              'top': pY+2,
              'height': pH-2
          });
          $p.before('<div class="move-placeholder" style="height:'+pH+'px;"></div>');

          $(document).on('mousemove.DR', function(e) {
              posY = e.clientY - STclientY + pY;   //当前移动的距离box的距离
              //移动限制
              if(posY>$p.parent().height() || posY<-(pH)){

                  posY = $p.position().top;

                  $(document).off('mousemove.DR');
                  $p.parent().find('.move-placeholder').remove();
                  $p.css({
                      'position':'relative',
                      'top':0
                  });
              }else{
                  $p.css({'top': posY});
              }

              backIndex = parseInt(posY/pH);   //移动到的元素
              if($p.index()-1!=backIndex){
                  $p.parent().children('.sub-menu').eq(backIndex).addClass('readmove').siblings().removeClass('readmove');
              }else{
                  $p.parent().children('.sub-menu').siblings().removeClass('readmove');
              }
          })

            $(document).on('mouseup.DR', function() { 
                backIndex = parseInt(posY/pH);   //移动到的元素

                if(backIndex<MENUDATA.menus[parentBoxIndex]['sub_button'].length){
                    MENUDATA.menus[parentBoxIndex]['sub_button'][backIndex] = MENUDATA.menus[parentBoxIndex]['sub_button'].splice(moveIndex, 1, MENUDATA.menus[parentBoxIndex]['sub_button'][backIndex])[0];
                     $p.parent().children('.sub_button').eq(backIndex).insertBefore($p.parent().children('.sub_button').eq(moveIndex));
                      $p.parent().children('.sub_button').css({'position':'relative', 'left':0});
                }

                $p.parent().find('.move-placeholder').remove();
                moveIndex = 0;
                backIndex = 0;
            })
          
      });
  }


  exports('wechatMenu', {});
});