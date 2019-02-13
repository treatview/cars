<?php
function get_cate($cates,$parent_id = 0){
     static $classes = array();   // 让数据在递归中保持上次得到的结果
     foreach($cates as $vo){
        if($parent_id== $vo['parent_id']){
          $classes[]=$vo;
          get_cate($cates,$vo['cate_id']);
        }
     }
     return $classes;
  }
 function get($name){
	return $name;
}