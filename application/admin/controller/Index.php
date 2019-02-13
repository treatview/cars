<<<<<<< HEAD
<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;

class Index extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // session_start();
        if((Session::has("admin_name")) == ""){
            $this->redirect("admin/Login/index");
        }else{
            $data = Session::get("admin_name");
            $datas = ['name'=>$data];
            return $this->fetch("",$datas);
        } 
    }

    public function console()
    {
        return $this->fetch();
    }

    public function form()
    {
        return $this->fetch();
    }

    public function login()
    {
        return $this->fetch();
    }

    public function operaterule()
    {
        return $this->fetch();
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
=======
<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
    }
    public function console()
    {
        return $this->fetch();
    }
    public function login()
    {
        return $this->fetch();
    }
    public function form()
    {
        return $this->fetch();
    }
    public function operaterule()
    {
        return $this->fetch();
    }
    public function users()
    {
        return $this->fetch();
    }
}
>>>>>>> 759166a5600271d3cb8d412deaa1ea0e2a9a8f4b
