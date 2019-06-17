<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Library\Mail;
use Make\Database\Pdosql;

class Submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        global $CONF;

        $sql = new Pdosql();
        $mail = new Mail();

        $sql->scheme('Core\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','email');

        if(IS_MEMBER){
            Valid::error('',SET_ALRAUTH_MSG);
        }

        Valid::isemail('email',$req['email'],1,'');

        //회원정보 확인
        $sql->query(
            $sql->scheme->member('select:forgot'),
            array(
                $req['email']
            )
        );

        if($sql->getcount()<1){
            Valid::error('email','회원 정보를 찾을 수 없습니다. 이메일 주소를 확인해 주세요.');
        }

        $mb_id = $sql->fetch('mb_id');
        $mb_name = $sql->fetch('mb_name');

        //임시 비밀번호 생성 및 회원DB update
        $upw = substr(md5(date('YmdHis').$mb_id),0,10);

        $sql->query(
            $sql->scheme->member('update:pwd'),
            array(
                $upw,$mb_id
            )
        );

        //회원 메일로 임시 비밀번호 발송
        $mail->set(
            array(
                'tpl' => 'forgot',
                't_email' => $req['email'],
                't_name' => $mb_name,
                'subject' => $mb_name.'님의 '.$CONF['title'].' 로그인 정보입니다.',
                'mb_id' => $mb_id,
                'mb_pwd' => $upw
            )
        );
        $mail->send();

        //return
        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '회원님의 이메일로 로그인 정보가 성공적으로 발송 되었습니다.',
                'location' => PH_DOMAIN
            )
        );
        Valid::success();
    }

}
