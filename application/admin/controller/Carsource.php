<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Carsource extends Controller
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
    //车源列表
    public function sourceList()
    {
        $request = Request::instance();

        // $data = Db::query('select
        //       concat_ws(" ",b.bnd_name,se.series_name,ty.ty_name) as name 
        //       from esc_brand b inner join esc_series se
        //     on
        //       b.bnd_id = se.series_bnd_id inner join esc_car_type ty
        //     on
        //       se.series_id = ty.ty_series_id')->join('series','')->select();

        $data = Db::name('car_source sr')
                ->join('brand b','sr_bnd_id = b.bnd_id')
                ->join('series se','sr.sr_series_id = se.series_id')
                ->join('car_type ty','sr.sr_ty_id = ty.ty_id')
                ->join('area a','sr.sr_area_id = a.area_id')
                ->join('car_level l','sr.sr_le_id = l.le_id')
                // ->where('concat_ws(" ",b.bnd_name,se.series_name,ty.ty_name)  name ')
                ->select();

        return $this->fetch('',['data'=>$data]);

    }
    //车源添加
    public function sourceAdd()
    {
        $request = Request::instance();
        if($request->isAjax()){

            $pid = $request->param('pid');
            $cid = $request->param('cid');
            $brand = $request->param('brand');
            $series = $request->param('series');
            $type = $request->param('type');
            $level = $request->param('level');
            $date_bord = $request->param('date_bord','','trim');
            $kilo = $request->param('kilo','','trim');
            $out = $request->param('out','','trim');
            $transbox = $request->param('transbox','');
            $price = $request->param('price','');
            //过户费
            $transfee = $request->param('transfee','');
            //分期
            $pay = $request->param('firstpay','');
            $car_des = $request->param('car_des','','trim');
            $output_stand = $request->param('output_stand','','trim'); 
            $oil = $request->param('oil','','trim');
            $color = $request->param('color','','trim');
            $purpose = $request->param('purpose','','trim');

            $brt = $request->param('brt/a','');
            $brt = json_encode($brt);
            $imgs = $request->param('imgs/a','');
            $imgs = json_encode($imgs);
            $user = $request->param('user','','trim');
            $phone = $request->param('phone','','trim');
            $time = time();
            $data = [
                'sr_area_id'=>$pid,
                'sr_area_next'=>$cid,
                'sr_bnd_id'=>$brand,
                'sr_series_id'=>$series,
                'sr_ty_id'=>$type,
                'sr_param_id'=>$transbox,
                'sr_le_id'=>$level,
                'sr_date_bord'=>$date_bord,
                'sr_run_kilo'=>$kilo,
                'sr_volume'=>$out,
                'sr_price'=>$price,
                'sr_trans_fee'=>$transfee,
                'sr_pay_id'=>$pay,
                'sr_car_des'=>$car_des,
                'sr_img'=>$imgs,
                'sr_owname'=>$user,
                'sr_phone'=>$phone,
                'sr_output_stand'=>$output_stand,
                'sr_oil'=>$oil,
                'sr_color'=>$color,
                'sr_purpose'=>$purpose,
                'sr_brt'=>$brt,
                'sr_jiance'=>0,
                'sr_out_time'=>$time
            ];
            if(Db::name('car_source')->insert($data)>0){
                return ['code'=>1,'msg'=>'插入成功'];
            }else{
                return ['code'=>0,'msg'=>'插入失败'];
            }

        }else{
            $level = Db::name('car_level')->select();
            $param = Db::name('param_va v')->join('param p','p.param_id = v.pv_param_id')->select();
            $fpay = Db::name('fpay')->select();
            $tpay = Db::name('tpay')->select();
            $brt = Db::name('brt')->select();
            return $this->fetch('',['level'=>$level,'param'=>$param,'fpay'=>$fpay,'tpay'=>$tpay
                ,'brt'=>$brt]);
        }
    }
    //图片回调
    public function sourceImg()
    {
        $request = Request::instance();
        if($request->isAjax()){ 
            $file = $request->file("img","");
            if($file){
                //文件保存到入口文件目录
                $info = $file->rule('date')->move("./uploads/source/");//第二个值为空时，表示原文件名
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
    public function srdelImg()
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
    //多级联动
    public function select()
    {
        $request = Request::instance();
        if($request->isPost()){
            $id = $request->param("id","");
            $next = $request->param('next',"");
            $query = Db::name('brand b');
            if($id){
                $query = Db::name('brand b')->join('series se','b.bnd_id = se.series_bnd_id')->where('se.series_bnd_id',$id);
            }
            if($next){
                $query = Db::name('series se')->join('car_type ty','se.series_id = ty.ty_series_id')->where('ty.ty_series_id',$next);
            }
            $res = $query->select();
            // ->where('bnd_id',$id)
            // ->join('series se','b.bnd_id = se.series_bnd_id','left')
            // ->join('car_type ty','se.series_id = ty.ty_series_id','left')
            echo json_encode($res);
        }   
    } 
    public function selectarea()
    {
        $request = Request::instance();
        if($request->isPost()){
            $id = $request->param("id","");
            $res = Db::name('area')->where('area_fid',$id)->select();
            echo json_encode($res);
        }   
    }
     //车源删除
    public function sourceDel()
    {
         $request = Request::instance();
        if($request->isAjax())
        {
            $id = $request->param("srid");
            if(Db::name("car_source")->where("sr_id",$id)->count()>0){
                //数据库删除
                $datas = Db::name("car_source")->delete($id);
                if($datas>0){
                    return ['code'=>1,'msg'=>"删除成功"];
                }else{
                    return ['code'=>0,'msg'=>"删除失败"];
                }
            }
        }
    }
    //编辑
    public function sourceUpdate()
    {
        $request = Request::instance();
        $id = $request->param('srid');
        if($request->isAjax()){
            $pid = $request->param('pid');
            $cid = $request->param('cid');
            $brand = $request->param('brand');
            $series = $request->param('series');
            $type = $request->param('type');
            $level = $request->param('level');
            $date_bord = $request->param('date_bord','','trim');
            $kilo = $request->param('kilo','','trim');
            $out = $request->param('out','','trim');
            $transbox = $request->param('transbox','');
            $price = $request->param('price','');
            //过户费
            $transfee = $request->param('transfee','');
            //分期
            $pay = $request->param('firstpay','');
            $car_des = $request->param('car_des','','trim');
            $output_stand = $request->param('output_stand','','trim'); 
            $oil = $request->param('oil','','trim');
            $color = $request->param('color','','trim');
            $purpose = $request->param('purpose','','trim');

            $brt = $request->param('brt/a','');
            $brt = json_encode($brt);
            $imgs = $request->param('imgs/a','');
            $imgs = json_encode($imgs);
            $user = $request->param('user','','trim');
            $phone = $request->param('phone','','trim');
            $time = time();
            $data = [
                'sr_area_id'=>$pid,
                'sr_area_next'=>$cid,
                'sr_bnd_id'=>$brand,
                'sr_series_id'=>$series,
                'sr_ty_id'=>$type,
                'sr_param_id'=>$transbox,
                'sr_le_id'=>$level,
                'sr_date_bord'=>$date_bord,
                'sr_run_kilo'=>$kilo,
                'sr_volume'=>$out,
                'sr_price'=>$price,
                'sr_trans_fee'=>$transfee,
                'sr_pay_id'=>$pay,
                'sr_car_des'=>$car_des,
                'sr_img'=>$imgs,
                'sr_owname'=>$user,
                'sr_phone'=>$phone,
                'sr_output_stand'=>$output_stand,
                'sr_oil'=>$oil,
                'sr_color'=>$color,
                'sr_purpose'=>$purpose,
                'sr_brt'=>$brt,
                'sr_jiance'=>0,
                'sr_out_time'=>$time
            ];
                if(Db::name('car_source')->where('sr_id',$id)->update($data)>0){
                    return ['code'=>1,'msg'=>'修改成功'];
                }else{
                    return ['code'=>0,'msg'=>'修改失败'];
            }
        }else{
        $data = Db::name('car_source')->where('sr_id',$id)->find();

        $area = Db::name('area')->where('area_fid','=',1)->select();

        $cid = $data['sr_area_id'];
        $next = Db::name('area')->where('area_fid','=',$cid)->select();

        $level = Db::name('car_level')->select();

        $param = Db::name('param_va v')->join('param p','p.param_id = v.pv_param_id')->select();

        $fpay = Db::name('fpay')->select();
        $tpay = Db::name('tpay')->select();
        $brt = Db::name('brt')->select();

        $brand = Db::name('brand')->select();
        $se = $data['sr_bnd_id'];
        $series = Db::name('series')->where("series_bnd_id",$se)->select();
        $ty = $data['sr_series_id'];
        $type = Db::name('car_type')->where('ty_series_id',$ty)->select();

        return $this->fetch('',['data'=>$data,'area'=>$area,'level'=>$level,'param'=>$param,'fpay'=>$fpay,'tpay'=>$tpay
                ,'brt'=>$brt,'next'=>$next,'brand'=>$brand,'series'=>$series,'type'=>$type]);
        }
    }
    //车辆检测
    public function carCheck()
    {
        $vars = Db::name('exterior_vars')->select();
        $parts = Db::name('exterior_part')->select();
        $engineroom = Db::name('check_var')->where('cvar_type',2)->select();
        $driveroom = Db::name('check_var')->where('cvar_type',3)->select();
        $start = Db::name('check_var')->where('cvar_type',4)->select();
        $roadtest = Db::name('check_var')->where('cvar_type',5)->select();
        $chassis = Db::name('check_var')->where('cvar_type',6)->select();
        $others = Db::name('check_var')->where('cvar_type',7)->select();
        return $this->fetch('',['vars'=>$vars,'parts'=>$parts,'engineroom'=>$engineroom,'driveroom'=>$driveroom,'start'=>$start,'roadtest'=>$roadtest,'chassis'=>$chassis,'others'=>$others]);
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
