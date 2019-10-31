/**
 * 自定义函数
 * @param {Object} myUtils
 */

layui.define(['setter', 'laytpl', 'table','form', 'view'], function(exports) {
    var $ = layui.$;

    var myUtils = {
        //缓存助手
        'cache': (...params) => {
            if (params.length == 2) {
                if (params == null) {
                    layui.data(layui.setter.tableName, {
                        key: params[0],
                        remove: true
                    });
                } else {
                    layui.data(layui.setter.tableName, {
                        key: params[0],
                        value: params[1]
                    });
                }
            }
            if (params.length == 1) {
                return layui.data(layui.setter.tableName)[params[0]];
            }
        },

        //模板助手
        'setTpl': (tpl, view, data) => {
            layui.laytpl($(tpl).html()).render(data, function(html) {
                $(view).html(html);
            });
        },

        //表格助手
        'tableHelper': (option) => {
            var url = option.url || layui.setter.apiUrl+$('#layui-table').attr('lay-url');
            var elem = option.elem || '#layui-table';
            var defOption = {
                url: url,
                elem: elem,
                headers: { //通过 request 头传递
                    access_token: layui.data(layui.setter.tableName).access_token
                },
                method: 'post',
                autoSort: false,
                where:{
                    field: 'id', 'order':'desc'
                },
                page: true, //开启分页
                limit: 20,
                limits:[10, 20, 30, 50, 100, 200],
                response: {
                    statusName: 'code' //规定数据状态的字段名称，默认：code
                    ,statusCode: 0 //规定成功的状态码，默认：0
                    ,msgName: 'msg' //规定状态信息的字段名称，默认：msg
                    ,countName: 'total' //规定数据总数的字段名称，默认：count
                    ,dataName: 'rows' //规定数据列表的字段名称，默认：data
                }, 
                parseData: function(res) { //res 即为原始返回的数据
                    return {
                        "code": res.code, //解析接口状态
                        "msg": res.message, //解析提示文本
                        "total": res.data.total, //解析数据长度
                        "rows": res.data.rows //解析数据列表
                    };
                }
                // initSort: {
                //     field: 'id' //排序字段，对应 cols 设定的各字段名
                //     ,type: 'desc' //排序方式  asc: 升序、desc: 降序、null: 默认排序
                // }
                // toolbar: '#layui-table-toolbar', //指向自定义工具栏模板选择器
            };

            var _options = Object.assign(defOption, option);

            _options.done = function(res, pageCur, total){
                tableInit.rowsData = res.rows;
                option.done(res, pageCur, total);
            }
            
            var tableInit = layui.table.render(_options);
            
            //搜索
            layui.form.on('submit(table-search-btn)', function(data){
                tableInit.where = data.field;
               tableInit.reload({ where: data.field });
            });
            
            //监听排序事件 
            layui.table.on('sort(layui-table-filter)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
              tableInit.reload({
                initSort: obj //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                ,where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                  field: obj.field //排序字段
                  ,order: obj.type //排序方式
                }
              });
            });
            
            //注：监听操作栏点击事件
            layui.table.on('tool(layui-table-filter)', function(obj){
                option.active[obj.event] ? option.active[obj.event].call(obj.data, tableInit.rowsData, this) : '';
            })

            $('.layui-table-add-btn').click(function(event) {
                option.active['add'] ? option.active['add'].call(this, tableInit.rowsData) : '';
            });



            //监听提交
            layui.form.on('submit(layui-editform-subbtn)', function(data){
                console.log(data);
                var field = data.field; //获取提交的字段
                var title = field.action=='add'?'添加':'修改';
                var index2 = layer.confirm('确定'+title+'?', { icon:3,
                  btn: ['提交','取消'] //按钮
                }, function(){
                    layer.closeAll();
                    layui.admin.req({
                        url:field.url,
                        type:'post',
                        data:field,
                        done:function (res) {
                            if(res.status!='error'){
                                tableInit.reload({ where: tableInit.where });
                            }
                        }
                    })
                });
              });

            layui.table.editForm = (rows, rowsData, editUrl) => {
                layui.admin.popup({
                   title: '修 改',
                   id: 'LAY-popup-content-edit',
                   area: '500px',
                   offset: '100px',
                   shadeClose:false,
                   success: function(layero, index){
                    layui.view(this.id).render('template/editform', {col:option.cols, val:rows, rowsData:rowsData, action:'edit', 'url':editUrl}).done(function(){
                      layui.form.render(null, 'layui-editform-filter');
                      $('.edit-form-close').click(function(event) {
                          layer.close(index); //执行关闭 
                      });
                    });
                  }
                });
            }

            layui.table.addForm = (rows, editUrl) => {
                layui.admin.popup({
                   title: '添 加',
                   id: 'LAY-popup-content-edit',
                   area: '500px',
                   offset: '100px',
                   shadeClose:false,
                   success: function(layero, index){
                        layui.view(this.id).render('template/editform', {col:option.cols,val:{}, action:'add', 'url':editUrl}).done(function(){
                          layui.form.render(null, 'layui-editform-filter');

                          $('.edit-form-close').click(function(event) {
                              layer.close(index); //执行关闭 
                          });
                        });
                   }
                });
            }

            layui.table.delForm = (rows, url) => {
                console.log(rows.id);
                var index = layer.confirm('确定删除?', { icon:3, btn: ['确定','取消']  }, function(){
                    layer.close(index); //关闭修改
                    layui.admin.req({
                        url:url,
                        type:'post',
                        data:{'id':rows.id},
                        done:function (res) {
                            if(res.status!='error'){
                                tableInit.reload({ where: tableInit.where });
                            }
                        }
                    })
                });
            }
            return tableInit;
        },

        //弹窗助手
        'Msg': (res)=> {
            if(res.status!='error'){
                layui.layer.msg(res.msg, {icon: 1,time: 1000 }, function(){});
            }else{
                layui.layer.msg(res.msg, {icon: 2,time: 2000 });
            }
        },

        'errorMsg':(res) =>{
            layer.msg(res, {
                anim: 6, icon: 5, time: 1000
            }); 
        },

        'getBaseUrl':() => {
            var fullurl = window.location.href;
            return fullurl.replace(layui.router().href, '')
        }
    }
    exports('myUtils', myUtils);
})
