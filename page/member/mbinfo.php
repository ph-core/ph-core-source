<?php
use Corelib\Func;
use Make\Database\Pdosql;

class Mbinfo extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->head();
        $this->_func();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/member/mbinfo.tpl.php');
        $this->layout()->foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('type','html');
        $form->set('action',PH_DIR.'/page/member/mbinfo.sbm.php');
        $form->run();
    }

    public function _func(){
        //성별 처리
        function gender_chked($obj){
            $arr = array(
                'M' => '',
                'F' => ''
            );
            foreach($arr as $key => $value){
                if($key==$obj['mb_gender']){
                    $arr[$key] = 'checked';
                }
            }
            return $arr;
        }
    }

    public function _make(){
        global $MB;

        $sql = new Pdosql();

        $sql->scheme('Core\\Scheme');

        Func::getlogin(SET_NOAUTH_MSG);

        if($MB['adm']=='Y'){
            Func::err_location('최고 레벨의 관리자는 Manage 에서 정보 변경 가능합니다.',PH_DOMAIN);
        }

        //회원 정보 select
        $sql->query(
            $sql->scheme->member('select:mbinfo'),
            array(
                MB_IDX
            )
        );
        $arr = $sql->fetchs();

        $arr['mb_point'] = Func::number($arr['mb_point']);
        $arr['mb_regdate'] = Func::datetime($arr['mb_regdate']);
        $arr['mb_lately'] = Func::datetime($arr['mb_lately']);

        $mb = array();
        if(isset($arr)){
            foreach($arr as $key => $value){
                $mb[$key] = $value;
            }
        }else{
            $mb = null;
        }

        $this->set('gender_chked',gender_chked($arr));
        $this->set('mb',$mb);
    }

}
