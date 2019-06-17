<?php
use Corelib\Method;
use Corelib\Session;
use Corelib\Valid;
use Make\Database\Pdosql;

class Submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        global $CONF;

        $sql = new Pdosql();

        $sql->scheme('Core\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','id,pwd,save,redirect');

        if(IS_MEMBER){
            Valid::error('',SET_ALRAUTH_MSG);
        }
        Valid::isid('id',$req['id'],1,'');
        Valid::ispwd('pwd',$req['pwd'],1,'');

        $sql->query(
            $sql->scheme->member('select:signin'),
            array(
                $req['id'],
                $req['pwd']
            )
        );

        if($sql->getcount()<1){
            Valid::error('id','아이디 혹은 비밀번호가 잘못 되었습니다.');
        }

        //이메일 인증이 완료되지 않은 아이디인 경우.
        if($sql->fetch('mb_email_chk')=='N' && $CONF['use_emailchk']=='Y'){
            Valid::set(
                array(
                    'return' => 'alert->location',
                    'msg' => '이메일 인증이 완료되지 않은 아이디입니다.',
                    'location' => PH_DIR.'/member/retry_emailchk?mb_idx='.$sql->fetch('mb_idx')
                )
            );
            Valid::success();
        }

        $mbinfo = array();
        $mbinfo['id'] = $sql->fetch('mb_id');
        $mbinfo['idx'] = $sql->fetch('mb_idx');

        //로그인 session 처리
        Session::set_sess('MB_IDX',$mbinfo['idx']);

        //최근 로그인 내역 기록
        $sql->query(
            $sql->scheme->member('update:mblately'),
            array(
                $_SERVER['REMOTE_ADDR'],
                $mbinfo['idx']
            )
        );

        //아이디 저장을 체크한 경우 아이디를 쿠키에 저장
        if($req['save']=='checked'){
            setcookie('MB_SAVE_ID',$mbinfo['id'],time()+2592000,'/');
        }else{
            setcookie('MB_SAVE_ID','',0,'/');
        }

        //return
        Valid::set(
            array(
                'return' => 'alert->location',
                'location' => urldecode($req['redirect'])
            )
        );
        Valid::success();
    }

}
