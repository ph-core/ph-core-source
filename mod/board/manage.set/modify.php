<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class Modify extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(MOD_BOARD_PATH.'/manage.set/html/modify.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','modifyBoardForm');
        $form->set('type','html');
        $form->set('action',MOD_BOARD_DIR.'/manage.set/modify.sbm.php');
        $form->run();
    }

    public function _func(){
        function board_theme($arr){
            $tpath = PH_THEME_PATH.'/mod-'.MOD_BOARD.'/board/';
            $topen = opendir($tpath);
            $topt = '';
            while($dir = readdir($topen)){
                $slted = '';
                if($dir!='.' && $dir!='..'){
                    if($dir==$arr['theme']){
                        $slted = 'selected';
                    }
                    $topt .= '<option value="'.$dir.'" '.$slted.'>'.$dir.'</option>';
                }
            }
            return $topt;
        }

        function set_chked($arr,$val){
            $setarr = array(
                'Y' => '',
                'N' => '',
                'AND' => '',
                'OR' => ''
            );
            foreach($setarr as $key => $value){
                if($key==$arr[$val]){
                    $setarr[$key] = 'checked';
                }
            }
            return $setarr;
        }
    }

    public function _make(){
        $sql = new Pdosql();
        $manage = new Manage();

        $sql->scheme('Module\\Board\\Scheme');

        $req = Method::request('GET','idx');

        Func::add_javascript(PH_PLUGIN_DIR.'/'.PH_PLUGIN_CKEDITOR.'/ckeditor.js');

        $manage->make_target('게시판 기본 설정|권한 설정|아이콘 출력 설정|여분필드');

        $sql->query(
            $sql->scheme->manage('select:board'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Func::err_back('게시판이 존재하지 않습니다.');
        }

        $arr = $sql->fetchs();
        $sql->specialchars = 0;
        $sql->nl2br = 0;
        $arr['top_source'] = $sql->fetch('top_source');
        $arr['bottom_source'] = $sql->fetch('bottom_source');

        $use_list = explode('|',$arr['use_list']);
        $arr['use_list'] = $use_list[0];
        $arr['m_use_list'] = $use_list[1];

        $list_limit = explode('|',$arr['list_limit']);
        $arr['list_limit'] = $list_limit[0];
        $arr['m_list_limit'] = $list_limit[1];

        $sbj_limit = explode('|',$arr['sbj_limit']);
        $arr['sbj_limit'] = $sbj_limit[0];
        $arr['m_sbj_limit'] = $sbj_limit[1];

        $txt_limit = explode('|',$arr['txt_limit']);
        $arr['txt_limit'] = $txt_limit[0];
        $arr['m_txt_limit'] = $txt_limit[1];

        $ico_hot_case = explode('|',$arr['ico_hot_case']);
        $arr['ico_hot_case_1'] = $ico_hot_case[0];
        $arr['ico_hot_case_2'] = $ico_hot_case[2];
        $arr['ico_hot_case_3'] = $ico_hot_case[1];

        $ex = explode('|',$arr['conf_exp']);
        for($i=1;$i<=10;$i++){
            $arr['conf_'.$i.'_exp'] = $ex[$i - 1];
        }

        $write = array();
        if(isset($arr)){
            foreach($arr as $key => $value){
                $write[$key] = $value;
            }
        }else{
            $write = null;
        }

        $this->set('manage',$manage);
        $this->set('write',$write);
        $this->set('print_target',$manage->print_target());
        $this->set('board_theme',board_theme($arr));
        $this->set('use_category',set_chked($arr,'use_category'));
        $this->set('use_list',set_chked($arr,'use_list'));
        $this->set('m_use_list',set_chked($arr,'m_use_list'));
        $this->set('use_likes',set_chked($arr,'use_likes'));
        $this->set('use_reply',set_chked($arr,'use_reply'));
        $this->set('use_comment',set_chked($arr,'use_comment'));
        $this->set('use_secret',set_chked($arr,'use_secret'));
        $this->set('ico_secret_def',set_chked($arr,'ico_secret_def'));
        $this->set('use_file1',set_chked($arr,'use_file1'));
        $this->set('use_file2',set_chked($arr,'use_file2'));
        $this->set('use_mng_feed',set_chked($arr,'use_mng_feed'));
        $this->set('ico_file',set_chked($arr,'ico_file'));
        $this->set('ico_secret',set_chked($arr,'ico_secret'));
        $this->set('ico_new',set_chked($arr,'ico_new'));
        $this->set('ico_hot',set_chked($arr,'ico_hot'));
        $this->set('ico_hot_case_3',set_chked($arr,'ico_hot_case_3'));
    }

}
