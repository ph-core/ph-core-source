<?php
use Corelib\Method;
use Corelib\Valid;
use Corelib\Func;
use Make\Database\Pdosql;
use Make\Library\Mail;

class Submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        global $CONF,$MODULE_CONTACTFORM_CONF;

        $sql = new Pdosql();
        $mail = new Mail();

        $sql->scheme('Module\\Contactform\\Scheme');

        include_once PH_PLUGIN_PATH.'/capcha/zmSpamFree.php';

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','name,email,phone,article,capcha,contact_1,contact_2,contact_3,contact_4,contact_5,contact_6,contact_7,contact_8,contact_9,contact_10');

        Valid::isnick('name',$req['name'],1,'');
        Valid::isemail('email',$req['email'],1,'');
        Valid::isphone('phone',$req['phone'],1,'');
        Valid::notnull('article',$req['article'],'');

        if(!IS_MEMBER){
            Valid::notnull('capcha',$req['capcha'],'');
            if(zsfCheck($req['capcha'],'')!=true){
                Valid::set(
                    array(
                        'return' => 'error',
                        'input' => 'capcha',
                        'err_code' => 'NOTMATCH_CAPCHA'
                    )
                );
                Valid::success();
            }
        }

        //insert
        $sql->query(
            $sql->scheme->write('insert:contact'),
            array(
                MB_IDX,$req['article'],$req['name'],$req['email'],$req['phone'],$req['contact_1'],$req['contact_2'],$req['contact_3'],$req['contact_4'],$req['contact_5'],$req['contact_6'],$req['contact_7'],$req['contact_8'],$req['contact_9'],$req['contact_10']
            )
        );

        //mail
        $memo = '
        새로운 문의가 등록되었습니다.<br /><br />
        <a href="'.PH_DOMAIN.'/manage/?mod='.MOD_CONTACTFORM.'&href=list">'.PH_DOMAIN.'/manage/?mod='.MOD_CONTACTFORM.'&href=list</a> 를 클릭하여<br />
        관리 페이지로 접속 후 확인하세요.
        ';
        $mail->set(
            array(
                't_email' => $CONF['email'],
                'subject' => '새로운 문의가 등록되었습니다.',
                'memo' => $memo
            )
        );
        $mail->send();

        //관리자 최근 피드에 등록
        Func::add_mng_feed(
            array(
                $MODULE_CONTACTFORM_CONF['title'],
                '<strong>'.$req['name'].'</strong>님이 새로운 문의를 등록했습니다.',
                '/manage/?mod='.MOD_CONTACTFORM.'&href=lists'
            )
        );

        //return
        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '문의가 정상적으로 접수 되었습니다.',
            )
        );
        Valid::success();
    }

}
