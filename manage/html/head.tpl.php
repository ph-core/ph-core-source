<header id="header">
    <a href="<?php echo PH_DIR; ?>/manage/" class="logo">
        <h1>Ph-Core Manager</h1>
    </a>
    <ul id="tnb">
        <li><a href="<?php echo $manage->gosite(); ?>">웹사이트</a></li>
        <?php if($MB['adm']=="Y"){ ?>
            <li><a href="<?php echo $manage->adminfo_link(); ?>">관리자 정보 변경</a></li>
        <?php } ?>
        <li><a href="<?php echo $manage->signout_link(); ?>">로그아웃</a></li>
    </ul>
</header>

<div id="wrap">
    <div id="side">

        <ul class="tab">
            <li class="on"><a href="#" data-tab="def"><i class="fa fa-cog"></i>기본메뉴</a></li>
            <li><a href="#" data-tab="mod"><i class="fa fa-box"></i>모듈<em><?php echo $manage->module_total(); ?></em></a></li>
        </ul>

        <div id="gnb">

            <!-- 기본 메뉴 -->
            <ul class="menu">
                <li>
                    <a href="#">기본 관리도구</a>
                    <ul>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=siteinfo">기본정보 관리</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=metaconf">검색엔진 최적화</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=sitemap">사이트맵 관리</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">테마&amp;모듈</a>
                    <ul>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=theme">테마 설정</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=module">설치된 모듈</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">회원관리</a>
                    <ul>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=mblist">가입 회원 관리</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=addmb">신규 회원 추가</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=mbleave">탈퇴 회원</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=mbvisit">회원 접속 기록</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=mbstat">현재 접속 세션</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=mbpoint">포인트 관리</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">팝업</a>
                    <ul>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=poplist">생성된 팝업</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=makepop">신규 팝업 생성</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">배너</a>
                    <ul>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=bnlist">생성된 배너</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=makebn">배너 생성</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">메일발송</a>
                    <ul>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=mailtpl">메일 템플릿 관리</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=sendmail">회원 메일 발송</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=mailhis">메일 발송 내역</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">접속 차단</a>
                    <ul>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=blockip">아이피 접속 차단</a></li>
                        <li><a href="<?php echo PH_MANAGE_DIR; ?>/?href=blockmb">회원 접속 차단</a></li>
                    </ul>
                </li>
            </ul>

            <!-- 모듈 -->

            <ul class="menu">
                <?php
                foreach($gnb_arr as $key => $value){
                ?>
                <li>
                    <a href="#"><?php echo $gnb_arr[$key]['name']; ?></a>
                    <ul>
                        <?php
                        foreach($gnb_arr[$key] as $key2 => $value2){
                            if(is_int($key2)){
                                ?>
                                <li><a href="<?php echo PH_MANAGE_DIR."/?mod=".$gnb_arr[$key]['mod']."&href=".$gnb_arr[$key][$key2]['href']; ?>"><?php echo $gnb_arr[$key][$key2]['title']; ?></a></li>
                            <?php }} ?>
                        </ul>
                    </li>
                <?php } ?>
                </ul>

            </div>
        </div>

        <div id="content">
