<?php

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\cms\model\Sc as ScModel;
use app\cms\model\Document as DocumentModel;
use think\Db;
use util\Tree;
ini_set('memory_limit', '-1');
/**
 * 文档控制器
 * @package app\cms\admin
 */
class Sc extends Admin
{
    public function index()
    {
        $get_money = empty($_GET['money'])?'':$_GET['money'];
        $_get_starttime = empty($_GET['starttime'])?'':$_GET['starttime'];
        $get_endtime = empty($_GET['endtime'])?'':$_GET['endtime'];
        $get_cate = empty($_GET['pro_cate'])?'':$_GET['pro_cate'];
        $get_cate = json_encode($get_cate);
        $get_prop = empty($_GET['pro_prop'])?'':$_GET['pro_prop'];
        $get_prop = json_encode($get_prop);
        $get_use = empty($_GET['pro_use'])?'':$_GET['pro_use'];
        $get_use = json_encode($get_use);
        $get_type = empty($_GET['pb_type'])?'':$_GET['pb_type'];
        $get_type = json_encode($get_type);
        $get_ctype = empty($_GET['c_type'])?'':$_GET['c_type'];
        $get_ctype = json_encode($get_ctype);
        $get_cname = empty($_GET['c_name'])?'':$_GET['c_name'];
        $get_cname = json_encode($get_cname);
        $get_sc_cate = empty($_GET['sc_cate'])?'':$_GET['sc_cate'];
        $get_sc_cate = json_encode($get_sc_cate);
        $get_is_insc = empty($_GET['is_sc'])?'':$_GET['is_sc'];
        $get_is_insc = json_encode($get_is_insc);

        $pro_cate = json_encode(DocumentModel::get_pro_cate());//项目分类
        $pro_prop = json_encode(DocumentModel::get_pro_prop());//建设性质
        $pro_use = json_encode(DocumentModel::get_pro_use());//工程用途
        $pb_type = json_encode(DocumentModel::get_pb_type());//招标类型
        $c_type = json_encode(DocumentModel::get_c_type());//资质类别
        $c_name = json_encode(DocumentModel::get_c_name());//资质名称
        $sc_cate = json_encode(ScModel::get_sc_cate());//四川分类
        cookie('__forward__', $_SERVER['REQUEST_URI']);


        $html = <<<EOF
         <!--<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">-->
        <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet">-->
        
        <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
        <!--<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>-->
       
        <div>
            <form method="GET" action="" role="form">
             <div >
                <div class="from-group col-sm-6">
                <label>资质类别</label>
                <select multiple id="c_type" class="form-control" name="c_type" onchange="ajax_c_name()">
                </select>
                </div>
                
                <!--<div class="from-group col-sm-6">-->
                <!--<label>资质名称</label>-->
                <!--<select multiple id="c_name" class="form-control" name="c_name[]">-->
                <!--</select>-->
                <!--</div>-->
                <div class="from-group col-sm-6"> 
                 <label>资质名称</label>                 
                 <select name="c_name[]" id="c_name" class="selectpicker form-control" multiple data-live-search="true"  title="请选择" data-size="12">
                  </select>
                </div>
            </div>
            <div>
                <div class="form-group col-sm-6">
                <label>中标金额(万元)</label><input  class="form-control" type="text" name="money" value="$get_money"/>
                </div>
                <div class="form-group col-sm-6">
                <label>最早开工日期</label><input class="form-control" type="date" name="starttime" value="$_get_starttime">
                </div>
                <div class="form-group col-sm-6">
                <label>最迟竣工日期</label><input class="form-control" type="date" name="endtime" value="$get_endtime">
                </div>
            </div>
            <div>
                <div class="form-group col-sm-6">
                <label>项目分类</label> 
                <select id="pro_cate" class="form-control" name="pro_cate">
                       
                </select>
                </div>
                <div class="form-group col-sm-6">
                <label>建设性质</label>
                <select id="pro_prop" class="form-control" name="pro_prop">
                  
                </select>
                </div>
                <div class="from-group col-sm-6">
                <label>工程用途</label>
                <select id="pro_use" class="form-control" name="pro_use">
                    
                </select>
                </div>
                
                <div class="from-group col-sm-6">
                <label>招标类型</label>
                <select id="pb_type" class="form-control" name="pb_type">
                    
                </select>
                </div>
                
                <div class="from-group col-sm-6">
                <label>四川分类</label>
                <select id="sc_cate" class="form-control" name="sc_cate">
                    
                </select>
                </div>
                
                <div class="from-group col-sm-6">
                <label>是否入川</label>
                <select id="is_sc" class="form-control" name="is_sc">
                        <option value="0">川内</option>
                        <option value="1">入川</option>
                </select>
                </div>
            </div>
           
            <div>
                <button type="submit" class="btn btn-danger btn-block">提交</button>
                <!--<input type="reset" class="btn btn-default" />-->
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
        //项目分类
            var pro_cate = $pro_cate;
            var pro_cate_option = "<option value="+0+">"+'无'+"</option>";
            for (x in pro_cate)
              {
                pro_cate_option += "<option value="+pro_cate[x]+">"+pro_cate[x]+"</option>";
              }
              $('#pro_cate').html(pro_cate_option);
        //建设性质
            var pro_prop = $pro_prop;
            var pro_prop_option = "<option value="+0+">"+'无'+"</option>";;
            for (x in pro_prop)
              {
                pro_prop_option += "<option value="+pro_prop[x]+">"+pro_prop[x]+"</option>";
              }
              $('#pro_prop').html(pro_prop_option);
        //工程用途
            var pro_use = $pro_use;
            var pro_use_option = "<option value="+0+">"+'无'+"</option>";;
            for (x in pro_use)
                {
                    pro_use_option +=  "<option value="+pro_use[x]+">"+pro_use[x]+"</option>";
                }
                $('#pro_use').html(pro_use_option);
        //招标类型
            var pb_type = $pb_type;
            var pb_type_option ="<option value="+0+">"+'无'+"</option>";;
            for (x in pb_type)
                {
                    pb_type_option += "<option value="+pb_type[x]+">"+pb_type[x]+"</option>"
                }
                $('#pb_type').html(pb_type_option);
        //资质类别
            var c_type = $c_type;
            var c_type_option ='';
            for (x in c_type)
                {
                    c_type_option += "<option value="+c_type[x]+">"+c_type[x]+"</option>";
                }
                $('#c_type').html(c_type_option);
        //资质名称
            var c_name = $c_name;
            var c_name_option ='';
            for (x in c_name)
                {
                    c_name_option += "<option value="+c_name[x]['cname_id']+">"+c_name[x]['cname_name']+"</option>";
                }
                $('#c_name').html(c_name_option);
        //四川分类
            var sc_cate = $sc_cate
            var sc_cate_option ="<option value="+0+">"+'无'+"</option>";
            for (x in sc_cate)
                {
                    sc_cate_option += "<option value="+sc_cate[x]+">"+sc_cate[x]+"</option>";
                }
                $('#sc_cate').html(sc_cate_option);
        
        //ajax根据类别名称请求资质名称
            function ajax_c_name() {
                var select = document.getElementById("c_type");
                var str = [];
                for(i=0;i<select.length;i++){
                    if(select.options[i].selected){
                        str.push(select[i].value);
                    }
                }
//                console.log(str);
                $.ajax({
                  type: 'POST',
                  url: 'ajax_get',
                  data: {param:str},
                  dataType: 'json',
                  success: function(data) {
                      $('#c_name').empty();
                      var c_name_ajax_option = '';
                      var data = JSON.parse(data);
                      for (x in data.data){
                          c_name_ajax_option += "<option value="+data.data[x]['cname_id']+">"+data.data[x]['cname_name']+"</option>"; 
                      }
                      $('#c_name').html(c_name_ajax_option);
                      //更新内容刷新到相应的位置，ajax请求的数据添加到下拉框的时候必须有render和refresh操作不然不显示
                      $("#c_name").selectpicker('render');
                      $("#c_name").selectpicker('refresh');
                  },
                });
            }
            
        //显示已选择
        //项目分类
        var get_cate =  $get_cate;
        if (get_cate != '0'){
            $("#pro_cate option[value='"+get_cate+"']").attr("selected","selected");
        }
        //建设性质
        var get_prop = $get_prop;
         if (get_prop != '0'){
            $("#pro_prop option[value='"+get_prop+"']").attr("selected","selected");
        }
        //工程用途
        var get_use = $get_use;
         if (get_use != '0'){
            $("#pro_use option[value='"+get_use+"']").attr("selected","selected");
        }
        //招标类型
        var get_type = $get_type;
        if (get_type != '0'){
            $("#pb_type option[value='"+get_type+"']").attr("selected","selected");
        }
        //资质类别
        var get_ctype =$get_ctype;
        if (get_ctype != ''){
            $("#c_type option[value='"+get_ctype+"']").attr("selected","selected");
        }
        //资质名称
        var get_cname = $get_cname;
         if (get_cname != ''){
           for(x in get_cname){
            $("#c_name option[value='"+get_cname[x]+"']").attr("selected","selected");
            }
        }
        //四川分类
        var get_sc_cate = $get_sc_cate;
         if (get_sc_cate != ''){
           for(x in get_sc_cate){
            $("#sc_cate option[value='"+get_sc_cate+"']").attr("selected","selected");
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
        $data_list = ScModel::getList();
        cookie('getInfo',null);//清空cookie
        cookie('getInfo',$_GET);
        // 使用ZBuilder快速创建数据表格
        return $dataArea = ZBuilder::make('table')
            ->hideCheckbox()//隐藏表格的第一行的复选框
            ->setExtraHtml($html, 'toolbar_top')
            ->addColumns([ // 批量添加数据列
                ['cpp_name', '项目名称'],//项目名称被要求放在第一个
                //被筛选的放在前面
                ['project_type', '项目分类'],
                ['project_nature', '建设性质'],
                ['project_use', '工程用途'],
                ['pbided_type', '招标类型'],
                ['pbided_way', '招标方式'],
                ['name','四川分类'],
                ['is_sc','是否入川'],
                ['rank_build_house','施工-房建排名'],
                ['rank_build_gov','施工-市政排行'],
                ['rank_survey_house','监理-房建排名'],
                ['rank_survey_gov','监理-市政排名'],
                /*公司表start*/
                ['company_name', '公司名称'],
                ['company_chid','注册资本'],
                ['add_time','创建时间'],
                ['cnum','证书编号'],
                ['term','有效期'],
                ['grade','等级'],
                ['range','包含范围'],
                ['manager','企业经理'],
                ['leader','技术负责人'],
                ['aptitude','资质项'],
                ['company_num', '统一社会信用代码'],
                ['company_person', '企业法定代表人'],
                // ['province_name', '企业注册属地'],
                // ['company_addr', '企业经营地址'],
                ['company_reg', '企业登记注册类型'],
                /*公司表end*/
                /*项目表start*/
                ['project_area', '所在区划'],
                ['project_unit', '建设单位'],
                ['project_allmoney', '总投资'],
                ['project_acreage', '总面积'],
                /*项目表end*/
                /*招投标表start*/
                ['pbided_unitname', '中标单位名称'],
                ['pbided_date', '中标日期','date'],
                ['pbided_money', '中标金额（万元）'],
                /*招投标表end*/
                /*竣工验证备案表start*/
                ['pfinish_design','设计单位'],
                ['pfinish_supervision','监理单位'],
                ['pfinish_construct','施工单位'],
                ['pfinish_num', '竣工备案编号'],
                ['pfinish_pnum', '省级竣工备案编号'],
                ['pfinish_realmoney', '实际造价（万元）'],
                ['pfinish_realarea', '实际面积（平方米）'],
                ['pfinish_realbegin', '实际开工日期','date'],
                ['pfinish_realfinish', '实际竣工验收日期','date'],
                /*竣工验证备案表end*/
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
        $getInfo = cookie('getInfo');

        // 数据列表
        $data_list = ScModel::getListAll($getInfo);
//        dump($data_list);die();
        $data = $data_list; //通过map筛选的数据筛选到需要的数据
        // 设置表头信息（对应字段名,宽度，显示表头名称）
        $cellName = [
            /*项目表start*/
            ['cpp_name', 'auto','项目名称'],
            ['project_area', 'auto','所在区划'],
            ['project_unit', 'auto','建设单位'],
            ['project_type', 'auto','项目分类'],
            ['project_nature', 'auto','建设性质'],
            ['project_use', 'auto','工程用途'],
            ['project_allmoney', 'auto','总投资'],
            ['project_acreage', 'auto','总面积'],
            /*项目表end*/
            /*公司表start*/
            ['company_name','auto', '公司名称'],
            ['company_chid','auto','注册资本'],
            ['add_time','auto','创建时间'],
            ['cnum','auto','证书编号'],
            ['term','auto','有效期'],
            ['grade','auto','等级'],
            ['range','auto','包含范围'],
            ['manager','auto','企业经理'],
            ['leader','auto','技术负责人'],
            ['aptitude','auto','资质项'],
            ['company_num','auto', '统一社会信用代码'],
            ['company_person','auto', '企业法定代表人'],
            ['company_reg', 'auto','企业登记注册类型'],
            ['name','auto','四川分类'],
            ['is_sc','auto','是否入川'],
            ['rank_build_house','auto','施工-房建排名'],
            ['rank_build_gov','auto','施工-市政排行'],
            ['rank_survey_house','auto','监理-房建排名'],
            ['rank_survey_gov','auto','监理-市政排名'],
            /*公司表end*/
            /*招投标表start*/
//            ['pbided_type', 'auto','招标类型'],
//            ['pbided_way', 'auto','招标方式'],
            ['pbided_unitname', 'auto','中标单位名称'],
            ['pbided_date', 'auto','中标日期'],
            ['pbided_money', 'auto','中标金额（万元）'],
            /*招投标表end*/
            /*竣工验证备案表start*/
            ['pfinish_design','auto','设计单位'],
            ['pfinish_supervision','auto','监理单位'],
            ['pfinish_construct','auto','施工单位'],
            ['pfinish_num','auto', '竣工备案编号'],
            ['pfinish_pnum', 'auto','省级竣工备案编号'],
            ['pfinish_realmoney','auto', '实际造价（万元）'],
            ['pfinish_realarea', 'auto','实际面积（平方米）'],
            ['pfinish_realbegin', 'auto','实际开工日期'],
            ['pfinish_realfinish', 'auto','实际竣工验收日期'],
            /*竣工验证备案表end*/
//
        ];
        // 调用插件（传入插件名，[导出文件名、表头信息、具体数据]）
        plugin_action('Excel/Excel/export', ['全国和四川项目', $cellName, $data]);
        cookie('getInfo',null);//清空cookie
    }
    //资质类别ajax资质名称
    public function ajax_get(){
        $input = input('param.');
        $data = DocumentModel::ajax_get($input);
        $json = [
            'code'=>1,
            'msg'=>'success',
            'data'=>$data
        ];
        return json_encode($json);
    }
}