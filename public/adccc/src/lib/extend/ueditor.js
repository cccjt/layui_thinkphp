/**
 * 自定义函数
 * @param {Object} myUtils
 */

layui.define(function(exports) {
    var $ = layui.$;
    window.UEDITOR_HOME_URL = layui.setter.base+'lib/extend/ueditor/';
    $.when(
        $.getScript( window.UEDITOR_HOME_URL+'ueditor.config.js' ),
        $.getScript( window.UEDITOR_HOME_URL+'ueditor.all.min.js' ),
        $.Deferred(function( deferred ){
            $( deferred.resolve );
        })
    ).done(function(d){
        exports('ueditor');
    });
})

