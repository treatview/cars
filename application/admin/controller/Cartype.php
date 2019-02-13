<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Cartype extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    
    //list
    public function typeList()
    {
        $data = Db::name('car_type t')->join('series s','t.ty_series_id = s.series_id')->join('brand b','t.ty_bnd_id = b.bnd_id')->select();
        return $this->fetch('',['data'=>$data]);
    }
    //新增
    public function typeAdd()
    {   
        $request = Request::instance();
        if($request->isAjax()){
            $band = $request->param('brand');
            $series = $request->param('series');
            $name = $request->param('tpname','','trim');
            $year = $request->param('year','','trim');
            $price = $request->param('price','','trim');
            $output = $request->param('output','','trim');
            $output_stand = $request->param('output_stand','','trim');
            $trans = $request->param('ty_trans','','trim');

            $data = [
                'ty_name'=>$name,
                'ty_bnd_id'=>$band,
                'ty_series_id'=>$series,
                'ty_year'=>$year,
                'ty_price'=>$price,
                'ty_volume'=>$output,
                'ty_volume_stand'=>$output_stand,
                'ty_trans'=>$trans,
            ];
            if(Db::name('car_type')->insert($data)>0){
                return ['code'=>1,"msg"=>'添加成功'];

            }else{
                return ['code'=>0,"msg"=>'添加失败'];
            }

        }else{
        $brand = Db::name('brand')->select();
        $series = Db::name('series')->select();
        return $this->fetch('',['brand'=>$brand,'series'=>$series]);
        }
    }
    public function select()
    {
        $request = Request::instance();
        if($request->isPost()){
            $id = $request->param("id","");
            $query = Db::name('brand b');
            if($id){
                $query = Db::name('brand b')->join('series se','b.bnd_id = se.series_bnd_id')->where('se.series_bnd_id',$id);
            }
            $res = $query->select();
            // ->where('bnd_id',$id)
            // ->join('series se','b.bnd_id = se.series_bnd_id','left')
            // ->join('car_type ty','se.series_id = ty.ty_series_id','left')
            
            echo json_encode($res);
        }   
    } 
    //名称
    public function checkTypename()
    {
        $request = Request::instance();
        if($request->isGet()){
            $name = $request->param('name');
            if(Db::name('car_type')->where('ty_name',$name)->count()>0){
                return ['code'=>0,'msg'=>"车型名称已存在，请重新输入"];
            }else{
                return ['code'=>1,"msg"=>'无'];
            }
        }
    }
    //编辑
    public function typeUpdate()
    {
        $request = Request::instance();
        $id = $request->param('tyid');
        if($request->isAjax()){
            $band = $request->param('brand');
            $series = $request->param('series');
            $name = $request->param('tpname','','trim');
            $year = $request->param('year','','trim');
            $price = $request->param('price','','trim');
            $output = $request->param('output','','trim');
            $output_stand = $request->param('output_stand','','trim');
            $trans = $request->param('ty_trans','','trim');

            $data = [
                'ty_name'=>$name,
                'ty_bnd_id'=>$band,
                'ty_series_id'=>$series,
                'ty_year'=>$year,
                'ty_price'=>$price,
                'ty_volume'=>$output,
                'ty_volume_stand'=>$output_stand,
                'ty_trans'=>$trans,
            ];
            if(Db::name('car_type')->where('ty_name',$name)->where('ty_id','<>',$id)->count()>0){
                return ['code'=>0,'msg'=>'车型名称已存在重新设置'];
            }else{
                if(Db::name('car_type')->where('ty_id',$id)->update($data)>0){
                    return ['code'=>1,'msg'=>'修改成功'];
                }else{
                    return ['code'=>0,'msg'=>'修改失败'];
                }
            }
        }else{
        $brand = Db::name('brand')->select();
        $series = Db::name('series')->select();
        $list = Db::name('car_type')->where('ty_id',$id)->find();
        return $this->fetch('',['list'=>$list,'brand'=>$brand,'series'=>$series]);
        }
    }
    //车型删除
    public function typeDel()
    {
         $request = Request::instance();
        if($request->isAjax())
        {
            $id = $request->param("tyid");
            if(Db::name("car_type")->where("ty_id",$id)->count()>0){
                //数据库删除
                $datas = Db::name("car_type")->delete($id);
                if($datas>0){
                    return ['code'=>1,'msg'=>"删除成功"];
                }else{
                    return ['code'=>0,'msg'=>"删除失败"];
                }
            }
        }
    }
    //品牌是否显示
    public function tyupdateifshow()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $id = $request->param("tyid");
            $ifcommand = Db::name("car_type")->where("ty_id",$id)->find()["ty_ifshow"]; 
            if($ifcommand == 1){
                $code = 0; $msg = "已隐藏";
            }else{
                $code = 1; $msg = "已显示";
            }
            if(Db::name("car_type")->where("ty_id",$id)->update(["ty_ifshow"=>$code])>0){
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
