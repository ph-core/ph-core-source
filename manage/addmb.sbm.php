<?php
use Corelib\Method;
use Corelib\Valid;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Mail;
use Manage\Func as Manage;

class submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        global $CONF;

        $manage = new Manage();
        $sql = new Pdosql();
        $mail = new Mail();

        $sql->scheme('Manage\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','id,pwd,pwd2,name,level,gender,phone,telephone,point,email,email_chk,mb_1,mb_2,mb_3,mb_4,mb_5,mb_6,mb_7,mb_8,mb_9,mb_10,mb_exp');
        $manage->req_hidden_inp('POST');

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
        Valid::isnum('point',$req['point'],0,10,1,'');

        $sql->query(
            $sql->scheme->member('select:id_chk'),
            array(
                $req['id']
            )
        );

        if($sql->getcount()>0){
            Valid::error('id','이미 존재하는 아이디입니다.');
        }

        $sql->query(
            $sql->scheme->member('select:email_chk2'),
            array(
                $req['email']
            )
        );

        if($sql->getcount()>0){
            Valid::error('email','이미 사용중인 이메일입니다.');
        }

        if($CONF['use_emailchk']=='Y'){
            $mbchk_var = 'N';
        }else{
            $mbchk_var = 'Y';
        }

        $mb_exp = $sql->etcfd_exp(implode('|',$req['mb_exp']));

        $sql->query(
            $sql->scheme->member('insert:member'),
            array(
                $req['id'],$req['email'],$req['pwd'],$req['name'],$req['level'],$req['gender'],$req['phone'],$req['telephone'],$mbchk_var,$req['mb_1'],$req['mb_2'],$req['mb_3'],$req['mb_4'],$req['mb_5'],$req['mb_6'],$req['mb_7'],$req['mb_8'],$req['mb_9'],$req['mb_10'],$mb_exp
            )
        );

        $sql->query(
            $sql->scheme->member('select:member2'),
            array(
                $req['id'],$req['pwd']
            )
        );
        $mb_idx = $sql->fetch('mb_idx');

        Func::set_mbpoint($mb_idx,'in',$req['point'],'관리자에 의한 회원가입 포인트 발생');

        if($CONF['use_emailchk']=='Y'){
            $chk_code = md5(date('YmdHis').$req['id']);
            $chk_url = PH_DOMAIN.'/page/member/emailchk.php?chk_code='.$chk_code;

            $mail->tpl = 'signup';
            $mail->t_email = $req['email'];
            $mail->t_name = $req['name'];
            $mail->subject = $req['name'].'님, '.$CONF['title'].' 이메일 인증을 해주세요.';
            $mail->chk_url = '<a href="'.$chk_url.'" target="_blank">'.$chk_url.'</a>';
            $mail->send();

            $sql->query(
                $sql->scheme->member('insert:mbchk'),
                array(
                    $mb_idx,$chk_code
                )
            );

            $succ_msg = '회원이 이메일로 발송된 메일을 확인하거나, 회원 관리에서 이메일 인증 처리하는 경우 회원가입이 완료됩니다.';
        }else{
            $succ_msg = '회원가입이 완료되었습니다.';
        }

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => $succ_msg,
                'location' => PH_MANAGE_DIR.'/?href=mblist&p=modifymb&idx='.$mb_idx
            )
        );
        Valid::success();
    }

}
