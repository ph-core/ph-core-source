<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class View extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(MOD_CONTACTFORM_PATH.'/manage.set/html/view.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','viewContactformForm');
        $form->set('type','html');
        $form->set('action',MOD_CONTACTFORM_DIR.'/manage.set/view.sbm.php');
        $form->run();
    }

    public function _func(){
        function print_name($arr){
            if($arr['mb_idx']!=0){
                return '<a href="./?href=mblist&p=modifymb&idx='.$arr['mb_idx'].'">'.$arr['name'].'</a>';
            }else{
                return $arr['name'];
            }
        }

        function print_reply($arr,$reparr){
            if($arr['rep_idx']!=0){
                return Func::datetime($reparr['regdate']).' 에 답변';
            }else{
                return '대기';
            }
        }
    }

    public function _make(){
        $manage = new Manage();
        $sql = new Pdosql();

        $sql->scheme('Module\\Contactform\\Scheme');

        $req = Method::request('GET','idx');

        Func::add_javascript(PH_PLUGIN_DIR.'/'.PH_PLUGIN_CKEDITOR.'/ckeditor.js');

        $sql->query(
            $sql->scheme->manage('select:contact'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Func::err_back('문의가 존재하지 않습니다.');
        }
        $arr = $sql->fetchs();

        if($arr['rep_idx']!=0){
            $is_reply_show = true;
            $is_reply_btn_show = false;
        }else{
            $is_reply_show = false;
            $is_reply_btn_show = show;
        }

        $view = array();
        if(isset($arr)){
            foreach($arr as $key => $value){
                $view[$key] = $value;
            }
            $view['regdate'] = Func::datetime($view['regdate']);
        }else{
            $view = null;
        }

        $reparr = array();

        if($arr['rep_idx']!=0){

            $sql->query(
                $sql->scheme->manage('select:contact'),
                array(
                    $arr['rep_idx']
                )
            );

            $sql->specialchars = 0;
            $sql->nl2br = 0;
            $reparr = $sql->fetchs();

            $repview = array();
            if(isset($reparr)){
                foreach($reparr as $key => $value){
                    $repview[$key] = $value;
                }
            }else{
                $repview = null;
            }

            $this->set('repview',$repview);

        }

        $this->set('manage',$manage);
        $this->set('view',$view);
        $this->set('is_reply_show',$is_reply_show);
        $this->set('is_reply_btn_show',$is_reply_btn_show);
        $this->set('print_name',print_name($arr));
        $this->set('print_reply',print_reply($arr,$reparr));
    }

}
