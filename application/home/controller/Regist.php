<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\captcha\Captcha;
use think\Db;

class Regist extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function getCateTree($id=0)
    {
        //
        $cates = Db::name('area')->where('fid',1)->select();
        foreach($cates as $key=>$vo){
            $childs = Db::name('area')->where('fid',$vo['id'])->select();
            $cates[$key]['childs'] = $childs;
        }
        return $cates;
    }
    public function regist()
    {
        $request = Request::instance(); 
        if ($request->isAjax()) {
            //注册数据
            $name = $request->param('user',"","trim");
            $phone = $request->param("mobilephone","","trim");
            $pwd = $request->param("rwd","","trim,md5");
            $pid = $request->param("pid");
            $cid = $request->param("cid");

            $pname = Db::name('area')->where('area_id',$pid)->find()['area_name'];
            $cname = Db::name('area')->where('area_id',$cid)->find()['area_name'];
            $arr = [];
            $arr = ['prov'=>$pname,'cname'=>$cname];
            $address = json_encode($arr);

            $data = [
                'user_name'=>$name,
                'user_phone'=>$phone,
                'user_pwd'=>$pwd,
                'user_address'=>$address,
                'user_switch' => 0,
            ];
            if(Db::name('user')->insert($data)>0){
                return ['code'=>1,'msg'=>'注册成功'];
            }else{
                return ['code'=>0,'msg'=>'注册成功'];
            }

        }else{        
            return $this->fetch();
        }
    }
    public function checkname()
    {
        $request = Request::instance();
        if($request->isGet()){
            $name = $request->param('name');
            if(Db::name('user')->where('user_name',$name)->count()>0){
                return ['code'=>0,'msg'=>"用户名已存在"];
            }else{
                return ['code'=>1,"msg"=>'无'];
            }
        }
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
    public function makeVerify()
    {
        $verify = new Captcha;
        return $verify->entry();
        //return $this->fetch();
    }
    public function checkVerify()
    {
        $request = Request::instance();
        $verify = new Captcha;
        $code = $request->param('verify',"");
        if ($verify->check($code)) {
            return ['code'=>1,'msg'=>'验证码正确'];
        }else{
            return ['code'=>0,'msg'=>'验证码错误'];
        }
    }
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
}
