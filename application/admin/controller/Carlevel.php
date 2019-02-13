<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Carlevel extends Controller
{
    /**
     * 显示资源列表
     * 
     * @return \think\Response
     */
    public function leveList()
    {
        $data = Db::name('car_level')->select();
        return $this->fetch('',['data'=>$data]);
    }
    //名称
    public function checklename()
    {
        $request = Request::instance();
        if($request->isGet()){
            $name = $request->param('name');
            if(Db::name('car_level')->where('le_name',$name)->count()>0){
                return ['code'=>0,'msg'=>"级别名称已存在，请重新输入"];
            }else{
                return ['code'=>1,"msg"=>'无'];
            }
        }
    }
    //添加数据
    public function levelAdd()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $name = $request->param('title','','trim');
            $img = $request->param('imgs','','trim');

            $data = [
                'le_name'=>$name,
                'le_img'=>$img
            ];
            if(Db::name('car_level')->insert($data)>0){
                 return ['code'=>1,"msg"=>'添加成功'];
            }else{
                return ['code'=>0,"msg"=>'添加失败'];
            }
        }else{
            return $this->fetch();
        }
    }
    //图片回调
    public function levelImg()
    {
        $request = Request::instance();
        if($request->isAjax()){ 
            $file = $request->file("img","");
            if($file){
                //文件保存到入口文件目录
                $info = $file->rule('date')->move("./uploads/level/");//第二个值为空时，表示原文件名
                $path = $info->getSavename();
                //进行路径反斜杠处理
                if(strpos($path,"\\")){
                    $pathinfo = str_replace("\\", "/", $path);

                }
                //默认json格式
                $str = ['code'=>1,'pathinfo'=> $pathinfo];
                return $str;
            }else{
                echo $file->getError();
            }
         }
    }
    //图片删除
    public function ledelImg()
    {
        $request = Request::instance();
        if($request->isAjax()){
            if($request->param("imginfo","")){ 
                $imginfo = $request->param("imginfo");
                $imginfo = ".".$imginfo;
                if(file_exists($imginfo)){
                    unlink($imginfo);
                    if(!is_file($imginfo)){
                        $resp = ['code'=>1,'msg'=>"删除成功"];
                }else{
                    $resp = ['code'=>0,'msg'=>"删除失败" ,"co"=>$imginfo];
                    }
                     echo json_encode($resp);
                }
            }
        }
    }
    //车型删除
    public function levelDel()
    {
         $request = Request::instance();
        if($request->isAjax())
        {
            $id = $request->param("leid");
            if(Db::name("car_level")->where("le_id",$id)->count()>0){
                $data = Db::name("car_level")->where("le_id",$id)->find();
                //数据库删除
                $datas = Db::name("car_level")->delete($id);
                unlink($paths = ".".$data['le_img']);
                if($datas>0){
                    return ['code'=>1,'msg'=>"删除成功"];
                }else{
                    return ['code'=>0,'msg'=>"删除失败"];
                }
            }
        }
    }
    //编辑
    public function levelUpdate()
    {
        $request = Request::instance();
        $id = $request->param('leid');
        if($request->isAjax()){
            $name = $request->param('title','','trim');
            $img = $request->param('imgs','','trim');

            $data = [
                'le_name'=>$name,
                'le_img'=>$img
            ];

            if(Db::name('car_level')->where('le_name',$name)->where('le_id','<>',$id)->count()>0){
                return ['code'=>0,'msg'=>'级别名称已存在重新设置'];
            }else{
                if(Db::name('car_level')->where('le_id',$id)->update($data)>0){
                    return ['code'=>1,'msg'=>'修改成功'];
                }else{
                    return ['code'=>0,'msg'=>'请做修改'];
                }
            }
        }else{
        $list = Db::name('car_level')->where('le_id',$id)->find();
        return $this->fetch('',['data'=>$list]);
        }
    }
    //品牌是否显示
    public function leupdateifshow()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $id = $request->param("leid");
            $ifcommand = Db::name("car_level")->where("le_id",$id)->find()["le_ifshow"]; 
            if($ifcommand == 1){
                $code = 0; $msg = "已隐藏";
            }else{
                $code = 1; $msg = "已显示";
            }
            if(Db::name("car_level")->where("le_id",$id)->update(["le_ifshow"=>$code])>0){
                return ['code'=>1,'msg'=>$msg];
            }else{
                return ['code'=>0,'msg'=>'设置失败'];
            }

        }
    }
    public function index()
    {
        //
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
