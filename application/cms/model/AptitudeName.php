<?php
/**
 * Created by PhpStorm.
 * User: alinejun
 * Date: 2018/10/25
 * Time: 21:56
 */

namespace app\cms\model;


use think\Db;

class AptitudeName
{
    //获取所有资质分类名
    public static function getAptitudeName()
    {
        $map['cname_name'] = ['not like','%不分等级%'];
        $map1['cname_name'] = ['like','%级%'];
        //所有含级的数据
        $data1 = Db::table('dp_query_cname')->where($map)->where($map1)->column('cname_name');
        //所有不分等级的数据
        $map=$map1=[];
        $map['cname_name'] = ['like','%不分等级%'];
        $data2 = Db::table('dp_query_cname')->where($map)->column('cname_name');
        //所有不含级的数据
        $map = [];
        $map['cname_name'] = ['not like','%级%'];
        $data3 = Db::table('dp_query_cname')->where($map)->column('cname_name');
        $data1=self::cuttingData($data1,2);
        self::addNewTableData($data1);
        self::getNewTableValue($data1);
        self::setPid();
    }

    //切割数据

    /**
     * @param array $data 需要切的数据
     * @param int $num     从后往前切掉n个字符
     */
    public static function cuttingData($data = [],$num=0)
    {
        foreach ($data as &$v){
            $v =  mb_substr(trim($v),0,-($num));
        }
        return $data;
    }

    //向新表中插入数据
    public static function addNewTableData($data = [],$num=0)
    {
        $arr = [];
        foreach ($data as $v){
            $arr[]['name'] = $v;
        }
        $res = Db::table('dp_zhj_aptitude_name')->insertAll($arr);
        echo $res;
    }
    //取出新表的值，按照名字排序,然后重新插入新表
    public static function getNewTableValue()
    {

        $res = Db::table('dp_zhj_aptitude_name')->order('name')->distinct('name')->field('name')->select();

        $res2 = Db::table('dp_zhj_aptitude_class')->insertAll($res);
    }

    //将原表的值加上父id

    public static  function setPid()
    {
        $map['cname_name'] = ['not like','%不分等级%'];
        $map1['cname_name'] = ['like','%级%'];
        //$map2['cname_name'] = ['not like','%（%'];
        //所有含级的数据
        $res2 = Db::table('dp_query_cname')->where($map)->where($map1)->where(['pid'=>0])->column('cname_name');

        foreach($res2 as $value){
            $name = mb_substr(trim($value),0,-2);
            $map2['name'] = $name;
            $id = Db::table('dp_zhj_aptitude_class')->where($map2)->column('id');
            if($id){
                Db::table('dp_query_cname')->where(['cname_name'=>$value])->setField('pid',$id[0]);
            }
        }
    }

    //将数组变为tree
    public static function setTree($arr=[])
    {
        //测试数据
        //$arr = [1,2,3,4,5,6,7,132,23,80];
        $data1 = Db::table('dp_query_cname')->where(['cname_id'=>['in',$arr]])->distinct('pid')->column('pid');

        foreach($data1 as $v){
            $data2[$v]= Db::table('dp_query_cname')->where(['cname_id'=>['in',$arr],'pid'=>$v])->select();
        }
        return $data2;
       /* $sql = $sql2='';
        foreach ($data2 as $value){
                foreach ($value as $k=>$v){
                    if($k<(count($value)-1)){
                        $sql .= "ctype_cnid = ".$v['cname_id'].' or ';
                    }else{
                        $sql .= "ctype_cnid = ".$v['cname_id'];
                    }
                }
            $sql2 .=' and  ('.$sql.')';
            $sql='';
        }
        return '1 '.$sql2;exit;*/
    }
}