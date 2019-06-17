<?php
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;
use Make\Library\Mail;
use Manage\Func as Manage;

class submit{

    public function _init(){
        $this->_make();
    }

    public function _make(){
        $sql = new Pdosql();
        $mail = new Mail();
        $manage = new Manage();

        $sql->scheme('Manage\\Scheme');

        Method::security('REFERER');
        Method::security('REQUEST_POST');
        $req = Method::request('POST','type,to_mb,level_from,level_to,tpl,subject,html');
        $manage->req_hidden_inp('POST');

        if($req['type']==1){
            Valid::notnull('to_mb',$req['to_mb'],'');
        }else{
            if($req['level_from'] > $req['level_to']){
                Valid::error('level_to','수신 종료 level 보다 시작 level이 클 수 없습니다.');
            }
        }
        if(!$req['tpl']){
            Valid::error('tpl','메일 템플릿이 존재하지 않거나 선택되지 않았습니다.');
        }

        Valid::notnull('subject',$req['subject'],'');
        Valid::notnull('html',$req['html'],'');

        $rcv_email = array();

        if($req['type']==1){

            $sql->query(
                $sql->scheme->sendmail('select:to_mb'),
                array(
                    $req['to_mb']
                )
            );

            if($sql->getcount()<1){
                Valid::error('','존재하지 않는 회원 id 입니다.');
            }
            if(!$sql->fetch('mb_email')){
                Valid::error('','회원 email이 등록되어 있지 않습니다.');
            }
            $rcv_email[] = $sql->fetch('mb_email');

            $req['level_from'] = 0;
            $req['level_to'] = 0;
        }

        if($req['type']==2){

            $sql->query(
                $sql->scheme->sendmail('select:to_level'),
                array(
                    $req['level_from'],
                    $req['level_to']
                )
            );

            if($sql->getcount()<1){
                Valid::error('','범위내 수신할 회원이 존재하지 않습니다.');
            }

            do{
                $rcv_email[] = $sql->fetch('mb_email');
            }while($sql->nextRec());

            $req['to_mb'] = '';
        }

        for($i=0;$i<count($rcv_email);$i++){
            $mail->set(
                array(
                    'tpl' => $req['tpl'],
                    't_email' => $rcv_email[$i],
                    'subject' => $req['subject'],
                    'memo' => stripslashes($req['html'])
                )
            );
            $mail->send();
        }

        $sql->query(
            $sql->scheme->sendmail('insert:sendmail'),
            array(
                $req['tpl'],$req['to_mb'],$req['level_from'],$req['level_to'],$req['subject'],$req['html']
            )
        );

        $sql->query(
            $sql->scheme->sendmail('select:sendmail'),
            array(
                $req['subject']
            )
        );

        $idx = $sql->fetch('idx');

        Valid::set(
            array(
                'return' => 'alert->location',
                'msg' => '성공적으로 발송 되었습니다.',
                'location' => PH_MANAGE_DIR.'/?href=mailhis&p=viewmail&idx='.$idx
            )
        );
        Valid::success();
    }

}
