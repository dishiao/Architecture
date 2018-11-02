<?php
use QL\QueryList;
use think\Db;
require_once 'autoload.php';
set_time_limit(0);
//连接数据库
$mysqli=new mysqli();//实例化mysqli
$mysqli->connect('47.104.231.109','dolphin','ENWSLCNia2GxJZzF','dolphin');
if(mysqli_connect_error()){
    exit('数据库连接错误,错误信息是.'.mysqli_connect_error());
}
$mysqli->set_charset("UTF8");//设置数据库编码
//end
////删除旧数据
//$sql_del = "delete from dp_query_rank";
//$mysqli->query($sql_del);
//end
$url = 'http://pt.cdcc.gov.cn:8024/Service/CreditRankWebSvr.assx/SearchComprehensiveCreditRankList';
$rules = array(
    'cname' => array('#mainContent_ucEnteBaseInfo_LabEnteName','text'),
    'money' => array('#mainContent_ucEnteBaseInfo_lbl_zczb','text'),
);

$companyType = ['%u65BD%u5DE5','%u76D1%u7406'];
$projectType = ['%u623F%u5EFA','%u5E02%u653F'];
$i = 1;
// 0 0 施工房建 0 1 施工市政 1 0 监理房建 1 1 监理市政
// 需要手动切换 以上四种情况，然后再在里面操作数据库 使用不同的字段来区分
$i=0;
$j=0;
        $param = "companyType=".$companyType[$j]."&projectType=".$projectType[$l]."&keyWord=&EvalDate=".date("Y-m-d",time())."&orderby=PM&oderType=%25u4ECA%25u65E5%25u6392%25u540D&startRange=-1&endRange=-1&startScore=-1&endScore=-1&pageIndex=".$i."&pageSize=10000&token=&opt=0";

        $ql= QueryList::post($url,$param,[
        //  'proxy' => 'http://222.141.11.17:8118',
            //设置超时时间，单位：秒
            'timeout' => 30,
            'headers' => [
                'Referer' => 'https://querylist.cc/',
                'User-Agent' => 'testing/1.0',
                'Accept'     => 'application/json',
                'X-Foo'      => ['Bar', 'Baz'],
                'Cookie'    => 'abc=111;xxx=222'
            ]
        ])->encoding('UTF-8');
        $html = $ql->getHtml();
        $ql->destruct();
        $temp = stripslashes($html);
        $tep = explode('"CompanyName":"', $temp);
        $new = [];
        foreach ($tep as $k => $v) {
            if($k>0){
                //公司名称
                $res = explode('","CompanyGuid":', $v);
                $new[$k]['cname'] = $res['0'];
                //今日分数
                $rr = explode('"TotalScore":', $v);
                $te = explode(',"EvalDate"', $rr['1']);
                $new[$k]['cscore'] =$te['0'];

                //60日分数
                $rr = explode('"AverageScore":', $v);
                $te = explode(',"AverageIndex"', $rr['1']);
                $new[$k]['cmore'] =$te['0'];
            }
        }
dump($new);die();
        //处理新数据
        if ($j==0 && $l==0){
            //施工房建
            $nums = count($new);//计算当前排名的个数
            for($i=1;$i<=$nums;$i++){
                $a = $new[$i]['cname'];
                $sql="select company_url from dp_query_company where company_name='$a'";//通过公司名称查找公司url
                $result=$mysqli->query($sql);//执行sql语句把结果集赋给$result
                $company_url = $result->fetch_array();//将结果集的第一行输出
                $c = $company_url['0'];//公司的url
                $sql2 = "insert into dp_query_rank(rank_url,rank_build_house) values('$c','$i')";
                $mysqli->query($sql2);
            }
        }elseif ($j==0 && $l==1){
            //施工市政
            $nums = count($new);
            for ($i=1;$i<=$nums;$i++){
                $a = $new[$i]['cname'];
                $sql="select company_url from dp_query_company where company_name='$a'";//通过公司名称查找公司url
                $result=$mysqli->query($sql);//执行sql语句把结果集赋给$result
                $company_url = $result->fetch_array();//将结果集的第一行输出
                $c = $company_url['0'];//公司的url
                //查现在排行榜有无此公司
                $sql2 = "select rank_url from dp_query_rank where rank_url='$c'";
                $result2 = $mysqli->query($sql2);
                $rank_url = $result2->fetch_array();
                $rank_url = $rank_url['0'];//公司URL
                if ($rank_url == null){//现在排行榜没有此公司数据
                    $sql3 = "insert into dp_query_rank(rank_url,rank_build_gov) values('$c','$i')";
                    $mysqli->query($sql3);
                }else{//有此公司
                    $sql4 = "UPDATE dp_query_rank SET rank_build_gov = '$i' WHERE rank_url = '$c'";
                    $mysqli->query($sql4);
                }
            }
        }elseif ($j==1 && $l==0){
            //监理房建
            $nums = count($new);
            for ($i=1;$i<=$nums;$i++){
                $a = $new[$i]['cname'];
                $sql="select company_url from dp_query_company where company_name='$a'";//通过公司名称查找公司url
                $result=$mysqli->query($sql);//执行sql语句把结果集赋给$result
                $company_url = $result->fetch_array();//将结果集的第一行输出
                $c = $company_url['0'];//公司的url
                //查现在排行榜有无此公司
                $sql2 = "select rank_url from dp_query_rank where rank_url='$c'";
                $result2 = $mysqli->query($sql2);
                $rank_url = $result2->fetch_array();
                $rank_url = $rank_url['0'];//公司URL
                if ($rank_url == null){//现在排行榜没有此公司数据
                    $sql3 = "insert into dp_query_rank(rank_url,rank_survey_house) values('$c','$i')";
                    $mysqli->query($sql3);
                }else{//有此公司
                    $sql4 = "UPDATE dp_query_rank SET rank_survey_house = '$i' WHERE rank_url = '$c'";
                    $mysqli->query($sql4);
                }
            }
        }elseif ($j==1 && $l==1){
            //监理市政
            $nums = count($new);
            for ($i=1;$i<=$nums;$i++){
                $a = $new[$i]['cname'];
                $sql="select company_url from dp_query_company where company_name='$a'";//通过公司名称查找公司url
                $result=$mysqli->query($sql);//执行sql语句把结果集赋给$result
                $company_url = $result->fetch_array();//将结果集的第一行输出
                $c = $company_url['0'];//公司的url
                //查现在排行榜有无此公司
                $sql2 = "select rank_url from dp_query_rank where rank_url='$c'";
                $result2 = $mysqli->query($sql2);
                $rank_url = $result2->fetch_array();
                $rank_url = $rank_url['0'];//公司URL
                if ($rank_url == null){//现在排行榜没有此公司数据
                    $sql3 = "insert into dp_query_rank(rank_url,rank_survey_gov) values('$c','$i')";
                    $mysqli->query($sql3);
                }else{//有此公司
                    $sql4 = "UPDATE dp_query_rank SET rank_survey_gov = '$i' WHERE rank_url = '$c'";
                    $mysqli->query($sql4);
                }
            }
        }



 $mysqli->close();//别忘了关闭你的"小资源";
?>