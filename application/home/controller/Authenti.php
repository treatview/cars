<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Db;

class Authenti extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function authenti()
    {
        return $this->fetch();
    }
    //图片上传
    public function upload()
    {
        $request = Request::instance();
        if($request->isAjax()){ 
            $file = $request->file("img","");
            if($file){
                //文件保存到入口文件目录
                $info = $file->rule('date')->move("./uploads");//第二个值为空时，表示原文件名
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
    public function delImg()
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

    //资料提交
    public function authinfo()
    {
        $request = Request::instance();
        if($request->isAjax())
        {
            //判断用户
            $checkname = $request->param('checkname',"");
            $realname = $request->param('user',"","trim");
            $idcard = $request->param('idcard',"","trim");
            $img_front = $request->param('idcard_front',"","trim");
            $img_back = $request->param('idcard_back',"","trim");
            $address = $request->param('address',"","trim");
            $email = $request->param('email',"","trim");

            $id_img = ['front'=>$img_front,'back'=>$img_back];
            $id_img = json_encode($id_img);
            $data = [
                'auth_real_name'=>$realname,
                'auth_id_card' => $idcard,
                'auth_idcard_img' => $id_img,
                'auth_des' => $address,
                'auth_email' => $email,

            ];
            if(Db::name('user')->where('user_name',$checkname)->count()>0){
                //插入外键
                $id = Db::name('user')->where('user_name',$checkname)->find()['user_id'];
                $data['auth_user_id'] = $id;


                //提交认证
                $authinfo = 1;

            if(Db::name("authenti")->where("auth_user_id",$id)->count()>0){
                    //数据更新
                if(Db::name("authenti")->where("auth_user_id",$id)->update($data)>0 && Db::name('user')->where('user_id',$id)->update(['user_auth_info'=>1])>0){
                    //更改user表中认证字段
                        return ['code'=>1,'msg'=>'提交成功'];
                    }else{
                        $this->delImg();
                        return ['code'=>0,'msg'=>'不可重复提交'];
                    }
            }else{
                    if(Db::name("authenti")->insert($data)>0){
                        //更改user表中认证字段
                        //
                        if(Db::name('user')->where('user_name',$checkname)->update(['user_auth_info'=>$authinfo])>0){
                            return ['code'=>1,'msg'=>'提交成功'];
                        }else{
                            $this->delImg();
                            return ['code'=>0,'msg'=>'数据出错2'];
                        }

                    }else{
                        //数据库图片删除
                        $this->delImg();
                        return ['code'=>0,'msg'=>'提交失败'];
                    }
                }
            }else{
                return ['code'=>0,'msg'=>'数据异常'];
            }
        }
    
}
    //审核中
    public function review()
    {
        return $this->fetch();
    }

    //审核未通过
    public function fail()
    {
        $request = Request::instance();
        $id = $request->param("userid","");
        $data = Db::name("authenti")->where("auth_user_id",$id)->find();
        return $this->fetch("",['data'=>$data]);
    }

    public function succes()
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
