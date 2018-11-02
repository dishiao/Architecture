<?php

namespace app\cms\admin;

use app\admin\controller\Admin;
use think\Db;
ini_set('memory_limit', '-1');

class Corntab extends Admin
{
  
  //定时删除乱码deleteMessyCode
  public function index(){
    	$type = ['公开招标','直接委托','其他','邀请招标','-'];
      	$map['pbided_way'] = ['not in',$type];
     	Db::table('dp_query_pbided')->where($map)->delete();
    
    	$typee = ['施工','监理','工程总承包','设计施工一体化','-','勘察','设计','其他'];
    	$mapp['pbided_type'] = ['not in',$typee];
    	Db::table('dp_query_pbided')->where($mapp)->delete();
    	$this->success('清除乱码成功！');
    }

}