<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;

class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    /*管理员管理  index*/
    
    public function index()
    {
        $request = Request::instance();
        $data = Db::name("admin_user")->select();
        return $this->fetch("",['data'=>$data]);
    }
    //管理员删除
    public function userDel()
    {
        $request = Request::instance();
        if($request->isAjax()){
            if(Db::name("admin_user")->where("admin_id",$request->param("adminid",""))->count()>0){
                $id = $request->param("adminid","","trim");
                //判断用户名是否为zhang  不可以删除
                if(!(Db::name("admin_user")->where("admin_id",$id)->find()['admin_name'] == "zhang")){
                    if(Db::name("admin_user")->where('admin_id',$id)->delete()>0){
                        return ['code'=>1,'msg'=>"删除成功"];
                    }
                }else{
                    return ['code'=>0,'msg'=>"不可以删除此项"];
                }
            }else{
                return ['code'=>0,'msg'=>"出错"];
            }
        }
    }

    //是否禁用
    public function updateifcomand()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $id = $request->param("adminid","");
            $sname = Session::get('admin_name');
            $ifcommand = Db::name("admin_user")->where("admin_id",$id)->find()["admin_level"];
            //判断当前管理员
            if($sname == "zhang"){
                if($ifcommand == 1){
                $code = 0; $msg = "已撤回";
                }else{
                $code = 1; $msg = "已禁用";
                }
                if(Db::name("admin_user")->where("admin_id",$id)->update(["admin_level"=>$code])>0){
                    return ['code'=>1,'msg'=>$msg];
                }else{
                    return ['code'=>0,'msg'=>'设置失败'];
                }
            }else{
                return ['code'=>0,'msg'=>"非顶级管理员不可更改"];
            }
        }else{
            return ['code'=>0,'msg'=>"出错"];
        }
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    
    /*
    *
    *前台用户管理 frontuser
    * 
    */
    
    public function frontUser()
    {
        $request = Request::instance();
        $data = Db::name("user ")->select();
        return $this->fetch("",['data'=>$data,]);
    }
    //删除前台用户
    public function frontDel(){
        $request = Request::instance();
        if($request->isAjax()){
            $id = $request->param("userid","","trim");
            if(Db::name("user")->where("user_id",$id)->count()>0){
                if(Db::name("user")->where('user_id',$id)->delete()>0 && Db::name("authenti")->where('auth_user_id',$id)->delete()>0){
                     return ['code'=>1,'msg'=>"删除成功"];
                }else{
                    return ['code'=>0,'msg'=>"删除失败"];
                }
            }else{
                return ['code'=>0,'msg'=>"出错"];
            }
        }
    }
    //更改前台用户登陆状态
    public function updateswitch()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $userid = $request->param("userid");
            $ifcommand = Db::name("user")->where("user_id",$userid)->find()["user_switch"]; 
            if($ifcommand == 1){
                $code = 0; $msg = "已撤回禁用";
            }else{
                $code = 1; $msg = "已禁用";
            }
            if(Db::name("user")->where("user_id",$userid)->update(["user_switch"=>$code])>0){
                return ['code'=>1,'msg'=>$msg];
            }else{
                return ['code'=>0,'msg'=>'设置失败'];
            }

        }
    }
    //查看认证信息
    public function lookAuth()
    {
        $request = Request::instance();

        $id = $request->param('userid',"","trim");
        $data = Db::name('authenti')->where('auth_user_id',$id)->find();
        $datas = ['data'=>$data];
        return $this->fetch("",$datas);
    }
    //处理认证信息
    public function methAuth()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $set = $request->param("set",0);
            $reason = $request->param("reson","",'trim');
            $id = $request->param("id");

            //未通过 2
            if($set == 0){
                $num = 2;
                if(Db::name("user")->where("user_id",$id)->update(['user_auth_info'=>$num])>0 && Db::name("authenti")->where("auth_user_id",$id)->update(['auth_reason'=>$reason])>0){
                    return ['code'=>1,'msg'=>'已提交'];
                }else{
                    return ['code'=>0,'msg'=>'提交失败'];
                }
            }else{
                //已通过 3
                $num = 3;
                if(Db::name("user")->where("user_id",$id)->update(['user_auth_info'=>$num])>0 && Db::name("authenti")->where("auth_user_id",$id)->update(['auth_reason'=>$reason])>0){
                    return ['code'=>1,'msg'=>'已提交'];
                }else{
                    return ['code'=>0,'msg'=>'提交失败'];
                }
            }

        }
    }
    /**
     * 
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
