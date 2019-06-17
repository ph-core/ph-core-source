<?php
use Corelib\Func;
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

        $mail = new Mail();
        $sql = new Pdosql();

        $sql->scheme('Core\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','id,email,pwd,pwd2,name,gender,phone,telephone,mb_1,mb_2,mb_3,mb_4,mb_5,mb_6,mb_7,mb_8,mb_9,mb_10');

        if(IS_MEMBER){
            Valid::error('',SET_ALRAUTH_MSG);
        }

        Valid::isid('id',$req['id'],1,'');
        Valid::isemail('email',$req['email'],1,'');
        Valid::ispwd('pwd',$req['pwd'],1,'');
        Valid::ispwd('pwd2',$req['pwd2'],1,'');
        if($req['pwd']!=$req['pwd2']){
            Valid::error('pwd2','비밀번호와 비밀번호확인이 일치하지 않습니다.');
        }
        Valid::isnick('name',$req['name'],1,'');
        Valid::isphone('phone',$req['phone'],0,'');
        Valid::isphone('telephone',$req['telephone'],0,'');

        //아이디 중복 검사
        $sql->query(
            $sql->scheme->member('select:id_inspt'),
            array(
                $req['id']
            )
        );

        if($sql->getcount()>0){
            Valid::error('id','이미 존재하는 아이디입니다.');
        }

        //이메일 중복 검사
        $sql->query(
            $sql->scheme->member('select:email_inspt'),
            array(
                $req['email']
            )
        );

        if($sql->getcount()>0){
            Valid::error('email','이미 사용중인 이메일입니다. \'회원정보 찾기\' 페이지에서 로그인 정보를 찾을 수 있습니다.');
        }

        //insert
        if($CONF['use_emailchk']=='Y'){
            $mbchk_var = 'N';
        }else{
            $mbchk_var = 'Y';
        }
        $sql->query(
            $sql->scheme->member('insert:signup'),
            array(
                $req['id'],$req['email'],$req['pwd'],$req['name'],$req['gender'],$req['phone'],$req['telephone'],$mbchk_var,$req['mb_1'],$req['mb_2'],$req['mb_3'],$req['mb_4'],$req['mb_5'],$req['mb_6'],$req['mb_7'],$req['mb_8'],$req['mb_9'],$req['mb_10'],$sql->etcfd_exp('')
            )
        );

        //회원 idx를 다시 가져옴
        $sql->query(
            $sql->scheme->member('select:mb_idx'),
            array(
                $req['id'],
                $req['pwd']
            )
        );
        $mb_idx = $sql->fetch('mb_idx');

        //이메일 인증 메일 발송
        if($CONF['use_emailchk']=='Y'){
            $chk_code = md5(date('YmdHis').$req['id']);
            $chk_url = PH_DOMAIN.'/member/emailchk?chk_code='.$chk_code;
            $mail->set(
                array(
                    'tpl' => 'signup',
                    't_email' => $req['email'],
                    't_name' => $req['name'],
                    'subject' => $req['name'].'님, '.$CONF['title'].' 이메일 인증을 해주세요.',
                    'chk_url' => '<a href=\''.$chk_url.'\' target=\'_blank\'>[이메일 인증 받기]</a>'
                )
            );
            $mail->send();

            $sql->query(
                $sql->scheme->member('insert:mbchk'),
                array(
                    $mb_idx,
                    $chk_code
                )
            );

            $succ_msg = '이메일로 발송된 메일을 확인해 주시면 회원가입이 완료됩니다. 가입해 주셔서 감사합니다.';
        }else{
            $succ_msg = '회원가입이 완료되었습니다. 가입해 주셔서 감사합니다.';
        }

        //관리자 최근 피드에 등록
        Func::add_mng_feed(
            array(
                '회원가입',
                '<strong>'.$req['name'].'</strong>님이 회원가입 했습니다.',
                '/manage/?href=mblist&p=modifymb&idx='.$mb_idx
            )
        );

        //return
        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => $succ_msg,
                'location' => PH_DOMAIN
            )
        );
        Valid::success();
    }

}
