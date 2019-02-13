<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Register extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $request = Request::instance();
        if($request->param("type",'')){
            if(Db::name("admin_user")->where('admin_name',$request->param('name',''))->count()>0){
                    return ['code'=>0,'msg'=>'用户名已存在'];
                }else{
                    return "用户名未注册";
                }
        }else{
            return $this->fetch();
        }
    }
    //添加
    public function registerAdd()
    {
        $request = Request::instance();
        if($request->isAjax())
        {
            $name = $request->param("admin","","trim");
            $pwd = $request->param("pwd","","trim,strip_tags,md5");
            $email = $request->param("email","","trim,strip_tags");
            $phone = $request->param("phone","","trim,strip_tags");
            $sex = $request->param("sex","","trim,strip_tags");

            $data = [
                'admin_name'=>$name,
                'admin_password'=>$pwd,
                'admin_email'=>$email,
                'admin_phone'=>$phone,
                'admin_sex'=>$sex,
                'admin_level'=>0
            ];
            if(Db::name("admin_user")->insert($data)>0){
                return ['code'=>1,'msg'=>"注册成功"];
            }else{
                return ['code'=>0,'msg'=>'注册失败'];
            }

        }else{
            return "出错";
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
