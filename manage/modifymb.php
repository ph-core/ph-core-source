<?php
use Corelib\Method;
use Corelib\Func;
use Make\Database\Pdosql;
use Manage\Func as Manage;

class Modifymb extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->mng_head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/modifymb.tpl.php');
        $this->layout()->mng_foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id','modifymbForm');
        $form->set('type','html');
        $form->set('action',PH_MANAGE_DIR.'/modifymb.sbm.php');
        $form->run();
    }

    public function _func(){
        function set_checked($arr,$val){
            $setarr = array(
                'Y' => '',
                'N' => '',
                'M' => '',
                'F' => ''
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

        $sql->scheme('Manage\\Scheme');

        $req = Method::request('GET','idx');

        $sql->query(
            $sql->scheme->member('select:member'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Func::err_back('회원이 존재하지 않거나 수정할 수 없는 회원입니다.');
        }

        $arr = $sql->fetchs();

        $manage->make_target('회원 기본정보|회원 접속 정보|여분필드');

        $ex = explode('|',$arr['mb_exp']);
        for($i=1;$i<=10;$i++){
            $arr['mb_'.$i.'_exp'] = $ex[$i - 1];
        }

        $write = array();
        if(isset($arr)){
            foreach($arr as $key => $value){
                $write[$key] = $value;
            }

            $write['mb_regdate'] = Func::datetime($write['mb_regdate']);
            $write['mb_lately'] = Func::datetime($write['mb_lately']);
        }else{
            $write = null;
        }

        $this->set('manage',$manage);
        $this->set('write',$write);
        $this->set('print_target',$manage->print_target());
        $this->set('email_chk',set_checked($write,'mb_email_chk'));
        $this->set('gender',set_checked($write,'mb_gender'));
    }

}
