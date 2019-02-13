<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Cars extends Controller
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

    //亮点设置
    public function bright()
    {
        $request = Request::instance();
        $data = Db::name('brt')->select();
        return $this->fetch('',['data'=>$data]);
    }
    //新增亮点
    public function brightAdd()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $title = $request->param("title","","trim");
            $img = $request->param("imgs");
            $ifshow = $request->param('close',0);
            $data = [
                'brt_name'=>$title,
                'brt_img'=>$img,
                'brt_ifshow'=>$ifshow
            ];
            if(Db::name("brt")->insert($data)>0){
                return ['code'=>1,'msg'=>'添加成功']; 
            }else{
                return ['code'=>0,'msg'=>'添加失败'];
            }
        }else{
             return $this->fetch();
        }
        dump($request->param());
       
    }
    //图片回调
    public function brtImg()
    {
        $request = Request::instance();
        if($request->isAjax()){ 
            $file = $request->file("img","");
            if($file){
                //文件保存到入口文件目录
                $info = $file->rule('date')->move("./uploads/brt/");//第二个值为空时，表示原文件名
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
    //删除记录
    public function brtDel()
    {
        $request = Request::instance();
        if($request->isAjax())
        {
            $newsid = $request->param("brtid");
            if(Db::name("brt")->where("brt_id",$newsid)->count()>0){
                //删除文件图片
                $data = Db::name("brt")->where("brt_id",$newsid)->find();
                unlink($paths = ".".$data['brt_img']);
                //数据库删除
                $datas = Db::name("brt")->delete($newsid);
                if($datas>0){
                    return ['code'=>1,'msg'=>"删除成功"];
                }else{
                    return ['code'=>0,'msg'=>"删除失败"];
                }
            }
        }
    }
    //是否显示
    public function updateifcomand()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $news_id = $request->param("brtid");
            
            $ifcommand = Db::name("brt")->where("brt_id",$news_id)->find()["brt_ifshow"]; 
            if($ifcommand == 1){
                $code = 0; $msg = "已撤回";
            }else{
                $code = 1; $msg = "已显示";
            }
            if(Db::name("brt")->where("brt_id",$news_id)->update(["brt_ifshow"=>$code])>0){
                return ['code'=>1,'msg'=>$msg];
            }else{
                return ['code'=>0,'msg'=>'设置失败'];
            }

        }
    }
    //编辑记录
    public function brightUpdate()
    {
        $request = Request::instance();
        $id = $request->param("brtid");
        if($request->isAjax())
        {
            $brtid = $request->param("brtid");
            //数据处理
            $title = $request->param("title","","trim");
            $img = $request->param("imgs");
            $ifshow = $request->param('close',0);
            // dump($request->param());
            $data = [
                'brt_name'=>$title,
                'brt_img'=>$img,
                'brt_ifshow'=>$ifshow
            ];
            if(Db::name('brt')->where('brt_name',$title)->where('brt_id','<>',$brtid)->count()>0){
                return ['code'=>0,'msg'=>'亮点名已存在，请修改'];
            }else{
                if(Db::name('brt')->where("brt_id",$brtid)->update($data)>0){
                    return ['code'=>1,"msg"=>"修改成功"]; 
                }else{
                    return ['code'=>0,"msg"=>"未作操作，修改失败"];
                }
            }
        }else{
            $data = Db::name('brt')->where('brt_id',$id)->find();
            return $this->fetch('',['data'=>$data]);
        }
    }
    //二手车参数
    public function param()
    {
        $data = Db::name('param')->select();
        return $this->fetch('',['data'=>$data]);
    }

    //品牌管理
    public function brand()
    {
        $request = Request::instance();
        $data = Db::name('brand')->select();
        return $this->fetch("",['data'=>$data]);
    }
    public function test(){
        $request = Request::instance();

        $id = $request->param('id');
        $data = Db::name('brand')->where('bnd_id','like',"%$id%")->select();
        //记得还要取得数据表所有记录的行数
        $count = Db::name('brand')->where('bnd_id','like',"%$id%")->count();
        //固定格式
        $res['code'] = 0;
        $res['msg'] = '';
        $res['count'] = $count;
        $res['data'] = $data;
        return json($res);
    }
    //
    public function checkBndname()
    {
        $request = Request::instance();
        if($request->isGet()){
            $name = $request->param('name');
            if(Db::name('brand')->where('bnd_name',$name)->count()>0){
                return ['code'=>0,'msg'=>"用户名已存在"];
            }else{
                return ['code'=>1,"msg"=>'无'];
            }
        }
    }
    //品牌添加
    public function brandAdd()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $title = $request->param('title','','trim');
            $img = $request->param('imgs');
            $command = $request->param('command',0);
            $show = $request->param('close',0);
            $ping = $request->param('ping','','trim');
            $ucfirst = $request->param('ucfirst','','trim');
            $data = [
               'bnd_name'=>$title,
               'bnd_img'=>$img,
               'bnd_ifcommand'=>$command,
               'bnd_ping'=>$ping,
               'bnd_ifshow'=>$show, 
               'bnd_ucfirst'=>$ucfirst,
            ];
            if(Db::name('brand')->where('bnd_name',$title)->count()>0){
                return ['code'=>0,'msg'=>'该品牌名已存在'];
            }else{

                if(Db::name('brand')->insert($data)>0){
                    return ['code'=>1,'msg'=>'添加成功'];
                }else{
                    return ['code'=>0,'msg'=>'添加失败'];
                }
            }
        }else{
            return $this->fetch();
        }
    }
    //图片回调
    public function bndImg()
    {
        $request = Request::instance();
        if($request->isAjax()){ 
            $file = $request->file("img","");
            if($file){
                //文件保存到入口文件目录
                $info = $file->rule('date')->move("./uploads/brand/");//第二个值为空时，表示原文件名
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
    public function bnddelImg()
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
    //品牌是否显示
    public function bndupdateifcomand()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $bndid = $request->param("bndid");
            
            $ifcommand = Db::name("brand")->where("bnd_id",$bndid)->find()["bnd_ifcommand"]; 
            if($ifcommand == 1){
                $code = 0; $msg = "已撤回";
            }else{
                $code = 1; $msg = "已推荐";
            }
            if(Db::name("brand")->where("bnd_id",$bndid)->update(["bnd_ifcommand"=>$code])>0){
                return ['code'=>1,'msg'=>$msg];
            }else{
                return ['code'=>0,'msg'=>'设置失败'];
            }

        }
    }
    //品牌是否显示
    public function bndupdateifshow()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $bndid = $request->param("bndid");
            
            $ifcommand = Db::name("brand")->where("bnd_id",$bndid)->find()["bnd_ifshow"]; 
            if($ifcommand == 1){
                $code = 0; $msg = "已隐藏";
            }else{
                $code = 1; $msg = "已显示";
            }
            if(Db::name("brand")->where("bnd_id",$bndid)->update(["bnd_ifshow"=>$code])>0){
                return ['code'=>1,'msg'=>$msg];
            }else{
                return ['code'=>0,'msg'=>'设置失败'];
            }

        }
    }
    //品牌删除
    public function brandDel()
    {
         $request = Request::instance();
        if($request->isAjax())
        {
            $bndid = $request->param("bndid");
            //判断是否存在车系
            $series = Db::name('series')->where('series_bnd_id',$bndid)->count();
            if($series >0 ){
                    return ['code'=>0,'msg'=>'该品牌存在车系，不能删除'];
            }else{       
            if(Db::name("brand")->where("bnd_id",$bndid)->count()>0){
                //删除文件图片
                $data = Db::name("brand")->where("bnd_id",$bndid)->find();
                //数据库删除
                $datas = Db::name("brand")->delete($bndid);
                unlink($paths = ".".$data['bnd_img']);
                    if($datas>0){
                        return ['code'=>1,'msg'=>"删除成功"];
                    }else{
                        return ['code'=>0,'msg'=>"删除失败"];
                    }
            }else{
                    return ['code'=>0,'msg'=>'数据库异常'];
                }
            }
        }
    }
    //编辑
    public function brandUpdate()
    {
        $request = Request::instance();
        $id = $request->param("bndid");
        if($request->isAjax())
        {
            $id = $request->param("bndid");
            $title = $request->param('title','','trim');
            $img = $request->param('imgs');
            $command = $request->param('command',0);
            $show = $request->param('close',0);
            $ping = $request->param('ping','','trim');
            $ucfirst = $request->param('ucfirst','','trim');
            $data = [
               'bnd_name'=>$title,
               'bnd_img'=>$img,
               'bnd_ifcommand'=>$command,
               'bnd_ping'=>$ping,
               'bnd_ifshow'=>$show, 
               'bnd_ucfirst'=>$ucfirst,
            ];
            if(Db::name('brand')->where('bnd_name',$title)->where("bnd_id",'<>',$id)->update($data)>0){
                return ['code'=>0,"msg"=>"名称不能重复"];
            }else{
                if(Db::name('brand')->where("bnd_id",$id)->update($data)>0){
                    return ['code'=>1,"msg"=>"修改成功"]; 
                }else{
                    return ['code'=>0,"msg"=>"未作操作，修改失败"];
                }
            }
        }else{
            $data = Db::name('brand')->where('bnd_id',$id)->find();
            return $this->fetch('',['data'=>$data]);
        }
    }
    //参数值
    public function paramList(){
        $data = Db::name('param_va v')->join('param p','v.pv_param_id = p.param_id')->select();
        return $this->fetch('',['data'=>$data]);
    }
    //参数值添加
    public function paramVadd()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $id = $request->param('param','','trim');
            $name = $request->param('name','','trim');
            $value = $request->param('value','','trim');
            $data = [
               'pv_name'=>$name,
               'pv_value'=>$value,
               'pv_param_id'=>$id,
            ];
            if(Db::name('param_va')->insert($data)>0){
                return ['code'=>1,'msg'=>'添加成功'];
            }else{
                return ['code'=>0,'msg'=>'添加失败'];
            }
        }else{
            $param = Db::name('param')->select();
            return $this->fetch('',['param'=>$param]);
        }
    }
    //检查名
    public function checkpaname()
    {
        $request = Request::instance();
        if($request->isGet()){
            $name = $request->param('name');
            if(Db::name('param_va')->where('pv_name',$name)->count()>0){
                return ['code'=>0,'msg'=>"用户名已存在"];
            }else{
                return ['code'=>1,"msg"=>'无'];
            }
        }
    }
    //参数删除
    public function pvalueDel()
    {
         $request = Request::instance();
        if($request->isAjax())
        {
            $id = $request->param("vid");
            if(Db::name("param_va")->where("pv_id",$id)->count()>0){
                //数据库删除
                $datas = Db::name("param_va")->delete($id);
                if($datas>0){
                    return ['code'=>1,'msg'=>"删除成功"];
                }else{
                    return ['code'=>0,'msg'=>"删除失败"];
                }
            }
        }
    }
    //编辑
    public function paramVupdate()
    {
        $request = Request::instance();
        $id = $request->param('vid');
        if($request->isAjax()){
            $name = $request->param('name','','trim');
            $value = $request->param('value','','trim');
            $vid= $request->param('param','','trim');
            $data = [
               'pv_name'=>$name,
               'pv_value'=>$value,
               'pv_param_id'=>$vid,
            ];
            if(Db::name('param_va')->where('pv_name',$name)->where('pv_id','<>',$id)->count()>0){
                return ['code'=>0,'msg'=>'参数名称已存在重新设置'];
            }else{
                if(Db::name('param_va')->where('pv_id',$id)->update($data)>0){
                    return ['code'=>1,'msg'=>'修改成功'];
                }else{
                    return ['code'=>0,'msg'=>'请做修改'];
                }
            }
        }else{
        $list = Db::name('param_va')->where('pv_id',$id)->find();
        $param = Db::name('param')->select();
        return $this->fetch('',['data'=>$list,'param'=>$param]);
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
