<?php
header('Content-Type: text/html; charset=UTF-8');

//////////////////////////////
// Ph-Core 기본 상수
//////////////////////////////
define('USE_MOBILE','C'); //모바일 사용 여부. (사용: 'Y', 비사용: 'N', 관리페이지 설정에 따름: 'C')

//////////////////////////////
// Ph-Core 경로 상수
//////////////////////////////
define('PH_MOD_DIR',PH_DIR.'/mod'); //Module 경로
define('PH_MOD_PATH',PH_PATH.'/mod'); //Module PHP 경로
define('PH_PLUGIN_DIR',PH_DIR.'/plugin'); //Plugin 경로
define('PH_PLUGIN_PATH',PH_PATH.'/plugin'); //Plugin PHP 경로
define('PH_DATA_DIR',PH_DIR.'/data'); //Data PHP 경로
define('PH_DATA_PATH',PH_PATH.'/data'); //Data PHP 경로
define('PH_MANAGE_DIR',PH_DIR.'/manage'); //Manage 경로
define('PH_MANAGE_PATH',PH_PATH.'/manage'); //Manage PHP 경로

//////////////////////////////
// Ph-Core 개발 환경 상수
//////////////////////////////
//정규식 상수
define('REGEXP_EMAIL',"/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/"); //이메일
define('REGEXP_KOR',"/^[가-힣]+$/"); //한글
define('REGEXP_NUM',"/^[0-9]+$/"); //숫자
define('REGEXP_NEGANUM',"/^[0-9-]+$/"); //숫자 (음수 포함)
define('REGEXP_ENG',"/^[a-zA-Z_]+$/"); //영어
define('REGEXP_NICK',"/^[가-힣]+$/"); //이름
define('REGEXP_PHONE',"/^[0-9]+$/"); //연락처
define('REGEXP_ID',"/^[0-9a-z]+$/"); //ID
define('REGEXP_IDX',"/^[0-9a-zA-Z_]+$/"); //idx
define('REGEXP_IMG',"/<img[^>]*src=[\'']?([^>\'']+)[\'']?[^>]*>/i"); //이미지 추출

//오류문구 상수
define('ERR_MSG_1','정상적으로 접근 바랍니다. (ERR-CODE: err001)'); //비정상적인 방법으로 접근한 경우
define('ERR_MSG_2','사용할 수 없는 태그가 포함되어 있습니다. (ERR-CODE: err002)'); //사용 금지 태그를 사용한 경우
define('ERR_MSG_3','Database에 접속할 수 없습니다. (ERR-CODE: err003)'); //DB Connect가 불가한 경우
define('ERR_MSG_4','Database를 찾을 수 없습니다. (ERR-CODE: err004)'); //DB Select_db 가 불가한 경우
define('ERR_MSG_5','DB Query가 올바르지 않습니다. (ERR-CODE: err005)'); //DB Query 문법이 잘못된 경우
define('ERR_MSG_6','DB Select Query가 올바르지 않습니다. (ERR-CODE: err006)'); //DB Select Query 문법이 잘못된 경우
define('ERR_MSG_7','외부 SMTP 소켓 연결에 실패 했습니다. (ERR-CODE: err007)'); //외부 SMTP 소켓 연결에 실패한 경우
define('ERR_MSG_8','허용되지 않는 파일 유형입니다. (ERR-CODE: err008)'); //허용되지 않는 파일 유형인 경우
define('ERR_MSG_9','필수 변수 값이 전달되지 않았습니다. (ERR-CODE: err009)'); //필수 변수 값이 전달되지 않은 경우
define('ERR_MSG_10','접근 권한이 없습니다. (ERR-CODE: err010)'); //페이지 접근 권한이 없는 경우
define('ERR_MSG_11','set_category_key 설정 없이 사용할 수 없는 명령어가 있습니다. (ERR-CODE: err011)'); //$func->set_category_key() 없이 $func->page_title() 등을 호출하려는 경우
define('ERR_MSG_12','page_navigator 에서 카테고리 key를 확인할 수 없습니다. (ERR-CODE: err012)'); //$func->page_navigator() 에서 카테고리 key를 인증할 수 없는 경우

//경고문구 상수
define('SET_NODATA_MSG','데이터가 존재하지 않습니다.'); //데이터가 없는 경우 문구
define('SET_NOAUTH_MSG','로그인 후에 이용 가능합니다.'); //접근 권한이 없는 경우 문구
define('SET_ALRAUTH_MSG','이미 로그인 되어 있습니다.'); //접근 권한이 없는 경우 문구

//개발 옵션 상수
define('SET_MAX_UPLOAD',5242880); //Core 기본 업로드 byte
define('SET_SESS_LIFE',3600); //세션 유지 시간 (초 단위)
define('SET_INTDICT_TAGS','script,iframe,link,meta'); //사용 금지 태그
define('SET_INTDICT_FILE','html,htm,shtm,php,php3,asp,jsp,cgi,js,css,conf,dot'); //첨부 금지 확장명
define('SET_IMGTYPE','gif,jpg,jpeg,bmp,png'); //사용 가능한 모든 이미지 종류
define('SET_LIST_LIMIT',15); //리스트 기본 노출 갯수
define('SET_DATE','Y.m.d'); //날짜 출력 포멧
define('SET_DATETIME','Y.m.d H:i:s'); //날짜+시간 출력 포멧
define('SET_MOBILE_DEVICE','iphone,lgtelecom,skt,mobile,samsung,nokia,blackberry,android,android,sony,phone'); //모바일 디바이스 종류
define('SET_BLANK_IMG',PH_DOMAIN.'/layout/images/blank-tmb.jpg'); //이미지가 없는 경우 대체될 blank 썸네일 경로

//PHP ini 설정
ini_set('display_errors',1);
// ini_set('error_reporting','E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE');

//////////////////////////////
// Ph-Core 플러그인 상수
// define('플러그인 상수명','플러그인 폴더명');
//////////////////////////////
define('PH_PLUGIN_CAPCHA','capcha');
define('PH_PLUGIN_CKEDITOR','ckeditor4');
