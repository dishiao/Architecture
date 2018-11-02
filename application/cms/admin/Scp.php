<?php

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\cms\model\Scp as ScpModel;
use think\Db;
use util\Tree;
ini_set('memory_limit', '-1');
/**
 * 文档控制器
 * @package app\cms\admin
 */
class Scp extends Admin
{
    public function index()
    {
        $get_sign_cate = empty($_GET['sign_cate'])?'':$_GET['sign_cate'];
        $get_sign_cate = json_encode($get_sign_cate);
        $get_sign_prof = empty($_GET['sign_prof'])?'':$_GET['sign_prof'];
        $get_sign_prof = json_encode($get_sign_prof);
        $get_sign_insc = empty($_GET['sign_insc'])?'':$_GET['sign_insc'];
        $get_sign_insc = json_encode($get_sign_insc);
        $get_is_insc = empty($_GET['is_sc'])?'':$_GET['is_sc'];
        $get_is_insc = json_encode($get_is_insc);

        $sign_cate = json_encode(ScpModel::get_sign_cate());//人员分类
        $sign_prof = json_encode(ScpModel::get_sign_prof());//证书类型
        $html = <<<EOF
        <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
        <div>
            <form method="GET" action="" role="form">
             <div>
                <div class="from-group col-sm-6" style="display: none;">
                <label>人员分类</label>
                <select  id="sign_cate" class="selectpicker form-control" name="sign_cate[]" data-live-search="true"  title="请选择人员分类" data-size="12">
                </select>
                </div>
                
                <div class="from-group col-sm-6"> 
                 <label>证书类型</label>                 
                 <select name="sign_prof[]" id="sign_prof" class="selectpicker form-control" multiple data-live-search="true"  title="请选择证书类别" data-size="12">
                  </select>
                </div>
                
                <div class="from-group col-sm-6" style="display: none;">
                <label>是否入川</label>
                <select id="is_sc" class="form-control" name="is_sc">
                        <option value="0">川内</option>
                        <option value="1">入川</option>
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
                    sign_cate_option += "<option value="+sign_cate[x]['uid']+">"+sign_cate[x]['name']+"</option>";
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
         //是否入川
        var get_is_insc = $get_is_insc;
         if (get_is_insc != ''){
           for(x in get_is_insc){
            $("#is_sc option[value='"+get_is_insc+"']").attr("selected","selected");
            }
        }
  
        </script>
EOF;
        // 数据列表
        $data_list = ScpModel::getList();
        cookie('getInfo_sign',null);//清空cookie
        cookie('getInfo_sign',$_GET);
        // 使用ZBuilder快速创建数据表格
        return $dataArea = ZBuilder::make('table')
            ->hideCheckbox()//隐藏表格的第一行的复选框
            ->setExtraHtml($html, 'toolbar_top')
            ->addColumns([ // 批量添加数据列
                //四川人员
                ['nickname','姓名'],
                ['company_name','公司名称'],
//                ['name','人员分类'],
//                ['type','证书类型'],
//                ['is_sc','是否入川'],
                ['sex','性别'],
                ['tel','技术职称'],
                ['xueli','最高学历'],
                ['over','毕业学校'],
                ['tech','所学专业'],
                ['msg','备注'],
                //业绩
                ['pwork_num', '项目编码'],
                ['pwork_name', '项目名称'],
                //四川人员证书

//                ['pro','专业'],
//                ['level','等级'],
//                ['pronum','证书号'],
//                ['number','证书编号'],
//                ['zhigenum','职业资格证书号'],
//                ['starttime','发证时间'],
//                ['endtime','有效期'],
//                ['icardname','证书登记姓名'],
//                ['exchange','变更记录'],
                //执业注册
//                ['preg_type', '注册类别'],
//                ['prrg_pro','注册专业'],
//                ['preg_certnum', '证书编号'],
//                ['preg_ynum', '执业印章号'],
//                ['preg_date', '有效期'],
//                ['preg_unit', '注册单位'],
                //诚信
                ['honest_num', '诚信记录编号'],
                ['honest_name', '诚信记录主体'],
                ['honest_content', '决定内容'],
                ['honest_dept', '实施部门（文号）'],
                ['honest_date', '发布有效期'],
            ])
            ->addTopButton('export', [
                'title' => '导出Excel',
                'icon' => 'fa fa-sign-out',
                'class' => 'btn btn-success',
                'href' => url('export', http_build_query($this->request->param()))//传送get的数据过去
            ])
            ->setRowList($data_list)// 设置表格数据
            ->fetch(); // 渲染模板
    }

    /**
     * 导出EXCEL
     * author@dishiao
     */
    public function export()
    {
        $getInfo = cookie('getInfo_sign');
        // 数据列表
        $data_list = ScpModel::getListAll($getInfo);
        $data = $data_list; //通过map筛选的数据筛选到需要的数据
        // 设置表头信息（对应字段名,宽度，显示表头名称）
        $cellName = [
            //四川人员
            ['nickname','auto','姓名'],
            ['company_name','auto','公司名称'],
//            ['name','auto','人员分类'],
//            ['type','auto','证书类型'],
//            ['is_sc','auto','是否入川'],
            ['sex','auto','性别'],
            ['tel','auto','技术职称'],
            ['xueli','auto','最高学历'],
            ['over','auto','毕业学校'],
            ['tech','auto','所学专业'],
            ['msg','auto','备注'],
            //业绩
            ['pwork_num','auto', '项目编码'],
            ['pwork_name', 'auto','项目名称'],
            //四川人员证书

            ['pro','auto','专业'],
            ['level','auto','等级'],
            ['pronum','auto','证书号'],
            ['number','auto','证书编号'],
            ['zhigenum','auto','职业资格证书号'],
            ['starttime','auto','发证时间'],
            ['endtime','auto','有效期'],
            ['icardname','auto','证书登记姓名'],
            ['exchange','auto','变更记录'],
            //执业注册
            ['preg_type','auto', '注册类别'],
            ['prrg_pro','auto','注册专业'],
            ['preg_certnum','auto', '证书编号'],
            ['preg_ynum', 'auto','执业印章号'],
            ['preg_date', 'auto','有效期'],
            ['preg_unit', 'auto','注册单位'],
            //诚信
            ['honest_num', 'auto','诚信记录编号'],
            ['honest_name', 'auto','诚信记录主体'],
            ['honest_content', 'auto','决定内容'],
            ['honest_dept', 'auto','实施部门（文号）'],
            ['honest_date', 'auto','发布有效期'],

        ];
        // 调用插件（传入插件名，[导出文件名、表头信息、具体数据]）
        plugin_action('Excel/Excel/export', ['全国和四川人员', $cellName, $data]);
        cookie('getInfo_sign',null);//清空cookie
    }

}