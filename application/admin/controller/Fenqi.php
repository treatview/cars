<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Fenqi extends Controller
{
	public function paylist()
	{	
		$request = Request::instance();
        $cates = Db::name('tpay t')->join("fpay f","t.fid = f.fid")->select();;
        // print_r($cates);
        return $this->fetch('',['cates'=>$cates]);
	}
	public function payadd()
    {
    	 $request = Request::instance();
         if($request->isAjax()){
            //分期期数是否重复检测
            if($request->param("type",'')){
                if(Db::name("tpay")->where('tname',$request->param('tname',''))->count()>0){
                    return ['code'=>0,'msg'=>'期数已存在'];
                }else{
                    return ['code'=>1,'msg'=>'期数不重复'];
                }
            }
            //分期方式添加
            $fid = $request->param('fid','','trim,strip_tags');
            $tname = $request->param('tname','','trim,strip_tags');
            $int = $request->param('int','','trim,strip_tags');
            $ifshow = $request->param('switch',0);
            $data = [
                'fid'=>$fid,
                'tname'=>$tname,
                'int'=>$int,
                'ifshow'=>$ifshow
            ];
            if(Db::name('tpay')->insert($data)>0){
                return ['code'=>1,'msg'=>'添加成功'];
            }else{

                return ['code'=>0,'msg'=>'添加失败'];
            }
        }else{
            $cates = Db::name('fpay')->where('parent_id','0')->select();
                foreach($cates as $key=>$vo){
                    $childs = Db::name('fpay')->where('parent_id',$vo['fid'])->select();
                    $cates[$key]['childs'] = $childs;
                }
            $cates = ['cates'=>$cates];   
            return $this->fetch('',$cates);
        }
    }
    public function paydel()
    {
        //删除分期方式
        $request = Request::instance();

        if($request->isAjax()){

            $tid = $request->param('tid','');    
            $data = Db::name('tpay')->where('tid',$tid)->find();
           
            if(Db::name('tpay')->delete($data)>0){
                return ['code'=>1,'msg'=>'删除成功'];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }
    }
    public function updateifshow(){
        $request = Request::instance();
        $tid = $request->param('tid');
        $ifshow = Db::name('tpay')->where('tid',$tid)->find()['ifshow'];
        if($ifshow==1){
            $eidtifshow = 0;$msg = "已隐藏";
        }else{
            $eidtifshow = 1;$msg = '已显示';
        }
        if(Db::name('tpay')->where('tid',$tid)->update(['ifshow'=>$eidtifshow])>0){
            return ['code'=>1,'msg'=>$msg];
        }else{
            return ['code'=>0,'msg'=>'修改失败'];
        }
    }
    public function payupdate()
    {
        //获取数据
        $request = Request::instance();
        $tid = $request->param('tid','');
        
        $tpay = Db::name('tpay')->where('tid',$tid)->find();

        if($request->isAjax()){
            //修改分期方式数据
            $fid = $request->param('fid','','trim,strip_tags');
            $tname = $request->param('tname','','trim,strip_tags');
            $int = $request->param('int','','trim,strip_tags');
            $ifshow = $request->param('switch',0);
            $data = [
                'fid'=>$fid,
                'tname'=>$tname,
                'int'=>$int,
                'ifshow'=>$ifshow
            ];
            if(!$tid){
                return ["code"=>0,"msg"=>'数据出错，请稍后再试'];
            }
            //数据修改
            if(Db::name('tpay')->where('tid',$tid)->update($data)>0){
                return ['code'=>1,'msg'=>'修改成功'];
            }else{
                return ['code'=>0,'msg'=>'修改失败'];
            }
        }else{
            $cate = Db::name('tpay t')->join("fpay f","t.fid = f.fid")->find($tid);
            $cates = Db::name('fpay')->where('parent_id','0')->select();
                foreach($cates as $key=>$vo){
                    $childs = Db::name('fpay')->where('parent_id',$vo['fid'])->select();
                    $cates[$key]['childs'] = $childs;
                }
            $data = ['tpay'=>$tpay,'cates'=>$cates,'cate'=>$cate];
            return $this->fetch('',$data);
        }
    }
}