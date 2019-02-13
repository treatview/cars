<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Link extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function link()
    {
        $request = Request::instance();
        $data = Db::name('link')->select();
        return $this->fetch("",['list'=>$data]);
    }

    public function linkAdd()
    {
        //数据处理
        $request = Request::instance();
        //链接名是否重复检测
        if($request->param("type",'')){
            if(Db::name("link")->where('link_name',$request->param('cate_name',''))->count()>0){
                return ['code'=>0,'msg'=>'链接名已存在'];
            }else{
                return ['code'=>1,'msg'=>'链接名不重复'];
            }
        }
        $swit = 0;
        $switch = $request->param("switch","");
        $link_name = $request->param("link_name","");
        $link_src = $request->param("link_src","");
        if ($switch == 'on') {
            $swit = 1;
        }
        if($request->isAjax()){
            $data = [
                'link_ifshow'=>$swit,
                'link_name'=>$link_name,
                'link_src'=>$link_src
            ];
            $res['code']=1;
            $into = Db::name("link")->insert($data);
            if ($into > 0) {
                return $res;
            }else{

            }
        }else{
            return $this->fetch();
        }
    }
    public function linkUpdate()
    {
        $request = Request::instance();
        $linkid = $request->param("newsid","");
        $swit = 0;
        $switch = $request->param("switch","");
        $link_name = $request->param("link_name","");
        $link_src = $request->param("link_src","");
        if ($switch == 'on'){
            $swit = 1;
        }
        if($request->isAjax()){
            $data = [
                'link_name'=>$link_name,
                'link_src'=>$link_src,
                'link_ifshow'=>$swit,
            ];
            $res = Db::name('link')->where('link_id',$linkid)->update($data);
            if($res>0){
                return ['code'=>1,'msg'=>'修改成功'];
            }else{
                return ['code'=>0,'msg'=>'修改失败'];
            }
        }else{
            $cate = Db::name('link')->find($linkid);
            return $this->fetch('',['list'=>$cate]);
        }
    }
    public function linkDel()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $id = $request->param('id','');
            if(Db::name('link')->delete($id)>0){
                return ['code'=>1,'msg'=>'删除成功'];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }
    }
    public function updateif()
    {
        $request = Request::instance();
        $cate_id = $request->param('cateid');
        $ifshow = Db::name('link')->where('link_id',$cate_id)->find()['link_ifshow'];
        if($ifshow==1){
            $eidtifshow = 0;$msg = "已隐藏";
        }else{
            $eidtifshow = 1;$msg = '已显示';
        }
        if(Db::name('link')->where('link_id',$cate_id)->update(['link_ifshow'=>$eidtifshow])>0){
            return ['code'=>1,'msg'=>$msg];
        }else{
            return ['code'=>0,'msg'=>'修改失败'];
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
