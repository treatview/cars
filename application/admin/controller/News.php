<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\NewsCate;
use think\Db;

class News extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    
    //分类添加
    public function catesAdd()
    {
        $request = Request::instance();
        if($request->isAjax()){
            //分类名是否重复检测
            if($request->param("type",'')){
                if(Db::name("news_cate")->where('cate_name',$request->param('cate_name',''))->count()>0){
                    return ['code'=>0,'msg'=>'分类名已存在'];
                }else{
                    return ['code'=>1,'msg'=>'分类名不重复'];
                }
            }
            //数据处理
            $data = [
                'cate_name'=>$request->param('cate_name','','trim,strip_tags'),
                'cate_ifshow'=>$request->param('switch',0),
                'cate_parent_id'=>$request->param('parent_id'),
            ];
            if($data['cate_parent_id']==0){
                $level = 0;
            }else{
                $level = Db::name('news_cate')->where('cate_id',$data['cate_parent_id'])->find()['cate_level']+1;
                if($level>2){
                    return ['code'=>0,'msg'=>'不支持添加二级分类的子分类'];
                }
            }
            $data['cate_level'] = $level;
            if(Db::name('news_cate')->insert($data)>0){
                return ['code'=>1,'msg'=>'添加成功'];
            }else{
                return ['code'=>0,'msg'=>'添加失败'];
            }
        }else{
            $cates = $this->getCateTree();
            $data = ['cates'=>$cates];
            return $this->fetch('',$data);
        }
    }
    //分类删除
    public function cateDel()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $cateid = $request->param('cateid','');
            if(Db::name('news_cate')->where('cate_parent_id',$cateid)->count()>0){
                return ['code'=>0,'msg'=>'该分类下有子分类，不能删除'];
            }
            if(Db::name('news_cate')->delete($cateid)>0){
                return ['code'=>1,'msg'=>'删除成功'];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }
    }
    //显示隐藏
    public function updateifshow(){
        $request = Request::instance();
        $cate_id = $request->param('cateid');
        $ifshow = Db::name('news_cate')->where('cate_id',$cate_id)->find()['cate_ifshow'];
        if($ifshow==1){
            $eidtifshow = 0;$msg = "已隐藏";
        }else{
            $eidtifshow = 1;$msg = '已显示';
        }
        if(Db::name('news_cate')->where('cate_id',$cate_id)->update(['cate_ifshow'=>$eidtifshow])>0){
            return ['code'=>1,'msg'=>$msg];
        }else{
            return ['code'=>0,'msg'=>'修改失败'];
        }
    }
    public function catesUpdate()
    {
        $request = Request::instance();
        $cateid = $request->param('cateid','');
        if($request->isAjax()){
            // dump($request->param());
            $data = [
                'cate_name' => $request->param('cate_name',''),
                'cate_parent_id'=>$request->param('parent_id',''),
                'cate_ifshow'=>$request->param('switch',0)
            ];
            if(!$cateid){
                return ["code"=>0,"msg"=>'数据出错，请稍后再试'];
            }
            //检查修改后的分类名是否重复
            if($request->param('type','')){
                // dump($request->param());
                if(Db::name('news_cate')->where('cate_name',$data['cate_name'])->where('cate_id','<>',$cateid)->count()>0){
                    return ['code'=>0,'msg'=>'分类名已存在，请重新设置'];
                }else{
                    return ['code'=>1];
                }
                
            }
            //数据修改
                //判断被修改的分类下是否有子分类，有子分类，只能修改分类名和是否显示
            if(Db::name('news_cate')->where('cate_parent_id',$cateid)->count()>0){
                unset($data['cate_parent_id']);
                if(Db::name('news_cate')->where('cate_id',$cateid)->update($data)>0){
                    return ['code'=>1,'msg'=>'修改成功'];
                }else{
                    return ['code'=>0,'msg'=>'修改失败'];
                }
            }
                      
            //分类下无子分类，则可以修改分类名，是否显示和上级分类
            if($data['cate_parent_id']==0){
                $level = 1;
            }else{
                $level = Db::name('news_cate')->find($data['cate_parent_id'])['cate_level']+1;
                if($level>2){
                    return ['code'=>0,'msg'=>'不支持设置为二级分类的子分类'];
                }
            }
            $data['cate_level'] = $level;
             // dump($data); 
            if(Db::name('news_cate')->where('cate_id',$cateid)->update($data)>0){
                return ['code'=>1,'msg'=>'修改成功'];
            }else{
                return ['code'=>0,'msg'=>'修改失败'];
            }
        }else{
            $cates = $this->getCateTree($cateid);
            // print_r($cates);
            $cate = Db::name('news_cate')->find($cateid);
            return $this->fetch('',['cates'=>$cates,'cate'=>$cate]);
        }
    }
    //分类列表
    public function cateList()
    {
        $cater = $this->getCateTree(); 
        $data = ['cates' => $cater];
        return $this->fetch("",$data);
    }
    //新闻展示
    public function newsList()
    {
        $data = Db::name('news ns')->join("news_cate nc","ns.news_cate_id = nc.cate_id")->select();
        $datas = ['data' => $data,
                
            ];
        return $this->fetch("",$datas);
    }

    public function newsAdd()
    {
        $request = Request::instance();
        if($request->isAjax()){
            //数据处理
            $title  = $request->param("title","",'trim,strip_tags');
            $keywords  = $request->param("keywords","",'trim,strip_tags');
            $des  = $request->param("des","",'trim,strip_tags');
            $author  = $request->param("author","",'trim,strip_tags');
            $content  = $request->param("content","");
            $close  = $request->param("close",0);
            $paths = $request->param("imgs/a");
            $path = json_encode($paths);
            $time = time();
            $cateid = $request->param("parent_id");
            $data = [
                "news_title"=>$title,
                "news_keywords"=>$keywords,
                "news_des"=>$des,"news_author"=>$author,
                "news_click"=>0,
                "news_pic"=>$path,
                "news_content"=>$content,
                "news_ifcommand"=>$close,
                "news_puttime"=>$time,
                "news_cate_id"=>$cateid,
            ];
            if(Db::name("news")->insert($data)>0){
                return ['code'=>1,'msg'=>'添加成功']; 
            }else{
                return ['code'=>0,'msg'=>'添加失败'];
            }

        }else{
            $cater = $this->getCateTree(); 
            $data = ['cates' => $cater];
            return $this->fetch("",$data);
            } 
    }
    //图片回调
    public function newsImg()
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
/*            if($request->param("imgspath","")){
                $path = $request->param("imgspath");
                $path = ".".$path;
                if(is_file($path)){
                    unlink($path);
                    if(!is_file($path)){
                        $resp = ['code'=>1,'msg'=>"删除成功"];
                }else{
                    $resp = ['code'=>0,'msg'=>"删除失败"];
                    }
                     echo json_encode($resp);
                }
            }*/
        }
    }

    //是否推荐
    public function updateifcomand()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $news_id = $request->param("newsid");
            
            $ifcommand = Db::name("news")->where("news_id",$news_id)->find()["news_ifcommand"]; 
            if($ifcommand == 1){
                $code = 0; $msg = "已撤回";
            }else{
                $code = 1; $msg = "已推荐";
            }
            if(Db::name("news")->where("news_id",$news_id)->update(["news_ifcommand"=>$code])>0){
                return ['code'=>1,'msg'=>$msg];
            }else{
                return ['code'=>0,'msg'=>'设置失败'];
            }

        }
    }
    //编辑记录
    public function newsUpdate()
    {
        $request = Request::instance();
        $newsid = $request->param("newsid");
        if($request->isAjax())
        {
            $newid = $request->param("newsid");
            //数据处理
            $title  = $request->param("title","",'trim,strip_tags');
            $keywords  = $request->param("keywords","",'trim,strip_tags');
            $des  = $request->param("des","",'trim,strip_tags');
            $author  = $request->param("author","",'trim,strip_tags');
            $content  = $request->param("content","");
            $close  = $request->param("close",0);
            $paths = $request->param("imgs/a");
            $path = json_encode($paths);
            $time = time();
            $parentid = $request->param("parent_id");
            $data = [
                "news_title"=>$title,
                "news_keywords"=>$keywords,
                "news_des"=>$des,
                "news_author"=>$author,
                "news_click"=>0,
                "news_pic"=>$path,
                "news_content"=>$content,
                "news_ifcommand"=>$close,
                "news_puttime"=>$time,
                "news_cate_id"=>$parentid,
            ];
            if(Db::name('news')->where("news_id",$newsid)->update($data)>0){
                return ['code'=>1,"msg"=>"修改成功"]; 
            }else{
                return ['code'=>0,"msg"=>"修改失败"];
            }
        }else{

            $data = Db::name("news ns")->join("news_cate nc","ns.news_cate_id = nc.cate_id")->where("news_id",$newsid)->find();
            $cater = $this->getCateTree();
            $datas = ['data'=>$data,
                    'cates' => $cater
            ];
            return $this->fetch("",$datas);
        }
    }
    //删除记录
    public function newsDel()
    {
        $request = Request::instance();
        if($request->isAjax())
        {
            $newsid = $request->param("newsid");
            if(Db::name("news")->where("news_id",$newsid)->count()>0){
                //删除文件图片
                $data = Db::name("news")->where("news_id",$newsid)->find();

                $path = (json_decode($data['news_pic']));
                foreach ($path as $key => $v) {
                    unlink($paths = "./uploads/".$v);
                }
                //数据库删除
                $datas = Db::name("news")->delete($newsid);
                if($datas>0){
                    return ['code'=>1,'msg'=>"删除成功"];
                }else{
                    return ['code'=>0,'msg'=>"删除失败"];
                }
            }
        }
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function getCateTree()
    {
        //
        $cates = Db::name('news_cate')->where('cate_parent_id',0)->select();
        foreach($cates as $key=>$vo){
            $childs = Db::name('news_cate')->where('cate_parent_id',$vo['cate_id'])->select();
            $cates[$key]['childs'] = $childs;
        }
        return $cates;
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
