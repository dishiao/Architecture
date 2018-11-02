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

namespace plugins\Excel;

use app\common\controller\Plugin;

/**
 * Excel 插件
 * @package plugins\Excel
 */
class Excel extends Plugin
{
    /**
     * @var array 插件信息
     */
    public $info = [
        // 插件名[必填]
        'name'        => 'Excel',
        // 插件标题[必填]
        'title'       => 'Excel 插件',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
        'identifier'  => 'excel.ming.plugin',
        // 插件图标[选填]
        'icon'        => 'fa fa-fw fa-file-excel-o',
        // 插件描述[选填]
        'description' => '导入或导出excel',
        // 插件作者[必填]
        'author'      => '蔡伟明',
        // 作者主页[选填]
        'author_url'  => 'http://www.caiweiming.com',
        // 插件版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
        'version'     => '1.0.1',
        // 是否有后台管理功能[选填]
        'admin'       => '0',
    ];

    /**
     * 安装方法
     * @author 蔡伟明 <314013107@qq.com>
     * @return bool
     */
    public function install(){
        return true;
    }

    /**
     * 卸载方法必
     * @author 蔡伟明 <314013107@qq.com>
     * @return bool
     */
    public function uninstall(){
        return true;
    }
}