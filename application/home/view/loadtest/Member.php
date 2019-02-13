<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;

class Member extends Controller
{
	public function index()
	{
		return $this->fetch();
	}
	
}