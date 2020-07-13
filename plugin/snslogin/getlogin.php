<?php
use Corelib\Func;
use Corelib\Method;

include_once '../../lib/ph.core.php';

$req = Method::request('GET','get_sns,redirect');

//SNS 인증 Request
$callback = '';
$authuri = '';

switch($req['get_sns']){

    case 'kakao' :
        //사용 가능한지 검사
        if($CONF['use_sns_ka']!='Y'){
            Func::err_back('카카오 로그인 기능이 꺼져 있습니다.');
        }
        if(!$CONF['sns_ka_key1'] || !$CONF['sns_ka_key2']){
            Func::err_back('카카오 로그인 Key 값 설정이 잘못 되었습니다.');
        }

        //인증 실행
        $callback = PH_DOMAIN.'/plugin/snslogin/kakaologin.php';
        $authuri = 'https://kauth.kakao.com/oauth/authorize?client_id='.$CONF['sns_ka_key1'].'&redirect_uri='.$callback.'&state='.$req['redirect'].'&response_type=code';
        break;

    case 'naver' :
        //사용 가능한지 검사
        if($CONF['use_sns_nv']!='Y'){
            Func::err_back('네이버 로그인 기능이 꺼져 있습니다.');
        }
        if(!$CONF['sns_nv_key1'] || !$CONF['sns_nv_key2']){
            Func::err_back('네이버 로그인 Key 값 설정이 잘못 되었습니다.');
        }

        $callback = PH_DOMAIN.'/plugin/snslogin/naverlogin.php';
        $authuri = 'https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id='.$CONF['sns_nv_key1'].'&redirect_uri='.$callback.'&state='.$req['redirect'];
        break;

}

Func::location($authuri);
