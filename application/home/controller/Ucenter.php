<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;

class Ucenter extends Controller
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
    //密码修改页
    public function password()
    {
        return $this->fetch();
    }
    //密码修改
    //
    public function checkcode()
    {
        $request = Request::instance();
        if($request->isAjax()){
            $original = $request->param("first","","trim,md5");
            $next = $request->param('next',"","trim,md5");
            $name = $request->param('checkname');
            $pwd = Db::name('user')->where('user_name',$name)->find()['user_pwd'];

            if($pwd == $original){
                if($pwd == $next){
                    return ['code'=>0,'msg'=>"不能和原密码一致"];
                }else{

                   if(Db::name('user')->where('user_name',$name)->update(['user_pwd'=>$next])>0){
                        Session::delete('checkname');
                        return ['code'=>1,'msg'=>"修改成功"];
                    }else{
                        return ['code'=>0,'msg'=>'修改失败'];
                    } 
                }
            }else{
                return ['code'=>0,'msg'=>'原始密码输入错误'];
            }
        }
    }
    //基本资料
    public function basefile()
    {
        $request = Request::instance();
        if(Session::has('front_name')){
            $name = Session::get('front_name');
           $data = Db::name('user')->where("user_name",$name)->find(); 
        }else{
            $data = [];
        }
       return $this->fetch("",['data'=>$data]);
    }
    
    public function select()
    {
        $request = Request::instance();
        if($request->isPost()){
            $id = $request->param("id","");
            $res = Db::name('area')->where('area_fid',$id)->select();
            $res = $this->groupByInitials($res,"area_name");
            echo json_encode($res);
        }   
    }
    //修改地区
    
    public function correctArea()
    {
        $request = Request::instance();
        $name = Session::get('front_name');
        if($request->isAjax()){
            $pid = $request->param("pid");
            $cid = $request->param("cid");

            $pname = Db::name('area')->where('area_id',$pid)->find()['area_name'];
            $cname = Db::name('area')->where('area_id',$cid)->find()['area_name'];
            $arr = [];
            $arr = ['prov'=>$pname,'cname'=>$cname];
            $address = json_encode($arr);

            $data = [
                'user_address'=>$address,
            ];
            if(Db::name('user')->where('user_name',$name)->update($data)>0){
                return ['code'=>1,'msg'=>'修改成功'];
            }else{
                return ['code'=>0,'msg'=>'修改失败'];
            }
        }
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
      /**
     * 二维数组根据首字母分组排序
     * @param  array  $data      二维数组
     * @param  string $targetKey 首字母的键名
     * @return array             根据首字母关联的二维数组
     */
    public function groupByInitials(array $data, $targetKey = 'name')
    {
        $data = array_map(function ($item) use ($targetKey) {
            return array_merge($item, [
                'initials' => $this->getInitials($item[$targetKey]),
            ]);
        }, $data);
        $data = $this->sortInitials($data);
        return $data;
    }
 
    /**
     * 按字母排序
     * @param  array  $data
     * @return array
     */
    public function sortInitials(array $data)
    {
        $sortData = [];
        foreach ($data as $key => $value) {
            $sortData[$value['initials']][] = $value;
        }
        ksort($sortData);
        return $sortData;
    }
    
    /**
     * 获取首字母
     * @param  string $str 汉字字符串
     * @return string 首字母
     */
    public function getInitials($str)
    {
        if (empty($str)) {return '';}
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) {
            return strtoupper($str{0});
        }
 
        $s1  = iconv('UTF-8', 'gb2312', $str);
        $s2  = iconv('gb2312', 'UTF-8', $s1);
        $s   = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) {
            return 'A';
        }
 
        if ($asc >= -20283 && $asc <= -19776) {
            return 'B';
        }
 
        if ($asc >= -19775 && $asc <= -19219) {
            return 'C';
        }
 
        if ($asc >= -19218 && $asc <= -18711) {
            return 'D';
        }
 
        if ($asc >= -18710 && $asc <= -18527) {
            return 'E';
        }
 
        if ($asc >= -18526 && $asc <= -18240) {
            return 'F';
        }
 
        if ($asc >= -18239 && $asc <= -17923) {
            return 'G';
        }
 
        if ($asc >= -17922 && $asc <= -17418) {
            return 'H';
        }
 
        if ($asc >= -17417 && $asc <= -16475) {
            return 'J';
        }
 
        if ($asc >= -16474 && $asc <= -16213) {
            return 'K';
        }
 
        if ($asc >= -16212 && $asc <= -15641) {
            return 'L';
        }
 
        if ($asc >= -15640 && $asc <= -15166) {
            return 'M';
        }
 
        if ($asc >= -15165 && $asc <= -14923) {
            return 'N';
        }
 
        if ($asc >= -14922 && $asc <= -14915) {
            return 'O';
        }
 
        if ($asc >= -14914 && $asc <= -14631) {
            return 'P';
        }
 
        if ($asc >= -14630 && $asc <= -14150) {
            return 'Q';
        }
 
        if ($asc >= -14149 && $asc <= -14091) {
            return 'R';
        }
 
        if ($asc >= -14090 && $asc <= -13319) {
            return 'S';
        }
 
        if ($asc >= -13318 && $asc <= -12839) {
            return 'T';
        }
 
        if ($asc >= -12838 && $asc <= -12557) {
            return 'W';
        }
 
        if ($asc >= -12556 && $asc <= -11848) {
            return 'X';
        }
 
        if ($asc >= -11847 && $asc <= -11056) {
            return 'Y';
        }
 
        if ($asc >= -11055 && $asc <= -10247) {
            return 'Z';
        }
 
        return null;
    }
 
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
