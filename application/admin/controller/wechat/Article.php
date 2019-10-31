<?php
/**
 * User: CCC
 * QQ: 357027
 * Date: 2019/4/2
 * Time: 23:52
 */

namespace app\admin\controller\wechat;

use app\common\controller\AdminBaseController;
use think\Db;
use think\db\Where;
use think\facade\Request;

/**
 * @AuthName 文章管理
 * Class Member
 * DataTime 2019/8/5 18:28
 * @package app\admin\controller
 */
class Article extends AdminBaseController
{
    /**
     * @AuthName 文章管理
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        if(Request::isGet()){
            $res = Db::table('wechat_article')->where('id', Request::get('id'))->find();
        }else{
            $input = Request::post();
            $res['rows'] = Db::table('wechat_article')->limit($input['limit'])->page($input['page'])->order($input['field'], $input['order'])->select();
            $res['total'] = Db::table('wechat_article')->count();
        }
        return $res;
    }

    public function edit()
    {
        $input = Request::post();
        if ($input['action'] == 'add') {
            Db::table('wechat_article')->data($input)->insert();
            $id = Db::table('wechat_article')->getLastInsID();
            return ['msg' => '添加文章成功', 'status' => 'success', 'data'=>$id];
        }

        if($input['action']=='edit'){
            $input['update_at'] = date('Y-m-d H:i:s', time());
            Db::table('wechat_article')->update($input);
            return ['msg'=>'修改文章成功', 'status'=>'success', 'data'=>$input['id']];
        }

        if($input['action']=='del'){
            Db::table('wechat_article')->where('id', $input['id'])->delete();
            return ['status'=>'success', 'msg'=>'删除文章成功!'];
        }

        return $input;
    }


    public function ueditUpload()
    {
        $action = Request::get('action');
        switch ($action) {
            case 'config':
                echo json_encode($this->ueConfig());
                break;
            case 'uploadimage':
                echo $this->uploadimage(Request::file('upfile'));
                break;
            case 'uploadscrawl':
                echo $this->uploadimage(Request::file('upfile'));
                break;
            case 'uploadvideo':
                echo $this->uploadVideo(Request::file('upfile'));
                break;
            case 'listimage':
                echo $this->listImage(Request::get());
                break;
        }
    }

    private function uploadimage($file)
    {
        $info = $file->move('uploads/article/image');
        if ($info) {
            $url = Request::domain() . '/uploads/article/image/' . $info->getSaveName();
            $this->saveUpload($url, 'image');
            return json_encode(['url' => $url, 'title' => $info->getSaveName(), 'original'=>$info->getSaveName(),'state' => "SUCCESS"]);
        } else {
            return ['msg' => $info->getError()];
        }
    }

    private function uploadVideo($file)
    {
        $info = $file->move('uploads/article/image');
        if ($info) {
            $url = Request::domain() . '/uploads/article/video/' . $info->getSaveName();
            $this->saveUpload($url, 'video');
            return json_encode(['url' => $url, 'title' => $info->getSaveName(), 'original'=>$info->getSaveName(),'state' => "SUCCESS"]);
        } else {
            return ['msg' => $info->getError()];
        }
    }

    private function listImage($input)
    {
        $page = $input['start']*$input['size'];
        $list = Db::table('wechat_uploads')->field('id,url')->limit($input['size'])->page($page)->where('type', 'image')->select();
        $ret = [
            'state' =>  'SUCCESS',
            'start' => $input['start'],
            'total' => Db::table('wechat_uploads')->where('type', 'image')->count(),
            'list'  => $list
        ];
        return json_encode($ret);
    }

    /**
     * 记录上传文件
     * @param $url
     * @return int|string
     */
    private function saveUpload($url, $type)
    {
        return Db::table('wechat_uploads')->insertGetId([
            'url'=>$url,
            'update_by'=>1,
            'update_action'=>'wechat.article',
            'type'=>$type
        ]);
    }

    private function ueConfig()
    {
        return [
            "imageActionName"=> "uploadimage", /* 执行上传图片的action名称 */
            "imageFieldName"=> "upfile", /* 提交的图片表单名称 */
            "imageMaxSize"=> 2048000, /* 上传大小限制，单位B */
            "imageAllowFiles"=> [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
            "imageCompressEnable"=> true, /* 是否压缩图片,默认是true */
            "imageCompressBorder"=> 1600, /* 图片压缩最长边限制 */
            "imageInsertAlign"=> "none", /* 插入的图片浮动方式 */
            "imageUrlPrefix"=> "", /* 图片访问路径前缀 */
            "imagePathFormat"=> "/uploads/article/image/{yyyy}{mm}{dd}/", /* 上传保存路径,可以自定义保存路径和文件名格式 */
            /* 涂鸦图片上传配置项 */
            "scrawlActionName"=> "uploadscrawl", /* 执行上传涂鸦的action名称 */
            "scrawlFieldName"=> "upfile", /* 提交的图片表单名称 */
            "scrawlPathFormat"=> "/uploads/article/image/{yyyy}{mm}{dd}/", /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "scrawlMaxSize"=> 2048000, /* 上传大小限制，单位B */
            "scrawlUrlPrefix"=> "", /* 图片访问路径前缀 */
            "scrawlInsertAlign"=> "none",
                /* 抓取远程图片配置 */
            // "catcherLocalDomain"=> ["127.0.0.1", "localhost", "img.baidu.com"],
            // "catcherActionName"=> "catchimage", /* 执行抓取远程图片的action名称 */
            // "catcherFieldName"=> "source", /* 提交的图片列表表单名称 */
            // "catcherPathFormat"=> "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand=>6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
            // "catcherUrlPrefix"=> "", /* 图片访问路径前缀 */
            // "catcherMaxSize"=> 2048000, /* 上传大小限制，单位B */
            // "catcherAllowFiles"=> [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 抓取图片格式显示 */
            /* 上传视频配置 */
            "videoActionName"=> "uploadvideo", /* 执行上传视频的action名称 */
            "videoFieldName"=> "upfile", /* 提交的视频表单名称 */
            "videoPathFormat"=> "/uploads/article/video/{yyyy}{mm}{dd}/{time}{rand=>6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "videoUrlPrefix"=> "", /* 视频访问路径前缀 */
            "videoMaxSize"=> 102400000, /* 上传大小限制，单位B，默认100MB */
            "videoAllowFiles"=> [".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */
             /* 上传文件配置 */
            "fileActionName"=> "uploadfile", /* controller里,执行上传视频的action名称 */
            "fileFieldName"=> "upfile", /* 提交的文件表单名称 */
            "filePathFormat"=> "/uploads/article/file/{yyyy}{mm}{dd}/{time}{rand=>6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "fileUrlPrefix"=> "", /* 文件访问路径前缀 */
            "fileMaxSize"=> 51200000, /* 上传大小限制，单位B，默认50MB */
            "fileAllowFiles"=> [".png", ".jpg", ".jpeg", ".gif", ".bmp", ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg", ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid", ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso", ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"], /* 上传文件格式显示 */
            /* 列出指定目录下的图片 */
            "imageManagerActionName"=> "listimage", /* 执行图片管理的action名称 */
            "imageManagerListPath"=> "/uploads/article/image/", /* 指定要列出图片的目录 */
            "imageManagerListSize"=> 20, /* 每次列出文件数量 */
            "imageManagerUrlPrefix"=> "", /* 图片访问路径前缀 */
            "imageManagerInsertAlign"=> "none", /* 插入的图片浮动方式 */
            "imageManagerAllowFiles"=> [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */
    //
    //             /* 列出指定目录下的文件 */
    //             "fileManagerActionName"=> "listfile", /* 执行文件管理的action名称 */
    //             "fileManagerListPath"=> "/ueditor/php/upload/file/", /* 指定要列出文件的目录 */
    //             "fileManagerUrlPrefix"=> "", /* 文件访问路径前缀 */
    //             "fileManagerListSize"=> 20, /* 每次列出文件数量 */
    //             "fileManagerAllowFiles"=> [
    //     ".png", ".jpg", ".jpeg", ".gif", ".bmp",
    //     ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
    //     ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
    //     ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
    //     ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    // ]

            ];
    }

}