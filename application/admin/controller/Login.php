<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;

class Login extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    //页面展示
    public function index()
    {
        $request = Request::instance();
        //登陆
        if($request->isAjax()){
            $name = $request->param("user","","trim,strip_tags");
            $pwd = $request->param("pwd","","trim,md5");

            if(Db::name("admin_user")->where("admin_name",$name)->count()>0){
                if(Db::name("admin_user")->where("admin_password",$pwd)->count()>0){
                    //判断等级 是否可以登陆
                    if(Db::name("admin_user")->where("admin_name",$name)->find()['admin_level'] == 1){
                        return ['code'=>0,'msg'=>"该账户已被禁用"];
                    }else{
                    Session::set("admin_name",$name);
                    return ['code'=> 1,'msg'=>"登陆成功"];    
                    }
                }else{
                    return ['code'=>0,'msg'=>"密码错误请重新输入"];
                }
            }else{
                return ['code'=> 0,"msg"=>"用户名不存在请重新输入"];
            }
        }else{
             return $this->fetch();
        }
    }
    //退出
    public function logout()
    {
        $requset = Request::instance();

        Session::delete('admin_name');
        if(Session::get("admin_name")==""){
            $this->success("退出成功，正在跳转~~",url("admin/Login/index"));
        }else{
            $this->error("退出失败");
        }
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
