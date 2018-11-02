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

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\cms\model\Page as PageModel;
use app\cms\model\Document as DocumentModel;
ini_set('memory_limit', '-1');
/**
 * 单页控制器
 * @package app\cms\admin
 */
class Page extends Admin
{
    public function index()
    {
        $get_sign_cate = empty($_GET['sign_cate'])?'':$_GET['sign_cate'];
        $get_sign_cate = json_encode($get_sign_cate);
        $get_sign_prof = empty($_GET['sign_prof'])?'':$_GET['sign_prof'];
        $get_sign_prof = json_encode($get_sign_prof);

        $sign_cate = json_encode(PageModel::get_sign_cate());//注册类别
        $sign_prof = json_encode(PageModel::get_sign_prof());//注册专业
        $html = <<<EOF
        <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
        <div>
            <form method="GET" action="" role="form">
             <div>
                <div class="from-group col-sm-6">
                <label>注册类别</label>
                <select multiple id="sign_cate" class="selectpicker form-control" name="sign_cate[]" data-live-search="true"  title="请选择注册类别" data-size="12">
                </select>
                </div>
                <div class="from-group col-sm-6" style="display:none;"> 
                 <label>注册专业</label>                 
                 <select name="sign_prof[]" id="sign_prof" class="selectpicker form-control" multiple data-live-search="true"  title="请选择注册专业" data-size="12">
                  </select>
                </div>
            </div>
           
            <div>
                <button type="submit" class="btn btn-danger btn-block">提交</button>
            </div>
            </form>
        </div>
        <script>
        //运用bootstrap-select 
       $(document).ready(function(){
          $('.selectpicker').selectpicker({
            'selectedText':'cat',
           
          });//初始化
          
        });
        //注册类别
            var sign_cate = $sign_cate;
            var sign_cate_option ='';
            for (x in sign_cate)
                {
                    sign_cate_option += "<option value="+sign_cate[x]+">"+sign_cate[x]+"</option>";
                }
                $('#sign_cate').html(sign_cate_option);
        //注册专业
            var sign_prof = $sign_prof;
            var sign_prof_option ='';
            for (x in sign_prof)
                {
                    sign_prof_option += "<option value="+sign_prof[x]+">"+sign_prof[x]+"</option>";
                }
                $('#sign_prof').html(sign_prof_option);
      
            
        //显示已选择
        //注册类别
        var get_sign_cate = $get_sign_cate;
         if (get_sign_cate != ''){
           for(x in get_sign_cate){
            $("#sign_cate option[value='"+get_sign_cate[x]+"']").attr("selected","selected");
            }
        }
        //注册专业
        var get_sign_prof = $get_sign_prof;
         if (get_sign_prof != ''){
           for(x in get_sign_prof){
            $("#sign_prof option[value='"+get_sign_prof[x]+"']").attr("selected","selected");
            }
        }
  
        </script>
EOF;
        // 数据列表
        $data_list = PageModel::getList();
        cookie('getInfo_sign',null);//清空cookie
        cookie('getInfo_sign',$_GET);
        // 使用ZBuilder快速创建数据表格
        return $dataArea = ZBuilder::make('table')
            ->hideCheckbox()//隐藏表格的第一行的复选框
            ->setExtraHtml($html, 'toolbar_top')
            ->addColumns([ // 批量添加数据列
                //被筛选的放在前面
//                ['project_type', '项目分类'],
//                ['project_nature', '建设性质'],
//                ['project_use', '工程用途'],
//                ['pbided_type', '招标类型'],
//                ['pbided_way', '招标方式'],
//                ['ctype_name', '资质类别'],
//                ['cname_name', '资质名称'],
//                ['preg_type', '注册类别'],
//                ['prrg_pro','注册专业'],
                /*公司表start*/
                ['company_name', '公司名称'],
//                ['company_num', '统一社会信用代码'],
//                ['company_person', '企业法定代表人'],
//                ['province_name', '企业注册属地'],
//                ['company_addr', '企业经营地址'],
//                ['company_reg', '企业登记注册类型'],
//                ['name','四川分类'],
//                ['is_sc','是否入川'],
//                ['rank_ranking','企业排行'],
                /*公司表end*/
                /*资质资格表start*/
//
//                ['ctype_certnum', '资质证书号'],
//                ['ctype_certdate', '发证日期'],
//                ['ctype_expiry', '证书有效期'],
//                ['ctype_cert', '发证机关'],
                /*资质资格表end*/
                /*资质名称表start*/

                /*资质名称表end*/
                /*项目表start*/
//                ['ccp_name', '项目名称'],
//                ['project_num', '项目编号'],
//                ['project_pnum', '省级项目编号'],
//                ['project_area', '所在区划'],
//                ['project_unit', '建设单位'],
//                ['project_unitnum', '建设单位组织机构代码（统一社会信用代码）'],
//
//                ['project_allmoney', '总投资'],
//                ['project_acreage', '总面积'],
//                ['project_level', '立项级别'],
//                ['project_wnum', '立项文号'],
                /*项目表end*/
                /*招投标表start*/
//
//                ['pbided_unitname', '中标单位名称'],
//                ['pbided_date', '中标日期','date'],
//                ['pbided_money', '中标金额（万元）'],
//                ['pbided_booknum', '中标通知书编号'],
//                ['pbided_bookpnum', '省级中标通知书编号'],
                /*招投标表end*/
                /*施工图审查start*/
//                ['parchit_booknums', '施工图审查合格书编号'],
//                ['parchit_bookpnums', '省级施工图审查合格书编号'],
//                ['parchit_kname', '勘察单位名称'],
//                ['parchit_sname', '设计单位名称'],
//                ['parchit_cname', '施工图审查机构名称'],
//                ['parchit_fdate', '审查完成日期'],
                /*施工图审查end*/
                /*合同备案表start*/
//                ['pcontract_type', '合同类别'],
//                ['pcontract_num', '合同备案编号'],
//                ['pcontract_pnum', '省级合同备案编号'],
//                ['pcontract_money', '合同金额（万元）'],
//                ['pcontract_time', '合同签订日期'],
                /*合同备案表end*/
                /*施工许可表start*/
//                ['pstruct_num', '施工许可证编号'],
//                ['pstruct_pnum', '省级施工许可证编号'],
//                ['pstruct_money', '合同金额（万元）'],
//                ['pstruct_areaunit', '面积（平方米）'],
//                ['pstruct_certdate', '发证日期'],
                /*施工许可表end*/
                /*竣工验证备案表start*/
//                ['pfinish_num', '竣工备案编号'],
//                ['pfinish_pnum', '省级竣工备案编号'],
//                ['pfinish_realmoney', '实际造价（万元）'],
//                ['pfinish_realarea', '实际面积（平方米）'],
//                ['pfinish_realbegin', '实际开工日期'],
//                ['pfinish_realfinish', '实际竣工验收日期'],
                /*竣工验证备案表end*/
                /*人员表start*/
                ['people_name', '姓名'],
                ['people_sex', '性别'],
                ['people_ttype', '证件类型'],
                ['people_num', '证件号码'],
                /*人员表end*/
                /*执业注册信息start*/
//
//                ['preg_certnum', '证书编号'],
//                ['preg_ynum', '执业印章号'],
//                ['preg_date', '有效期','date'],
//                ['preg_unit', '注册单位'],
                /*执业注册信息end*/
                /*个人业绩表start*/
                ['pwork_num', '项目编码'],
                ['pwork_name', '项目名称'],
//                ['ccp_address', '项目属地'],
//                ['ccp_type', '项目类别'],
//                ['ccp_par', '建设单位'],
                /*个人业绩表end*/
                /*诚信表start*/
                ['honest_num', '诚信记录编号'],
                ['honest_name', '诚信记录主体'],
                ['honest_content', '决定内容'],
                ['honest_dept', '实施部门（文号）'],
                ['honest_date', '发布有效期','date'],
                /*诚信表end*/
                /*四川公司表start*/
//                ['sccompany_type', '企业类型'],
//                ['sccompany_dtype', '类型分类'],
//                ['sccompany_reg', '注册资本'],
                /*四川公司表end*/
                /*成都排名表start*/
//                ['rank_ranking', '成都网站60日排名'],
                /*成都排名表end*/
            ])
            ->addTopButton('export', [
                'title' => '导出Excel',
                'icon' => 'fa fa-sign-out',
                'class' => 'btn btn-success',
                'href' => url('export')//传送get的数据过去
            ])

            ->setRowList($data_list)// 设置表格数据
            ->fetch(); // 渲染模板
    }
    public function export()
    {
        $getInfo = cookie('getInfo_sign');
        // 数据列表
        $data_list = PageModel::getListAll($getInfo);
//        dump($data_list);die();
        $data = $data_list; //通过map筛选的数据筛选到需要的数据
        // 设置表头信息（对应字段名,宽度，显示表头名称）
        $cellName = [
            /*公司表start*/
            ['company_name','auto', '公司名称'],
//            ['company_num','auto', '统一社会信用代码'],
//            ['company_person','auto', '企业法定代表人'],
//            ['province_name', 'auto','企业注册属地'],
//            ['company_addr','auto', '企业经营地址'],
//            ['company_reg', 'auto','企业登记注册类型'],
//            ['name','auto','四川分类'],
//            ['is_sc','auto','是否入川'],
//            ['rank_ranking','auto','企业排行'],
            /*公司表end*/
            /*资质资格表start*/
//            ['ctype_name', 'auto','资质类别'],
//            ['ctype_certnum', 'auto','资质证书号'],
//            ['ctype_certdate', 'auto','发证日期'],
//            ['ctype_expiry', 'auto','证书有效期'],
//            ['ctype_cert', 'auto','发证机关'],
            /*资质资格表end*/
            /*资质名称表start*/
//            ['cname_name', 'auto','资质名称'],
            /*资质名称表end*/
            /*项目表start*/
//            ['ccp_name', 'auto','项目名称'],
//            ['project_num', 'auto','项目编号'],
//            ['project_pnum', 'auto','省级项目编号'],
//            ['project_area', 'auto','所在区划'],
//            ['project_unit', 'auto','建设单位'],
//            ['project_unitnum', 'auto','建设单位组织机构代码（统一社会信用代码）'],
//            ['project_type', 'auto','项目分类'],
//            ['project_nature', 'auto','建设性质'],
//            ['project_use', 'auto','工程用途'],
//            ['project_allmoney', 'auto','总投资'],
//            ['project_acreage', 'auto','总面积'],
//            ['project_level', 'auto','立项级别'],
//            ['project_wnum', 'auto','立项文号'],
            /*项目表end*/
            /*招投标表start*/
//            ['pbided_type', 'auto','招标类型'],
//            ['pbided_way', 'auto','招标方式'],
//            ['pbided_unitname', 'auto','中标单位名称'],
//            ['pbided_date', 'auto','中标日期'],
//            ['pbided_money', 'auto','中标金额（万元）'],
//            ['pbided_booknum', 'auto','中标通知书编号'],
//            ['pbided_bookpnum', 'auto','省级中标通知书编号'],
            /*招投标表end*/
            /*施工图审查start*/
//            ['parchit_booknums','auto', '施工图审查合格书编号'],
//            ['parchit_bookpnums', 'auto','省级施工图审查合格书编号'],
//            ['parchit_kname','auto', '勘察单位名称'],
//            ['parchit_sname', 'auto','设计单位名称'],
//            ['parchit_cname', 'auto','施工图审查机构名称'],
//            ['parchit_fdate', 'auto','审查完成日期'],
            /*施工图审查end*/
            /*合同备案表start*/
//            ['pcontract_type','auto', '合同类别'],
//            ['pcontract_num', 'auto','合同备案编号'],
//            ['pcontract_pnum', 'auto','省级合同备案编号'],
//            ['pcontract_money', 'auto','合同金额（万元）'],
//            ['pcontract_time', 'auto','合同签订日期'],
            /*合同备案表end*/
            /*施工许可表start*/
//            ['pstruct_num','auto', '施工许可证编号'],
//            ['pstruct_pnum', 'auto','省级施工许可证编号'],
//            ['pstruct_money', 'auto','合同金额（万元）'],
//            ['pstruct_areaunit','auto', '面积（平方米）'],
//            ['pstruct_certdate', 'auto','发证日期'],
            /*施工许可表end*/
            /*竣工验证备案表start*/
//            ['pfinish_num','auto', '竣工备案编号'],
//            ['pfinish_pnum', 'auto','省级竣工备案编号'],
//            ['pfinish_realmoney','auto', '实际造价（万元）'],
//            ['pfinish_realarea', 'auto','实际面积（平方米）'],
//            ['pfinish_realbegin', 'auto','实际开工日期'],
//            ['pfinish_realfinish', 'auto','实际竣工验收日期'],
            /*竣工验证备案表end*/
            /*人员表start*/
            ['people_name', 'auto','姓名'],
            ['people_sex', 'auto','性别'],
            ['people_ttype', 'auto','证件类型'],
            ['people_num', 'auto','证件号码'],
            /*人员表end*/
            /*执业注册信息start*/
//            ['preg_type','auto', '注册类别'],
//            ['prrg_pro','auto','注册专业'],
//            ['preg_certnum', 'auto','证书编号'],
//            ['preg_ynum', 'auto','执业印章号'],
//            ['preg_date', 'auto','有效期'],
//            ['preg_unit', 'auto','注册单位'],
            /*执业注册信息end*/
            /*个人业绩表start*/
            ['pwork_num', 'auto','项目编码'],
            ['pwork_name','auto', '项目名称'],
//            ['ccp_address', 'auto','项目属地'],
//            ['ccp_type', 'auto','项目类别'],
//            ['ccp_par', 'auto','建设单位'],
            /*个人业绩表end*/
            /*诚信表start*/
            ['honest_num', 'auto','诚信记录编号'],
            ['honest_name', 'auto','诚信记录主体'],
            ['honest_content', 'auto','决定内容'],
            ['honest_dept', 'auto','实施部门（文号）'],
            ['honest_date', 'auto','发布有效期'],
            /*诚信表end*/
            /*四川公司表start*/
//            ['sccompany_type','auto', '企业类型'],
//            ['sccompany_dtype', 'auto','类型分类'],
//            ['sccompany_reg', 'auto','注册资本'],
            /*四川公司表end*/
            /*成都排名表start*/
//            ['rank_ranking', 'auto','成都网站60日排名'],
            /*成都排名表end*/
        ];
        // 调用插件（传入插件名，[导出文件名、表头信息、具体数据]）
        plugin_action('Excel/Excel/export', ['全国人员', $cellName, $data]);
        cookie('getInfo_sign',null);//清空cookie
    }
}