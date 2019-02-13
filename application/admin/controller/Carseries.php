<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Carseries extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    //车系管理
    public function series()
    {
        $data = Db::name('series s')->join("brand b","s.series_bnd_id = b.bnd_id",'left')->select();
        return $this->fetch("",["data"=>$data]);
    }
    //新增车系
    public function seriesAdd()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $id = $request->param('parent_id','','trim');
            $title = $request->param('title','','trim');
            $series = $request->param('series','','trim');

            $data = [
                'series_name'=>$title,
                'series_group'=>$series,
                'series_bnd_id'=>$id,
            ];
            if(Db::name('series')->insert($data)>0){
                return ['code'=>1,'msg'=>'添加成功'];
            }else{
                return ['code'=>0,'msg'=>'添加失败'];
            }
        }else{
        $data = Db::name('brand')->order('bnd_ucfirst asc')->select();
        return $this->fetch("",['data'=>$data]);
        }
    }
    //名称
    public function checkSeriesname()
    {
        $request = Request::instance();
        if($request->isGet()){
            $name = $request->param('name');
            if(Db::name('series')->where('series_name',$name)->count()>0){
                return ['code'=>0,'msg'=>"用户名已存在"];
            }else{
                return ['code'=>1,"msg"=>'无'];
            }
        }
    }
    //车系是否显示
    //品牌是否显示
    public function seupdatestatus()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $id = $request->param("seriesid");
            
            $ifcommand = Db::name("series")->where("series_id",$id)->find()["series_status"]; 
            if($ifcommand == 1){
                $code = 0; $msg = "已隐藏";
            }else{
                $code = 1; $msg = "已显示";
            }
            if(Db::name("series")->where("series_id",$id)->update(["series_status"=>$code])>0){
                return ['code'=>1,'msg'=>$msg];
            }else{
                return ['code'=>0,'msg'=>'设置失败'];
            }

        }
    }
    //品牌删除
    public function seriesDel()
    {
         $request = Request::instance();
        if($request->isAjax())
        {
            $id = $request->param("seriesid");
            $type = Db::name('car_type')->where('ty_series_id',$id)->count();
            if($type>0){
                return ['code'=>0,'msg'=>'改车系下存在车型数据，无法删除'];
            }else{

                if(Db::name("series")->where("series_id",$id)->count()>0){
                //数据库删除
                    $datas = Db::name("series")->delete($id);
                    if($datas>0){
                        return ['code'=>1,'msg'=>"删除成功"];
                    }else{
                        return ['code'=>0,'msg'=>"删除失败"];
                    }
                }
            }
        }
    }
    //品牌编辑
    public function seriesUpdate()
    {
        $request = Request::instance();
        $id = $request->param("seriesid");
        if($request->isAjax())
        {
            $id = $request->param('seriesid','','trim');
            $title = $request->param('title','','trim');
            $series = $request->param('series','','trim');
            $parentid = $request->param('parent_id','','trim');

            $data = [
                'series_name'=>$title,
                'series_group'=>$series,
                'series_bnd_id'=>$parentid,
            ];
            //名称不能重复
            if(Db::name('series')->where('series_name',$title)->where("series_id",$id)->count()>0){
                return ['code'=>0,'msg'=>'名称不能重复'];
            }else{

                if(Db::name('series')->where("series_id",$id)->update($data)>0){
                    return ['code'=>1,"msg"=>"修改成功"]; 
                }else{
                    return ['code'=>0,"msg"=>"未作操作，修改失败"];
                }
            }
        }else{
            $data = Db::name('series s')->where('series_id',$id)->find();
            $str = Db::name('brand')->select();
            // dump($data);
            return $this->fetch('',['data'=>$data,'brand'=>$str]);
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
