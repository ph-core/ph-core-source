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
        $this->load_tpl(MOD_CONTENTS_PATH.'/manage.set/html/modify.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','modifyContentsForm');
        $form->set('type','html');
        $form->set('action',MOD_CONTENTS_DIR.'/manage.set/modify.sbm.php');
        $form->run();
    }

    public function _func(){
        function set_chked($arr,$val){
            $setarr = array(
                'Y' => '',
                'N' => ''
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

        $sql->scheme('Module\\Contents\\Scheme');

        $req = Method::request('GET','idx');

        $sql->query(
            $sql->scheme->manage('select:contents'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Func::err_back('콘텐츠가 존재하지 않습니다.');
        }

        $arr = $sql->fetchs();

        $sql->specialchars = 0;
        $sql->nl2br = 0;
        $arr['html'] = $sql->fetch('html');
        $arr['mo_html'] = $sql->fetch('mo_html');

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
        $this->set('use_mo_html',set_chked($arr,'use_mo_html'));
    }

}
