<?php
namespace Module\Board;

use Corelib\Func;
use Make\Database\Pdosql;

//////////////////////////////
// Module : Board Library
//////////////////////////////
class Library{

    //설정 정보 가져옴
    public function load_conf($board_id){
        global $CONF,$func;

        $sql = new Pdosql();

        $sql->scheme('Module\\Board\\Scheme');

        $sql->query(
            $sql->scheme->conf(),
            array(
                $board_id
            )
        );

        //올바른 접근인지 검사
        if(!$board_id){
            Func::err_location('게시판이 지정되지 않았습니다.',PH_DOMAIN);
        }
        if($sql->getcount()<1){
            Func::err_location('존재하지 않는 게시판 입니다.',PH_DOMAIN);
        }

        $conf = $sql->fetchs();
        $sql->specialchars = 0;
        $sql->nl2br = 0;
        $conf['top_source'] = $sql->fetch('top_source');
        $conf['bottom_source'] = $sql->fetch('bottom_source');
        $conf['category'] = $sql->fetch('category');
        $sql->specialchars = 1;
        $sql->nl2br = 1;

        $conf = $sql->fetchs();

        if($CONF['use_mobile']=='Y' && Func::chkdevice()=='mobile'){
            $ex_slt = 1;
        }else{
            $ex_slt = 0;
        }
        $use_list = explode('|',$conf['use_list']);
        $conf['use_list'] = $use_list[$ex_slt];
        $sbj_limit = explode('|',$conf['sbj_limit']);
        $conf['sbj_limit'] = $sbj_limit[$ex_slt];
        $list_limit = explode('|',$conf['list_limit']);
        $conf['list_limit'] = $list_limit[$ex_slt];
        $txt_limit = explode('|',$conf['txt_limit']);
        $conf['txt_limit'] = $txt_limit[$ex_slt];

        return $conf;
    }

    //add stylesheet & javascript
    public function print_headsrc($theme){
        global $mode;
        Func::add_stylesheet(MOD_BOARD_THEME_DIR.'/board/'.$theme.'/style.css');
        if($mode=='write'){
            Func::add_javascript(PH_PLUGIN_DIR.'/'.PH_PLUGIN_CKEDITOR.'/ckeditor.js');
        }
    }

}
