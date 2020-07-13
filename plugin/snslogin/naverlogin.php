<?php
use Corelib\Func;
use Corelib\Method;
use Corelib\Session;
use Make\Database\Pdosql;

include_once '../../lib/ph.core.php';

$req = Method::request('GET','state');

$req['redirect'] = $req['state'];

$sql = new Pdosql();

$sql->scheme('Core\\Scheme');

if(IS_MEMBER){
    Func::er_location('이미 로그인 되어 있습니다.',PH_DOMAIN);
}

//네이버 로그인 콜백
$client_id = $CONF['sns_nv_key1'];
$client_secret = $CONF['sns_nv_key2'];
$code = $_GET['code'];
$state = $_GET['state'];
$redirectURI = urlencode(PH_DOMAIN.'/plugin/snslogin/naverlogin.php');
$url = 'https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id='.$client_id.'&client_secret='.$client_secret.'&redirect_uri='.$redirectURI.'&code='.$code.'&state='.$state;
$is_post = false;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, $is_post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers = array();
$res = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$res = json_decode($res,true);

//성공한 경우 획득한 Token으로 계정 정보 불러옴
if($status_code==200 && isset($res['access_token'])){
    $token = $res['access_token'];
    $header = 'Bearer '.$token; // Bearer 다음에 공백 추가
    $url = 'https://openapi.naver.com/v1/nid/me';
    $is_post = false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = array();
    $headers[] = 'Authorization: '.$header;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $res = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $res = json_decode($res,true);

    if($status_code==200) {

        //회원 정보를 받아 옴
        $naver_arr = array();
        $naver_arr['token'] = $token;
        @$naver_arr['id'] = $res['response']['id'];
        @$naver_arr['name'] = $res['response']['name'];
        @$naver_arr['email'] = $res['response']['email'];
        @$naver_arr['gender'] = $res['response']['gender'];
    }

    if($status_code!=200){
        echo 'Error : Naver 로그인 오류';
        exit;
    }
}

//실패한 경우 error 출력
else if($status_code!=200 || !isset($res['access_token'])){
    echo 'Error : Naver 로그인 오류';
    exit;
}

//중복되는 이메일이 아닌 경우 그대로 회원가입에 활용, 중복되는 경우 비워둠
$naver_inf = array();
$naver_inf['email'] = '';
if($naver_arr['email']){
    $sql->query(
        $sql->scheme->member('select:email_inspt'),
        array(
            $naver_arr['email']
        )
    );

    if($sql->getcount()<1){
        $naver_inf['email'] = $naver_arr['email'];
    }
}

//성별 처리
$naver_inf['gender'] = '';
if($naver_arr['gender']){
    $naver_inf['gender'] = strtoupper(substr($naver_arr['gender'],0,1));
}

//이름 처리
$match = REGEXP_NICK;
$naver_inf['name'] = '픽플회원'.rand(1,999);
if($naver_arr['name']){
    if(preg_match($match,$naver_arr['name'])){
        $naver_inf['name'] = $naver_arr['name'];
    }
}

//임의 회원 아이디 생성
$naver_inf['usrid'] = 'naver'.$naver_arr['id'];

//임의 회원 비밀번호 생성
$naver_inf['pwd'] = 'naver'.$naver_arr['id'].date('ymdhis',time()).rand(0,9999);

//가입여부 확인
$sql->query(
    $sql->scheme->member('select:mb_sns_nv_id_inspt'),
    array(
        $naver_arr['id']
    )
);

//가입되지 않은 네이버 회원인 경우 가입 처리
if($sql->getcount()<1){
    $sql->query(
        $sql->scheme->member('insert:signup'),
        array(
            $naver_inf['usrid'],$naver_inf['email'],$naver_inf['pwd'],$naver_inf['name'],$naver_inf['gender'],'','','Y','','','','','','','','','','','',$naver_arr['id'],'',$naver_arr['token'],$sql->etcfd_exp('')
        )
    );
}

//가입되어있는 경우 Token키 업데이트
if($sql->getcount()>0){
    $sql->query(
        $sql->scheme->member('update:sns_login_update_token_nv'),
        array(
            $naver_arr['token'],$naver_arr['id']
        )
    );
}

//로그인 정보 로드
$sql->query(
    $sql->scheme->member('select:mb_sns_nv_signin'),
    array(
        $naver_arr['id']
    )
);

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

//로그인 완료 후 페이지 이동
Func::location(PH_DOMAIN.urldecode($req['redirect']));
