<?php
use Corelib\Method;
use Corelib\Valid;
use Corelib\Session;
use Make\Library\Mail;
use Make\Database\Pdosql;

class Submit{

    public function _init(){
        global $req;

        $this->_make();

        switch($req['mode']){
            case 'mdf' :
            $this->get_mdf();
            break;

            case 'lv' :
            $this->get_lv();
            break;
        }
    }

    public function _make(){
        global $req;

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','mode,email,pwd,pwd2,name,gender,phone,telephone,email_chg_cc');

        if(!IS_MEMBER){
            Valid::error('',SET_NOAUTH_MSG);
        }
    }

    //////////////////////////////
    // 회원 정보 변경
    //////////////////////////////
    private function get_mdf(){
        global $MB,$CONF,$req;

        $sql = new Pdosql();
        $mail = new Mail();

        $sql->scheme('Core\Scheme');

        Valid::isnick('name',$req['name'],1,'');
        Valid::isphone('phone',$req['phone'],0,'');
        Valid::isphone('telephone',$req['telephone'],0,'');

        //비밀번호가 입력된 경우
        if($req['pwd']!=$req['pwd2']){
            Valid::error('pwd2','비밀번호와 비밀번호 확인이 일치하지 않습니다.');
        }
        if($req['pwd']!=''){
            Valid::ispwd('pwd',$req['pwd'],1,'');
        }

        //이메일이 입력된 경우
        $mb_email_chg = '';
        if($req['email']!='' && $req['email']==$MB['email']){
            Valid::error('email','회원님이 이미 사용중인 이메일입니다.');
        }
        if($req['email']!=''){
            Valid::isemail('email',$req['email'],1,'');

            $sql->query(
                $sql->scheme->member('select:emailvalid'),
                array(
                    $req['email'],
                    $MB['email']
                )
            );

            if($sql->getcount()>0){
                Valid::error('email','다른 회원이 사용중인 이메일입니다.');
            }
            $mb_email_chg = $req['email'];
        }

        //이메일이 입력된 경우 인증 메일 발송
        if($req['email']!=''){
            $chk_code = md5(date('YmdHis').$req['email']);
            $chk_url = PH_DOMAIN.'/member/emailchk?chk_code='.$chk_code;

            $mail->set(
                array(
                    'tpl' => 'signup',
                    't_email' => $req['email'],
                    't_name' => $req['name'],
                    'subject' => $req['name'].'님, '.$CONF['title'].' 이메일 변경 인증을 해주세요.',
                    'chk_url' => '<a href="'.$chk_url.'" target="_blank">[이메일 인증하기]</a>'
                )
            );
            $mail->send();

            $sql->query(
                $sql->scheme->member('insert:mbchk_chg'),
                array(
                    MB_IDX,
                    $chk_code
                )
            );
        }

        //이메일 변경 취소
        if(!$req['email'] && $req['email_chg_cc']=='checked'){
            $mb_email_chg = '';
        }

        //update
        if($req['pwd']!=''){
            $sql->query(
                $sql->scheme->member('update:mbinfo'),
                array(
                    $req['pwd'],
                    $req['name'],
                    $req['gender'],
                    $req['phone'],
                    $req['telephone'],
                    $mb_email_chg,
                    MB_IDX
                )
            );
        }else{
            $sql->query(
                $sql->scheme->member('update:mbinfo2'),
                array(
                    $MB['pwd'],
                    $req['name'],
                    $req['gender'],
                    $req['phone'],
                    $req['telephone'],
                    $mb_email_chg,
                    MB_IDX
                )
            );
        }


        //return
        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 변경 되었습니다.'
            )
        );
        Valid::success();
    }

    //////////////////////////////
    // 회원 탈퇴
    //////////////////////////////
    private function get_lv(){
        global $MB;

        $sql = new Pdosql();

        $sql->scheme('Core\Scheme');

        if($MB['adm']=='Y'){
            Valid::error('','최고 관리자는 탈퇴할 수 없습니다.');
        }

        //delete
        $sql->query(
            $sql->scheme->member('update:mbleave'),
            array(
                MB_IDX
            )
        );

        //로그인 세션 삭제
        Session::drop_sess();

        //return
        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '탈퇴가 완료 되었습니다. 그동안 이용해 주셔서 감사합니다.',
                'location' => PH_DOMAIN,
            )
        );
        Valid::success();
    }
}
