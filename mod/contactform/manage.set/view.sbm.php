<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;
use Make\Library\Mail;
use Manage\Func as Manage;

class submit{

    public function _init(){
        global $req;

        $this->_make();

        switch($req['mode']){
            case 'rep' :
            $this->get_reply();
            break;

            case 'del' :
            $this->get_delete();
            break;
        }
    }

    public function _make(){
        global $req;

        $manage = new Manage();

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','mode,idx,article');
        $manage->req_hidden_inp('POST');
    }

    //////////////////////////////
    // reply
    //////////////////////////////
    public function get_reply(){
        global $CONF,$req;

        $sql = new Pdosql();
        $mail = new Mail();

        $sql->scheme('Module\\Contactform\\Scheme');

        Valid::notnull('article',$req['article'],'');

        $sql->query(
            $sql->scheme->manage('insert:contact'),
            array(
                $req['article']
            )
        );

        $sql->query(
            $sql->scheme->manage('select:contact2'),
            array(
                $req['article']
            )
        );
        $rep_idx = $sql->fetch('idx');

        $sql->query(
            $sql->scheme->manage('update:rep'),
            array(
                $rep_idx,
                $req['idx']
            )
        );

        $sql->query(
            $sql->scheme->manage('select:contact'),
            array(
                $req['idx']
            )
        );

        $arr = $sql->fetchs();

        $memo = stripslashes($req['article']);
        $memo .= '
        <i>
        <br /><br /><br />
        <strong>문의 내용 :</strong><br />
        '.$arr['article'].'
        </i>
        ';

        $mail->set(
            array(
                't_email' => $arr['email'],
                'subject' => $CONF['title'].' 에 등록한 문의에 대한 답변입니다.',
                'memo' => $memo
            )
        );
        $mail->send();

        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 답변이 발송 되었습니다.'
            )
        );
        Valid::success();
    }

    //////////////////////////////
    // delete
    //////////////////////////////
    public function get_delete(){
        global $req;

        $sql = new Pdosql();
        $manage = new Manage();

        $sql->scheme('Module\\Contactform\\Scheme');

        $sql->query(
            $sql->scheme->manage('select:contact'),
            array(
                $req['idx']
            )
        );

        if($sql->getcount()<1){
            Valid::error('','문의가 존재하지 않습니다.');
        }

        $rep_idx = $sql->fetch('rep_idx');

        $sql->query(
            $sql->scheme->manage('delete:contact'),
            array(
                $req['idx']
            )
        );

        $sql->query(
            $sql->scheme->manage('delete:contact'),
            array(
                $rep_idx
            )
        );

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 삭제 되었습니다.',
                'location' => PH_MANAGE_DIR.'/?mod='.MOD_CONTACTFORM.'&href=lists'.$manage->retlink('')
            )
        );
        Valid::success();
    }

}
