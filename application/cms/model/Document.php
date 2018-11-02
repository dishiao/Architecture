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
use app\cms\model\AptitudeName as AptitudeNameModel;
ini_set('memory_limit', '-1');

class Document extends ThinkModel
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
    public static function getList($map = [], $order = [])
    {

        //中标金额
        if (isset($_GET['money']) && $_GET['money']>0){
            $get_money = $_GET['money'];
            $map['query_pbided.pbided_money'] = ['>',$get_money];
        }//最早开工日期
        if (isset($_GET['starttime']) && !empty($_GET['starttime'])){
            $get_starttime = strtotime($_GET['starttime']);
            $map['query_pfinish.pfinish_realbegin'] = ['>',$get_starttime];
        }//最迟竣工日期
        if (isset($_GET['endtime']) && !empty($_GET['endtime'])){
            $get_endtime = strtotime($_GET['endtime']);
            $map['query_pfinish.pfinish_realfinish'] = ['<',$get_endtime];
        }//项目分类
        if (isset($_GET['pro_cate']) && $_GET['pro_cate']!='0'){
            $get_pro_cate = $_GET['pro_cate'];
            $map['query_project.project_type'] = $get_pro_cate;
        }//建设性质
        if (isset($_GET['pro_prop']) && $_GET['pro_prop']!='0'){
            $get_pro_prop = $_GET['pro_prop'];
            $map['query_project.project_nature'] = $get_pro_prop;
        }//工程用途
        if (isset($_GET['pro_use']) && $_GET['pro_use']!='0'){
            $get_pro_use = $_GET['pro_use'];
            $map['query_project.project_use'] = $get_pro_use;
        }//招标类别
        if (isset($_GET['pb_type']) && $_GET['pb_type']!='0'){
            $get_pb_type = $_GET['pb_type'];
            $map['query_pbided.pbided_type'] = $get_pb_type;
        }


        if (isset($_GET['c_type']) && isset($_GET['c_name'])){
            //查询出符合资质类型和名称的公司ID
            //处理资质名称
//            $c_name_num_all = count($_GET['c_name']);
//            if ($c_name_num_all>1){
//                $c_name = implode(',',$_GET['c_name']);
//            }else{
//                $c_name = $_GET['c_name'][0];
//            }
//            $where = ' ctype_cnid IN ('.$c_name.')';
//            $sql = "
//            SELECT
//             -- GROUP_CONCAT(DISTINCT(ctype_cnid)),
//              cid
//            FROM dp_query_ctype
//            WHERE $where
//            GROUP BY ctype_certnum
//            HAVING COUNT(cid) >= $c_name_num_all
//            ";
//            $cids = Db::query($sql);
            //反向处理cid
//            if (isset($cids) && !empty($cids)){
//                $cid_n_before = count($cids);
//                for ($cid_n=0;$cid_n<$cid_n_before;$cid_n++){
//                    $cid_before = $cids[$cid_n]['cid'];
//                    $sql2 ="
//                        SELECT
//                            ctype_cnid
//                        FROM dp_query_ctype
//                        WHERE cid = $cid_before
//                    ";
//                    $emp = Db::query($sql2);
//                    $emp = array_column($emp,'ctype_cnid');
//                    $flag = 1;//初始符合
//                    foreach ($_GET['c_name'] as $c_name_before) {
//                        if (in_array($c_name_before, $emp)) {
//                            continue;
//                        }else {
//                            $flag = 0;
//                            break;
//                        }
//                    }
//                    if ($flag==0) {//不符合,需剔除
//                        unset($cids[$cid_n]);
//                    }
//                    unset($emp);
//                }
//                $cids = array_values($cids);
//            }
            $cids_arr = AptitudeNameModel::setTree($_GET['c_name']);
            $cids_arr = array_values($cids_arr);
            for ($k = 0;$k < count($cids_arr);$k++){
                if (count($cids_arr[$k])>1){
                    //拼接where
                    $where = 'ctype_cnid = '.$cids_arr[$k][0]['cname_id'];
                    for ($o=1;$o<count($cids_arr[$k]);$o++){
                        $where .= ' or ctype_cnid = '.$cids_arr[$k][$o]['cname_id'];
                    }
                    $sql = "SELECT DISTINCT(cid) FROM dp_query_ctype WHERE $where";
                    $cids_array[$k] = Db::query($sql);
                    $cids_array[$k] = array_column( $cids_array[$k],'cid');
                }else{
                    $where2 = 'ctype_cnid = '.$cids_arr[$k][0]['cname_id'];
                    $sql2 = "SELECT DISTINCT(cid) FROM dp_query_ctype WHERE $where2";
                    $cids_array[$k] = Db::query($sql2);
                    $cids_array[$k] = array_column( $cids_array[$k],'cid');
                }
            }
            if (count($cids_array)>1){
                $cid_data_list = $cids_array[0];
                for ($r=1;$r<count($cids_array);$r++){
                    $cid_data_list = array_intersect($cids_array[$r],$cid_data_list);
                }
            }else{
                $cid_data_list = $cids_array[0];
            }
            $cids = array_values($cid_data_list);
        }
        if (isset($cids)){
        $cids_num = count($cids);
		//dump($cids_num);die();
        for ($i=0;$i<$cids_num;$i++){
            //筛选条件
            $map['query_company.company_id'] = $cids[$i];


            $data_list[$i] = Db::view('query_project','project_num,project_pnum,project_area,project_unit,project_unitnum,project_type,project_nature,project_use,project_allmoney,project_acreage,project_level,project_wnum')
                //公司项目表
                ->view('query_ccp',
                    'cpp_name,cpp_address,cpp_type,cpp_par',
                    'query_project.project_url = query_ccp.ccp_pid',
                    'LEFT'
                )
                //公司表
                ->view('query_company',
                    'company_name,company_num,company_person,company_addr,company_reg',
                    'query_company.company_id = query_ccp.ccp_cid',
                'LEFT'
                )
                //省市区 left以公司为主
                ->view('province',
                    'province_name',
                    'query_company.company_place = province.province_id',
                    'LEFT'
                )
                //招投标表
                ->view('query_pbided',
                    'pbided_type,pbided_way,pbided_unitname,pbided_date,pbided_money,pbided_booknum,pbided_bookpnum',
                    'query_project.project_url = query_pbided.pbided_pid',
                    'LEFT'
                )
                //竣工验证备案表
                ->view('query_pfinish',
                    'pfinish_num,pfinish_pnum,pfinish_realmoney,pfinish_realarea,pfinish_realbegin,pfinish_realfinish,jsondata',
                    'query_project.project_url = query_pfinish.pfinish_pid',
                    'LEFT')

                ->where($map)
                ->group('query_project.project_num')
                ->select();
        }
        if (isset($data_list) && !empty($data_list)){//处理数据，让其每个数据合并到一起
            $data_list_num = count($data_list);
                $data_list_final=[];
                for ($j=0;$j<$data_list_num;$j++){
                    $data_list_final = array_merge($data_list[$j],$data_list_final);
                }
        }else{
            $data_list_final = null;
        }
        if($data_list_final == null ){
            return  $data_list_final =null;
        }
        //处理监理、施工、设计单位以及名称
            $count_data_lists = count($data_list_final);
            for ($json_data_num=0;$json_data_num<$count_data_lists;$json_data_num++){
                if ($data_list_final[$json_data_num]['jsondata']==''|| $data_list_final[$json_data_num]['jsondata']=='{}'){//为空，则没有
                    $data_list_final[$json_data_num]['pfinish_design'] = '-';
                    $data_list_final[$json_data_num]['pfinish_supervision'] = '-';
                    $data_list_final[$json_data_num]['pfinish_construct'] = '-';
                }else{//不为空
                    $decode_data = json_decode($data_list_final[$json_data_num]['jsondata']);
                    $data_list_final[$json_data_num]['pfinish_design'] = $decode_data->company_name[0];
                    $data_list_final[$json_data_num]['pfinish_supervision'] = $decode_data->company_name[1];
                    $data_list_final[$json_data_num]['pfinish_construct'] = $decode_data->company_name[2];
                    unset($decode_data);//清除解析后的json数据，不形象下一个循环的数据结构
                }
            }

       return $data_list_final;
        }else{
            $data_list = null;
            return $data_list;
        }

    }
    public static function getListAll($getInfo){
//中标金额
        if (isset($getInfo['money']) && $getInfo['money']>0){
            $get_money = $getInfo['money'];
            $map['query_pbided.pbided_money'] = ['>',$get_money];
        }//最早开工日期
        if (isset($getInfo['starttime']) && !empty($getInfo['starttime'])){
            $get_starttime = strtotime($getInfo['starttime']);
            $map['query_pfinish.pfinish_realbegin'] = ['>',$get_starttime];
        }//最迟竣工日期
        if (isset($getInfo['endtime']) && !empty($getInfo['endtime'])){
            $get_endtime = strtotime($getInfo['endtime']);
            $map['query_pfinish.pfinish_realfinish'] = ['<',$get_endtime];
        }//项目分类
        if (isset($getInfo['pro_cate']) && $getInfo['pro_cate']!='0'){
            $get_pro_cate = $getInfo['pro_cate'];
            $map['query_project.project_type'] = $get_pro_cate;
        }//建设性质
        if (isset($getInfo['pro_prop']) && $getInfo['pro_prop']!='0'){
            $get_pro_prop = $getInfo['pro_prop'];
            $map['query_project.project_nature'] = $get_pro_prop;
        }//工程用途
        if (isset($getInfo['pro_use']) && $getInfo['pro_use']!='0'){
            $get_pro_use = $getInfo['pro_use'];
            $map['query_project.project_use'] = $get_pro_use;
        }//招标类别
        if (isset($getInfo['pb_type']) && $getInfo['pb_type']!='0'){
            $get_pb_type = $getInfo['pb_type'];
            $map['query_pbided.pbided_type'] = $get_pb_type;
        }


        if (isset($getInfo['c_type']) && isset($getInfo['c_name'])){
            $cids_arr = AptitudeNameModel::setTree($getInfo['c_name']);
            $cids_arr = array_values($cids_arr);
            for ($k = 0;$k < count($cids_arr);$k++){
                if (count($cids_arr[$k])>1){
                    //拼接where
                    $where = 'ctype_cnid = '.$cids_arr[$k][0]['cname_id'];
                    for ($o=1;$o<count($cids_arr[$k]);$o++){
                        $where .= ' or ctype_cnid = '.$cids_arr[$k][$o]['cname_id'];
                    }
                    $sql = "SELECT DISTINCT(cid) FROM dp_query_ctype WHERE $where";
                    $cids_array[$k] = Db::query($sql);
                    $cids_array[$k] = array_column( $cids_array[$k],'cid');
                }else{
                    $where2 = 'ctype_cnid = '.$cids_arr[$k][0]['cname_id'];
                    $sql2 = "SELECT DISTINCT(cid) FROM dp_query_ctype WHERE $where2";
                    $cids_array[$k] = Db::query($sql2);
                    $cids_array[$k] = array_column( $cids_array[$k],'cid');
                }
            }
            if (count($cids_array)>1){
                $cid_data_list = $cids_array[0];
                for ($r=1;$r<count($cids_array);$r++){
                    $cid_data_list = array_intersect($cids_array[$r],$cid_data_list);
                }
            }else{
                $cid_data_list = $cids_array[0];
            }
            $cids = array_values($cid_data_list);
        }
        if (isset($cids)){
            $cids_num = count($cids);

            for ($i=0;$i<$cids_num;$i++){
                //筛选条件
                $map['query_company.company_id'] = $cids[$i];


                //资质名称为主表
                $data_list[$i] = Db::view('query_project','project_num,project_pnum,project_area,project_unit,project_unitnum,project_type,project_nature,project_use,project_allmoney,project_acreage,project_level,project_wnum')
                    //公司项目表
                    ->view('query_ccp',
                        'cpp_name,cpp_address,cpp_type,cpp_par',
                        'query_project.project_url = query_ccp.ccp_pid',
                        'LEFT'
                    )
                    //公司表
                    ->view('query_company',
                        'company_name,company_num,company_person,company_addr,company_reg',
                        'query_company.company_id = query_ccp.ccp_cid',
                        'LEFT'
                    )
                    //省市区 left以公司为主
                    ->view('province',
                        'province_name',
                        'query_company.company_place = province.province_id',
                        'LEFT'
                    )
                    //招投标表
                    ->view('query_pbided',
                        'pbided_type,pbided_way,pbided_unitname,pbided_date,pbided_money,pbided_booknum,pbided_bookpnum',
                        'query_project.project_url = query_pbided.pbided_pid',
                        'LEFT'
                    )
                    //竣工验证备案表
                    ->view('query_pfinish',
                        'pfinish_num,pfinish_pnum,pfinish_realmoney,pfinish_realarea,pfinish_realbegin,pfinish_realfinish,jsondata',
                        'query_project.project_url = query_pfinish.pfinish_pid',
                        'LEFT')

                    ->where($map)
                    ->group('query_project.project_num')
                    ->select();
               // dump(Db::table('query_project')->getLastSql());

            }
            if (isset($data_list) && !empty($data_list)){
                $data_list_num = count($data_list);
                $data_list_final=[];
                for ($j=0;$j<$data_list_num;$j++){
                    $data_list_final = array_merge($data_list[$j],$data_list_final);
                }
            }else{
                $data_list_final = null;
            }
            if($data_list_final == null ){
                return  $data_list_final =null;
            }
            //处理监理、施工、设计单位以及名称
            $count_data_lists = count($data_list_final);
            for ($json_data_num=0;$json_data_num<$count_data_lists;$json_data_num++){
                if ($data_list_final[$json_data_num]['jsondata']=='' || $data_list_final[$json_data_num]['jsondata']=='{}'){//为空，则没有
                    $data_list_final[$json_data_num]['pfinish_design'] = '-';
                    $data_list_final[$json_data_num]['pfinish_supervision'] = '-';
                    $data_list_final[$json_data_num]['pfinish_construct'] = '-';
                }else{//不为空
                    $decode_data = json_decode($data_list_final[$json_data_num]['jsondata']);
                    $data_list_final[$json_data_num]['pfinish_design'] = $decode_data->company_name[0];
                    $data_list_final[$json_data_num]['pfinish_supervision'] = $decode_data->company_name[1];
                    $data_list_final[$json_data_num]['pfinish_construct'] = $decode_data->company_name[2];
                    unset($decode_data);//清除解析后的json数据，不形象下一个循环的数据结构
                }
            }

            //时间戳转格式
            foreach ($data_list_final as $key=>$value){
                foreach ($value as $k=>$v){
                    if ($k == 'pbided_date'){//中标日期
                        $data_list_final[$key][$k] = date('Y-m-d',$v);
                    }
                    if ($k =='pfinish_realbegin'){//实际开工日期
                        $data_list_final[$key][$k] = date('Y-m-d',$v);
                    }
                    if ($k =='pfinish_realfinish'){//实际竣工日期
                        $data_list_final[$key][$k] = date('Y-m-d',$v);
                    }
                }
            }
            return $data_list_final;
        }else{
            $data_list = null;
            return $data_list;
        }
    }
    //查询项目分类
    public static function get_pro_cate(){
        $pro_cate = Db::table('dp_query_project')->where(['project_type'=>['neq','']])->distinct('project_type')->column('project_type');
        return $pro_cate;
    }
    //查询建设性质
    public static function get_pro_prop(){
        $pro_prop = Db::table('dp_query_project')->where(['project_nature'=>['neq','']])->distinct('project_nature')->column('project_nature');
        return $pro_prop;
    }
    //查询工程用途
    public static function get_pro_use(){
        $pro_use = Db::table('dp_query_project')->where(['project_use'=>['neq','']])->distinct('project_use')->column('project_use');
        return $pro_use;
    }
    //获取招标类型
    public static function get_pb_type(){
        $pb_type = Db::table('dp_query_pbided')->where(['pbided_type'=>['neq','']])->distinct('pbided_type')->column('pbided_type');
        return $pb_type;
    }
    //获取资质类别
    public static function get_c_type(){
        $c_type = Db::table('dp_query_ctype')
            ->where(['ctype_name'=>['neq','']])
            ->distinct('ctype_name')
            ->column('ctype_name');
        return $c_type;
    }
    //获取资质名称
    public static function get_c_name(){
        $c_name = Db::table('dp_query_cname')
            ->where(['cname_name'=>['neq','']])
            ->distinct('cname_id')
            ->field('cname_id,cname_name')
            ->select();
        return $c_name;
    }
    // ajax通过类别动态查名称
    public static function ajax_get($input){
        $input = implode(',',$input['param']);
        $where = [
            'ctype_name'=>['IN',($input)]
        ];
        $cnid = Db::table('dp_query_ctype')->where($where)->distinct('ctype_cnid')->column('ctype_cnid');
        $cnid = implode(',',$cnid);
        $cname_info = Db::table('dp_query_cname')->where(['cname_id'=>['IN',($cnid)]])->field('cname_id,cname_name')->select();
        return $cname_info;
    }
}