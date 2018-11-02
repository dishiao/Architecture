<?php

// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\cms\model;

use think\Model as ThinkModel;
use think\Db;

ini_set('memory_limit', '-1');

class Scp extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__CMS_DOCUMENT__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取列表
     * @param array $map 筛选条件
     * @param array $order 排序
     * @return mixed
     */
    public static function getList()
    {
//        $map[''] = $_GET['is_sc']; // 暂时不处理是否入川选项， 前端采取的措施， 只是显示隐藏了一下，还是会传值到后端来，不处理
        if (isset($_GET['sign_cate']) || isset($_GET['sign_prof'])) {
            if (isset($_GET['sign_prof'])) {
                $sign_prof_num_all = count($_GET['sign_prof']);
            	$sign_where['type']  = ['IN',$_GET['sign_prof']];
            }
            if (isset($_GET['sign_cate'])){
                $cate_type = $_GET['sign_cate'];
               // $map['sichuan_people.news'] = intval($cate_type[0]);
            }
            $types =
                Db::table('dp_people_zhengshu')
                    ->field('nid')
                    ->where($sign_where)
                    ->group('nid')
                    ->having('count(nid)>='.$sign_prof_num_all)
                    ->select();
          //dump($types);die();
            if (isset($types)){
                $types_num = count($types);
                for ($i=0;$i<$types_num;$i++){
                    //筛选条件
                  
                    $map['sichuan_people.id'] = $types[$i]['nid'];
                    $data_list[$i] = Db::view('sichuan_people', 'nickname,sex,tel,xueli,over,tech,msg,news')
//                //四川人左侧分类
//                ->view('people_insc',
//                    'name',
//                    'sichuan_people.news = people_insc.uid',
//                    'LEFT'
//                )
//                //四川人员证书
//                ->view('people_zhengshu',
//                    'type,pro,level,pronum,number,zhigenum,starttime,endtime,icardname,exchange',
//                    'sichuan_people.id = people_zhengshu.nid',
//                    'LEFT'
//                )
                        //公司名称
                        ->view('sichuan_company',
                            'company_name,company_num',
                            'sichuan_people.curl = sichuan_company.url',
                            'LEFT'
                        )
//                        //公司分类-取是否入川
//                        ->view('is_insc',
//                            'is_sc',
//                            'sichuan_company.news = is_insc.uid',
//                            'LEFT'
//                        )
                        //全国公司表
                        ->view('query_company',
                            'company_id',
                            ' sichuan_company.company_num = query_company.company_num',
                            'LEFT'
                        )
                        //全国人员表
                        ->view('query_people',
                            'people_id,people_url',
                            'query_people.cid = query_company.company_id',
                            'LEFT'
                        )
//                        //执业注册信息
//                        ->view('query_preg',
//                            'preg_id,preg_type,preg_certnum,preg_ynum,preg_date,prrg_pro',
//                            'query_people.people_id = query_preg.pid',
//                            'LEFT')
                        //个人工程业绩 不用left左联的原因是 没有做项目的就不显示出来了。以免重复数据太多
                        ->view('query_pwork',
                            'pwork_num,pwork_name',
                            'query_people.people_id = query_pwork.pid')
                        //诚信表
                        ->view('query_honest',
                            'honest_num,honest_name,honest_content,honest_dept,honest_date,honest_type',
                            'query_people.people_url = query_honest.honest_url',
                            'LEFT')
                        ->where($map)
                        ->select();
                  //dump($data_list);die();
                }
                if (isset($data_list) && !empty($data_list)){//处理数据
                    $data_list_num = count($data_list);
                    $data_list_final=[];
                    for ($j=0;$j<$data_list_num;$j++){
                        $data_list_final = array_merge($data_list[$j],$data_list_final);
                    }
                }else{
                    $data_list_final = null;
                }
            }
         // dump($data_list_final);die();
            return $data_list_final;
        }else{
            $data_list = null;
            return $data_list;
        }
    }
    public static function getListAll($getInfo){
        if (isset($getInfo['sign_cate']) || isset($getInfo['sign_prof'])) {
            if (isset($getInfo['sign_prof'])) {
                $sign_prof_num_all = count($getInfo['sign_prof']);
            }
            $sign_where['type']  = ['IN',$getInfo['sign_prof']];
            if (isset($getInfo['sign_cate'])){
                $cate_type = $getInfo['sign_cate'];
                //$map['sichuan_people.news'] = intval($cate_type[0]);
            }
            $types =
                Db::table('dp_people_zhengshu')
                    ->field('nid')
                    ->where($sign_where)
                    ->group('nid')
                    ->having('count(nid)>='.$sign_prof_num_all)
                    ->select();
            if (isset($types)){
                $types_num = count($types);
                for ($i=0;$i<$types_num;$i++){
                    //筛选条件
                    $map['sichuan_people.id'] = $types[$i]['nid'];
                    $data_list[$i] = Db::view('sichuan_people', 'nickname,sex,tel,xueli,over,tech,msg,news')
//
                        //公司名称
                        ->view('sichuan_company',
                            'company_name,company_num',
                            'sichuan_people.curl = sichuan_company.url',
                            'LEFT'
                        )
//
                        //全国公司表
                        ->view('query_company',
                            'company_id',
                            ' sichuan_company.company_num = query_company.company_num',
                            'LEFT'
                        )
                        //全国人员表
                        ->view('query_people',
                            'people_id,people_url',
                            'query_people.cid = query_company.company_id',
                            'LEFT'
                        )
//
                        //个人工程业绩
                        ->view('query_pwork',
                            'pwork_num,pwork_name',
                            'query_people.people_id = query_pwork.pid')
                        //诚信表
                        ->view('query_honest',
                            'honest_num,honest_name,honest_content,honest_dept,honest_date,honest_type',
                            'query_people.people_url = query_honest.honest_url',
                            'LEFT')
                        ->where($map)
                        ->select();
                }
                if (isset($data_list) && !empty($data_list)){//处理数据
                    $data_list_num = count($data_list);
                    $data_list_final=[];
                    for ($j=0;$j<$data_list_num;$j++){
                        $data_list_final = array_merge($data_list[$j],$data_list_final);
                    }
                }else{
                    $data_list_final = null;
                }
            }
            return $data_list_final;
        }else{
            $data_list = null;
            return $data_list;
        }
    }
    public static function get_sign_cate(){//人员分类
        $sign_cate = Db::table('dp_people_insc')
            ->where(['name'=>['neq','']])
            ->distinct('name')
            ->field('uid,name')
            ->select();
        return $sign_cate;
    }
    public static function get_sign_prof(){//证书类型
        $sign_prof = Db::table('dp_people_zhengshu')
            ->where(['type'=>['neq','']])
            ->distinct('type')
            ->column('type');
        return $sign_prof;
    }
}