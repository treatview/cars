<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Slide extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }
//幻灯片模块
    public function slide()
    {
        $list = Db::name('slide')->select();
        return $this->fetch("",['list'=>$list]);
    }
    public function slideAdd()
    {
        //数据处理
        $request = Request::instance();
        $img = $request->param("img","");
        $desc = $request->param("slide_desc","");
        $swit = 0;
        $switch = $request->param("switch","");
        if ($switch == 'on') {
            $swit = 1;
        }
        if($request->isAjax()){
            $data = [
                'slide_desc'=>$desc,
                'slide_pic'=>$img,
                'slide_ifshow'=>$swit,
            ];
            $res['code']=1;
            $into = Db::name("slide")->insert($data);
            if ($into > 0) {
                return $res;
            }else{

            }
        }else{
            return $this->fetch();
        }
    }
    public function del()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $id = $request->param('id','');
            if(Db::name('slide')->delete($id)>0){
                return ['code'=>1,'msg'=>'删除成功'];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }
    }
    public function upload()
    {
        $request = Request::instance();
        if ($request->isPost()) {
            $file = $request->file('img');
            if ($file) {
                $info = $file->move("./uploads");
                $path = $info->getSavename();
                //进行路径反斜杠处理
                if(strpos($path,"\\")){
                    $pathinfo = str_replace("\\", "/", $path);

                }
                if ($info) {
                    $res = ['code'=>1,'imginfo'=>$pathinfo];
                    echo json_encode($res);
                }
            }else{
                echo $file->getError();
            }
        }else{
            //return $this->fetch();
        }
    }
    public function updateif()
    {
        $request = Request::instance();
        $cate_id = $request->param('cateid');
        $ifshow = Db::name('slide')->where('slide_id',$cate_id)->find()['slide_ifshow'];
        if($ifshow==1){
            $eidtifshow = 0;$msg = "已隐藏";
        }else{
            $eidtifshow = 1;$msg = '已显示';
        }
        if(Db::name('slide')->where('slide_id',$cate_id)->update(['slide_ifshow'=>$eidtifshow])>0){
            return ['code'=>1,'msg'=>$msg];
        }else{
            return ['code'=>0,'msg'=>'修改失败'];
        }
    }

    public function delimg()
    {
        $request = Request::instance();
        $imginfo = $request->param('imginfo','');
        $imginf = ".".$imginfo;
        //echo $imginfo;
        if (is_file($imginf)) {
            unlink($imginf);
            if (!is_file($imginfo)) {
                $resp = ['code'=>1,'msg'=>'删除成功'];
            }else{
                $resp = ['code'=>0,'msg'=>'删除失败'];
            }
        }else{
            $resp = ['code'=>1,'msg'=>'删除成功'];
        }
        echo json_encode($resp);
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
