<?php

namespace app\admin\model;

use think\Model;

class NewsCate extends Model
{
     public function catetree(){
        $cateres = $this->select();
        return $this->sort($cateres);
    }
    //无限分类
    public function sort($data,$cate_parent_id=0,$level=0){
        static $arr = [];
        foreach($data as $k=>$v){
            if($v['cate_parent_id'] == $cate_parent_id){
                $v['cate_level'] = $level;
                $arr[] = $v;
                $this->sort($data,$v['cate_id'],$level+1);
            }
        }
        return $arr;
    }
}
