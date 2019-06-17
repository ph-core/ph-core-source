<?php
use Corelib\Func;
use Corelib\Method;
use Make\Database\Pdosql;

class Emailchk extends \Controller\Make_Controller{

    public function _init(){
        $this->common()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/member/emailchk.tpl.php');
        $this->common()->foot();
    }

    public function _make(){
        $sql = new Pdosql();

        $sql->scheme('Core\\Scheme');

        Method::security('REQUEST_GET');
        $req = Method::request('GET','chk_code');

        $succ_var = true;
        $msg = '';

        if(!isset($req['chk_code']) || trim($req['chk_code'])==''){
            Func::err_location(ERR_MSG_1,PH_DOMAIN);
        }

        //인증코드 정보 및 인증코드 생성되어 있는지 확인
        $sql->query(
            $sql->scheme->member('select:mbchk'),
            array(
                $req['chk_code']
            )
        );
        $mb_idx = $sql->fetch('mb_idx');
        $chk_mode = $sql->fetch('chk_mode');

        //인증코드 검사 및 실패시
        if($sql->getcount()<1){
            $msg = '인증 요청 내역을 확인할 수 없습니다.<br />다시 확인 후 시도해 주세요.';
            $succ_var = false;
        }

        //만료된 인증코드인 경우
        $sql->query(
            $sql->scheme->member('select:mbchk2'),
            array(
                $mb_idx
            )
        );

        if($succ_var==true && $sql->fetch('chk_code')!=$req['chk_code']){
            $msg = '만료된 인증코드 이거나, 존재하지 않는 인증코드 입니다.<br />인증코드 재발송 후 다시 시도해 주시기 바랍니다.';
            $succ_var = false;
        }

        //이미 인증된 경우
        if($succ_var==true && $sql->fetch('chk_chk')=='Y'){
            $msg = '이미 이메일 인증을 완료 하였습니다.<br />회원님의 아이디로 홈페이지를 정상적으로 이용할 수 있습니다.';
            $succ_var = false;
        }

        if($succ_var===true){

            //신규가입 인증인 경우
            if($chk_mode=='chk'){
                $sql->query(
                    $sql->scheme->member('update:mbchk'),
                    array(
                        $mb_idx
                    )
                );
            }

            //이메일 변경 인증인 경우
            if($chk_mode=='chg'){
                $sql->query(
                    $sql->scheme->member('update:mbchk2'),
                    array(
                        $mb_idx
                    )
                );
            }

            //update
            $sql->query(
                $sql->scheme->member('update:mbchk3'),
                array(
                    $req['chk_code']
                )
            );

            $msg = '회원님의 이메일이 성공적으로 인증되었습니다.<br />로그인 후 정상적으로 서비스 이용 가능합니다.<br />감사합니다.';

        }

        $this->set('msg',$msg);
    }
}
