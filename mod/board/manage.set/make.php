<?php
use Corelib\Method;
use Corelib\Func;
use Manage\Func as Manage;

class Make extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(MOD_BOARD_PATH.'/manage.set/html/make.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','makeBoardForm');
        $form->set('type','html');
        $form->set('action',MOD_BOARD_DIR.'/manage.set/make.sbm.php');
        $form->run();
    }

    public function _func(){
        function board_theme(){
            $tpath = PH_THEME_PATH.'/mod-'.MOD_BOARD.'/board/';
            $topen = opendir($tpath);
            $topt = '';
            while($dir = readdir($topen)){
                if($dir!='.' && $dir!='..'){
                    $topt .= '<option value="'.$dir.'">'.$dir.'</option>';
                    $bd_theme[] = $dir;
                }
            }
            return $topt;
        }
    }

    public function _make(){
        $manage = new Manage();

        Func::add_javascript(PH_PLUGIN_DIR.'/'.PH_PLUGIN_CKEDITOR.'/ckeditor.js');

        $manage->make_target('게시판 기본 설정|권한 설정|아이콘 출력 설정|여분필드');

        $this->set('manage',$manage);
        $this->set('print_target',$manage->print_target());
        $this->set('board_theme',board_theme());
    }

}
