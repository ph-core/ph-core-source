<?php
use Corelib\Func;
use Corelib\Method;
use Make\Library\Mail;
use Make\Database\Pdosql;

class Retry_emailchk extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/member/retry_emailchk.tpl.php');
        $this->layout()->foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('type','static');
        $form->set('action',PH_DIR.'/member/retry_emailchk');
        $form->set('target','view');
        $form->set('method','post');
        $form->run();
    }

    public function _make(){
        global $CONF;

        $sql = new Pdosql();
        $mail = new Mail();

        $sql->scheme('Core\\Scheme');

        $req = Method::request('GET','mb_idx');
        $p_req = Method::request('POST','p_mb_idx');

        if(!isset($p_req['p_mb_idx']) && !isset($req['mb_idx'])){
            Func::err_back(ERR_MSG_1);
        }

        //Submit 수행된 경우 (인증메일 재발송)
        if(isset($p_req['p_mb_idx']) && trim($p_req['p_mb_idx'])!=''){

            $sql->query(
                $sql->scheme->member('select:nochked_mb'),
                array(
                    $p_req['p_mb_idx']
                )
            );
            if($sql->getcount()<1){
                Func::err_back('회원 정보를 찾을 수 없습니다.');
            }
            $mbinfo = $sql->fetchs();

            $chk_code = md5(date('YmdHis').$mbinfo['mb_id']);
            $chk_url = PH_DOMAIN.'/member/emailchk?chk_code='.$chk_code;
            $mail->set(
                array(
                    'tpl' => 'signup',
                    't_email' => $mbinfo['mb_email'],
                    't_name' => $mbinfo['mb_name'],
                    'subject' => $mbinfo['mb_name'].'님, '.$CONF['title'].' 이메일 인증을 해주세요.',
                    'chk_url' => '<a href="'.$chk_url.'" target"_blank">[이메일 인증 받기]</a>'
                )
            );
            $mail->send();

            $sql->query(
                $sql->scheme->member('insert:mbchk'),
                array(
                    $mbinfo['mb_idx'],
                    $chk_code
                )
            );

            Func::err_location('인증 메일을 성공적으로 재발송 하였습니다.',PH_DOMAIN);
        }

        $this->set('mb_idx',$req['mb_idx']);
    }

}
