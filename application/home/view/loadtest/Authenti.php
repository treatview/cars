<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;

class Authenti extends Controller
{
	public function authenti()
	{
		return $this->fetch();
	}
	
}