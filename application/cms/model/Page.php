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
/**
 * 单页模型
 * @package app\cms\model
 */
class Page extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__CMS_PAGE__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取单页标题列表
     * @author 蔡伟明 <314013107@qq.com>
     * @return array|mixed
     */
    public static function getList($map = [], $order = [])
    {
        if (isset($_GET['sign_cate']) || isset($_GET['sign_prof'])){
            if (isset($_GET['sign_cate'])){
                $sign_cate_num_all = count($_GET['sign_cate']);
        		$sign_where['preg_type'] = ['IN',$_GET['sign_cate']];
            }
            if (isset($_GET['sign_prof'])){
                $sign_prof_num_all = count($_GET['sign_prof']);
       			 $sign_where['prrg_pro']  = ['IN',$_GET['sign_prof']];
            }
            if (isset($_GET['sign_cate']) && isset($_GET['sign_prof'])){
                if ($sign_cate_num_all > $sign_prof_num_all){//大于
                    $sign_nums = $sign_cate_num_all;
                }elseif ($sign_cate_num_all < $sign_prof_num_all){//小于
                    $sign_nums = $sign_prof_num_all;
                }else{//等于
                    $sign_nums = $sign_cate_num_all;
                }
            }elseif (isset($_GET['sign_cate']) && empty($_GET['sign_prof'])){
                $sign_nums = $sign_cate_num_all;
            }elseif (empty($_GET['sign_cate']) && isset($_GET['sign_prof'])){
                $sign_nums = $sign_prof_num_all;
            }
//        $cate_str = implode(',',$_GET['sign_cate']);
//        $prof_str = implode(',',$_GET['sign_prof']);
//        $sign_where = 'preg_type IN ('.$cate_str.') AND prrg_pro IN ('.$prof_str.')';
         // 	dump($sign_nums);dump($sign_where);die();
        //先查出人员id 再查人员信息和此人员所做的项目
          /*
            $pregs_arr =
                Db::table('dp_query_preg')
                    ->field('pid')
                    ->where($sign_where)
                    ->group('pid')
                    ->having('count(pid)>='.$sign_nums)
                    ->select();
            弃用          */
            for ($cates = 0;$cates < $sign_cate_num_all;$cates++){
                $data_cate_arr[$cates] = Db::table('dp_query_preg')->where(['preg_type'=>$_GET['sign_cate'][$cates]])->column('pid');
            }
            if ($sign_cate_num_all==1){//只选择一个
                $data_pid_arr = $data_cate_arr[0];
            }
            if ($sign_cate_num_all==2){//选择两个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1]);
            }
            if ($sign_cate_num_all==3){//选择三个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1],$data_cate_arr[2]);
            }
            if ($sign_cate_num_all==4){//选择四个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1],$data_cate_arr[2],$data_cate_arr[3]);
            }
            if ($sign_cate_num_all==5){//选择五个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1],$data_cate_arr[2],$data_cate_arr[3],$data_cate_arr[4]);
            }
            if ($sign_cate_num_all==6){//选择六个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1],$data_cate_arr[2],$data_cate_arr[3],$data_cate_arr[4],$data_cate_arr[5]);
            }
            if ($sign_cate_num_all==7){//选择七个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1],$data_cate_arr[2],$data_cate_arr[3],$data_cate_arr[4],$data_cate_arr[5],$data_cate_arr[6]);
            }
            $data_pid_arr = array_values($data_pid_arr);
            //处理注册类别
//            if (isset($_GET['sign_cate'])){
//                $sign_cate_num_all = count($_GET['sign_cate']);
//                if ($sign_cate_num_all>1){
//                    $sign_cate = implode(',',$_GET['sign_cate']);
//                }else{
//                    $sign_cate = $_GET['sign_cate'][0];
//                }
//                //条件
//                $map['query_preg.preg_type'] = ['IN',$sign_cate];
//            }

            //处理注册专业
//            if (isset($_GET['sign_prof'])){
//                $sign_prof_num_all = count($_GET['sign_prof']);
//                if ($sign_prof_num_all>1){
//                    $sign_prof = implode(',',$_GET['sign_prof']);
//                }else{
//                    $sign_prof = $_GET['sign_prof'][0];
//                }
//                //条件
//                $map['query_preg.prrg_pro'] = ['IN',$sign_prof];
//            }
            if (isset($data_pid_arr)) {
                $pregs_arr_nums = count($data_pid_arr);
                for ($i=0;$i<$pregs_arr_nums;$i++){
                    //筛选条件
                    $map['query_people.people_id'] = $data_pid_arr[$i];
                    $data_list[$i] =
                        Db::view('query_people', 'people_name,people_sex,people_ttype,people_num')
                            //公司表
                            ->view('query_company',
                                'company_name',
                                'query_company.company_id = query_people.cid'
                            )
                            //执业注册信息
//                            ->view('query_preg',
//                                'preg_id,preg_type,preg_certnum,preg_ynum,preg_date,prrg_pro',
//                                'query_people.people_id = query_preg.pid',
//                                'LEFT')
                            //个人工程业绩
                            ->view('query_pwork',
                                'pwork_id,pwork_num,pwork_name,pwork_addr,pwork_type,pwork_unit',
                                'query_people.people_id = query_pwork.pid',
                                'LEFT')
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
    public static function getListAll($getInfo){
        if (isset($getInfo['sign_cate']) || isset($getInfo['sign_prof'])){
            if (isset($getInfo['sign_cate'])){
                $sign_cate_num_all = count($getInfo['sign_cate']);
            }
            if (isset($getInfo['sign_prof'])){
                $sign_prof_num_all = count($getInfo['sign_prof']);
            }
            if (isset($getInfo['sign_cate']) && isset($getInfo['sign_prof'])){
                if ($sign_cate_num_all > $sign_prof_num_all){//大于
                    $sign_nums = $sign_cate_num_all;
                }elseif ($sign_cate_num_all < $sign_prof_num_all){//小于
                    $sign_nums = $sign_prof_num_all;
                }else{//等于
                    $sign_nums = $sign_cate_num_all;
                }
            }elseif (isset($getInfo['sign_cate']) && empty($getInfo['sign_prof'])){
                $sign_nums = $sign_cate_num_all;
            }elseif (empty($getInfo['sign_cate']) && isset($getInfo['sign_prof'])){
                $sign_nums = $sign_prof_num_all;
            }


//        $cate_str = implode(',',$getInfo['sign_cate']);
//        $prof_str = implode(',',$getInfo['sign_prof']);
//        $sign_where = 'preg_type IN ('.$cate_str.') AND prrg_pro IN ('.$prof_str.')';
//            $sign_where['preg_type'] = ['IN',$getInfo['sign_cate']];
//            $sign_where['prrg_pro']  = ['IN',$getInfo['sign_prof']];
//            //先查出人员id 再查人员信息和此人员所做的项目
//            $pregs_arr =
//                Db::table('dp_query_preg')
//                    ->field('pid')
//                    ->where($sign_where)
//                    ->group('pid')
//                    ->having('count(pid)>='.$sign_nums)
//                    ->select();
            for ($cates = 0;$cates < $sign_cate_num_all;$cates++){
                $data_cate_arr[$cates] = Db::table('dp_query_preg')->where(['preg_type'=>$getInfo['sign_cate'][$cates]])->column('pid');
            }
            if ($sign_cate_num_all==1){//只选择一个
                $data_pid_arr = $data_cate_arr[0];
            }
            if ($sign_cate_num_all==2){//选择两个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1]);
            }
            if ($sign_cate_num_all==3){//选择三个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1],$data_cate_arr[2]);
            }
            if ($sign_cate_num_all==4){//选择四个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1],$data_cate_arr[2],$data_cate_arr[3]);
            }
            if ($sign_cate_num_all==5){//选择五个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1],$data_cate_arr[2],$data_cate_arr[3],$data_cate_arr[4]);
            }
            if ($sign_cate_num_all==6){//选择六个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1],$data_cate_arr[2],$data_cate_arr[3],$data_cate_arr[4],$data_cate_arr[5]);
            }
            if ($sign_cate_num_all==7){//选择七个
                $data_pid_arr = array_intersect($data_cate_arr[0],$data_cate_arr[1],$data_cate_arr[2],$data_cate_arr[3],$data_cate_arr[4],$data_cate_arr[5],$data_cate_arr[6]);
            }

            $data_pid_arr = array_values($data_pid_arr);
//        //处理注册类别
//        if (isset($getInfo['sign_cate'])){
//            $sign_cate_num_all = count($getInfo['sign_cate']);
//            if ($sign_cate_num_all>1){
//                $sign_cate = implode(',',$getInfo['sign_cate']);
//            }else{
//                $sign_cate = $getInfo['sign_cate'][0];
//            }
//            //条件
//            $map['query_preg.preg_type'] = ['IN',$sign_cate];
//        }
//
//        //处理注册专业
//        if (isset($getInfo['sign_prof'])){
//            $sign_prof_num_all = count($getInfo['sign_prof']);
//            if ($sign_prof_num_all>1){
//                $sign_prof = implode(',',$getInfo['sign_prof']);
//            }else{
//                $sign_prof = $getInfo['sign_prof'][0];
//            }
//            //条件
//            $map['query_preg.prrg_pro'] = ['IN',$sign_prof];
//        }
            if (isset($data_pid_arr)) {
                $pregs_arr_nums = count($data_pid_arr);
                for ($i=0;$i<$pregs_arr_nums;$i++){
                    //筛选条件
                    $map['query_people.people_id'] = $data_pid_arr[$i];
                $data_list[$i] =
                    Db::view('query_people','people_name,people_sex,people_ttype,people_num')
                    //公司表
                        ->view('query_company',
                            'company_name',
                            'query_company.company_id = query_people.cid'
                        )
        //                //执业注册信息
//                    ->view('query_preg',
//                        'preg_id,preg_type,preg_certnum,preg_ynum,preg_date,prrg_pro',
//                        'query_people.people_id = query_preg.pid',
//                        'LEFT')

        //            //个人工程业绩
                    ->view('query_pwork',
                        'pwork_id,pwork_num,pwork_name,pwork_addr,pwork_type,pwork_unit',
                        'query_people.people_id = query_pwork.pid',
                            'LEFT')
        //            //诚信表
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
                    //时间戳转格式
                    foreach ($data_list_final as $key=>$value){
                        foreach ($value as $k=>$v){
                            if ($k == 'preg_date'){//有效期
                                $data_list_final[$key][$k] = date('Y-m-d',$v);
                            }
                        }
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
    //查找注册类别-多选
    public static function get_sign_cate(){
        $sign_cate = Db::table('dp_query_preg')->where(['preg_type'=>['neq','']])->distinct('preg_type')->column('preg_type');
        return $sign_cate;
    }
    //查找注册专业-多选
    public static function get_sign_prof(){
        $sign_prof = Db::table('dp_query_preg')->where(['prrg_pro'=>['neq','']])->distinct('prrg_pro')->column('prrg_pro');
        return $sign_prof;
    }
}