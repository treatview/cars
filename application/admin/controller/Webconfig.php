<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Webconfig extends Controller
{
	public function configList()
	{
		$list = Db::name('webconfig')->select();
		return $this->fetch('',['list'=>$list]);
	}
	public function configDel()
    {
        $request = Request::instance();
        if($request->isAjax()){
        	$info = $request->param('info','');
	        $id = Db::name('webconfig')->field('id')->where('info',$info)->select();
	       	$id = $id[0]['id'];
            if($id>3){
            	if(Db::name('webconfig')->where('info',$info)->delete()>0){
            		return ['code'=>1,'msg'=>'删除成功'];
            	}else{
                return ['code'=>0,'msg'=>'删除失败'];
               }
            }else{
                return ['code'=>0,'msg'=>'该配置项不能删除'];
            }
        }
    }
    public function configUpdate()
    {
    	$request = Request::instance();
    	$list = $request->param();
    	foreach ($list as $key => $vo){
    		Db::name('webconfig')->where('varname',$key)->update(['value' => $vo]);
    	}
    	return ['code'=>1,'msg'=>'修改成功'];
    }
    public function configAdd()
    {
         $request = Request::instance();
         if($request->isAjax()){
            //配置项名是否重复
            if($request->param("type",'')){
                if(Db::name("webconfig")->where('varname',$request->param('varname',''))->count()>0){
                    return ['code'=>0,'msg'=>'配置项已经存在'];
                }else{
                    return ['code'=>1,'msg'=>'配置项未存在'];
                }
            }
            //配置项添加
            $info = $request->param('info','');
            $varname = $request->param('varname','');
            $value = $request->param('value','');
            $data = [
                'info'=>$info,
                'varname'=>$varname,
                'value'=>$value
            ];
            if(Db::name('webconfig')->insert($data)>0){
                return ['code'=>1,'msg'=>'添加成功'];
            }else{

                return ['code'=>0,'msg'=>'添加失败'];
            }
        }else{
        	return $this->fetch();
        }
    }
}