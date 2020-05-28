<?php
$SCHEME_CORE = "

CREATE TABLE IF NOT EXISTS {$req['pfx']}banner (
    idx int(11) NOT NULL auto_increment,
    bn_key varchar(255) BINARY NOT NULL,
    pc_img text,
    mo_img text,
    title varchar(255) NOT NULL,
    link text NOT NULL,
    link_target varchar(255) default NULL,
    hit int(11) NOT NULL default '0',
    zindex int(11) NOT NULL default '1',
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$req['pfx']}banner (idx, bn_key, pc_img, mo_img, title, link, link_target, hit, zindex, regdate) VALUES
(1, 'test_banner', '', '', 'test banner', 'http://www.ph-core.com', '_self', 0, 1, now());

CREATE TABLE IF NOT EXISTS {$req['pfx']}blockmb (
    idx int(11) NOT NULL auto_increment,
    ip varchar(255) default NULL,
    mb_idx int(11) default NULL,
    mb_id varchar(255) default NULL,
    memo text NOT NULL,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mailtpl (
    idx int(11) NOT NULL auto_increment,
    type varchar(255) BINARY default NULL,
    title varchar(255) default NULL,
    html text,
    system char(1) NOT NULL default 'N',
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO {$req['pfx']}mailtpl (idx, type, title, html, system, regdate) VALUES
(1, 'signup', '회원가입 인증 자동 발송 메일', '<p>안녕하세요?<br />\n<strong>{{name}}</strong> 님.<br />\n회원가입 완료를 위해&nbsp;<strong>{{site_title}}</strong> 이메일 인증 부탁 드립니다.<br />\n<br />\n아래 링크를 클릭해 주세요.<br />\n<br />\n<span style=\"color:#3498db\">{{check_url}}</span></p>\n', 'Y', '2019-03-11 00:00:00'),
(2, 'forgot', '로그인 정보 자동 발송 메일', '<p>안녕하세요?<br />\n<strong>{{name}}</strong> 님.<br />\n<strong>{{site_title}}</strong> 회원 로그인 정보를 보내드립니다.<br />\n<br />\n로그인 ID : <strong>{{id}}</strong><br />\n임시 비민번호 : <strong>{{password}}</strong></p>\n', 'Y', '2019-03-11 00:00:00'),
(3, 'default', '기본 템플릿', '<p><span style=\"color:#999999\">이 메일은&nbsp;<strong>{{site_title}}</strong> 에서 발송된 메일입니다.</span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>{{article}}</p>\r\n', 'Y', now());

CREATE TABLE IF NOT EXISTS {$req['pfx']}mbchk (
    chk_idx int(11) NOT NULL auto_increment,
    mb_idx int(11) NOT NULL,
    chk_code text,
    chk_mode varchar(255) NOT NULL default 'chk',
    chk_chk char(1) default 'N',
    chk_regdate datetime default NULL,
    chk_dregdate datetime default NULL,
    PRIMARY KEY  (chk_idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mbpoint (
    idx int(11) NOT NULL auto_increment,
    mb_idx int(11) NOT NULL,
    p_in int(11) default NULL,
    p_out int(11) default NULL,
    memo text,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}member (
    mb_adm char(1) default 'N',
    mb_idx int(11) NOT NULL auto_increment,
    mb_id varchar(255) BINARY NOT NULL,
    mb_email varchar(255) NOT NULL,
    mb_pwd text NOT NULL,
    mb_name varchar(255) default NULL,
    mb_level int(11) default '9',
    mb_gender char(1) default 'M',
    mb_phone varchar(255) default NULL,
    mb_telephone varchar(255) default NULL,
    mb_lately datetime default NULL,
    mb_lately_ip varchar(255) default NULL,
    mb_point int(11) default '0',
    mb_email_chk char(1) default 'N',
    mb_email_chg varchar(255) NOT NULL,
    mb_regdate datetime default NULL,
    mb_dregdate datetime default NULL,
    mb_1 text,
    mb_2 text,
    mb_3 text,
    mb_4 text,
    mb_5 text,
    mb_6 text,
    mb_7 text,
    mb_8 text,
    mb_9 text,
    mb_10 text,
    mb_exp text NOT NULL,
    PRIMARY KEY  (mb_idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}mng_feeds (
    idx int(11) NOT NULL auto_increment,
    msg_from text collate utf8_bin,
    href text collate utf8_bin,
    memo text collate utf8_bin,
    regdate datetime default NULL,
    chked char(1) collate utf8_bin default 'N',
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}popup (
    idx int(11) NOT NULL auto_increment,
    id varchar(255) BINARY NOT NULL,
    title varchar(255) NOT NULL,
    link text,
    link_target varchar(255) default NULL,
    width int(11) default NULL,
    height int(11) default NULL,
    pos_left int(11) default NULL,
    pos_top int(11) default NULL,
    level_from int(11) default NULL,
    level_to int(11) default NULL,
    show_from datetime default NULL,
    show_to datetime default NULL,
    html text,
    mo_html text,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}sentmail (
    idx int(11) NOT NULL auto_increment,
    template varchar(255) BINARY default NULL,
    to_mb varchar(255) default NULL,
    level_from int(11) default NULL,
    level_to int(11) default NULL,
    subject text,
    html text,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}session (
    idx int(11) NOT NULL auto_increment,
    sesskey text NOT NULL,
    expiry int(11) NOT NULL,
    value text,
    mb_idx int(11) default '0',
    ip varchar(255) default NULL,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$req['pfx']}siteconfig (
    st_idx int(11) NOT NULL,
    st_title varchar(255) default NULL,
    st_domain text,
    st_description text,
    st_use_mobile char(1) default 'Y',
    st_use_emailchk char(1) NOT NULL default 'Y',
    st_use_recaptcha char(1) NOT NULL default 'N',
    st_recaptcha_key1 text,
    st_recaptcha_key2 text,
    st_email varchar(255) default NULL,
    st_tel varchar(255) default NULL,
    st_favicon text,
    st_logo text,
    st_mb_division text,
    st_og_type varchar(255) NOT NULL default 'website',
    st_og_title text,
    st_og_description text,
    st_og_image text,
    st_og_url text,
    st_naver_verific text,
    st_google_verific text,
    st_theme varchar(255) default 'ph-default',
    st_use_smtp char(1) NOT NULL default 'N',
    st_smtp_server varchar(255) default 'ssl\\:\\/\\/',
    st_smtp_port char(10) default NULL,
    st_smtp_id text,
    st_smtp_pwd text,
    st_script text,
    st_meta text,
    st_privacy text,
    st_policy text,
    st_1 text,
    st_2 text,
    st_3 text,
    st_4 text,
    st_5 text,
    st_6 text,
    st_7 text,
    st_8 text,
    st_9 text,
    st_10 text,
    st_exp text NOT NULL,
    PRIMARY KEY  (st_idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$req['pfx']}siteconfig (st_idx, st_title, st_domain, st_description, st_use_mobile, st_use_emailchk, st_email, st_tel, st_favicon, st_logo, st_mb_division, st_og_type, st_og_title, st_og_description, st_og_image, st_og_url, st_naver_verific, st_google_verific, st_theme, st_use_smtp, st_smtp_server, st_smtp_port, st_smtp_id, st_smtp_pwd, st_script, st_meta, st_privacy, st_policy, st_1, st_2, st_3, st_4, st_5, st_6, st_7, st_8, st_9, st_10, st_exp) VALUES
(0, '테스트사이트', '".$protocol.$_SERVER['HTTP_HOST'].$realdir."', '테스트사이트입니다.', 'Y', 'Y', '', '', '', '', '최고관리자|관리자|게시판관리자|정회원|정회원|정회원|정회원|정회원|일반회원|비회원', 'website', '테스트사이트', '테스트사이트 설명', '', '".$protocol.$_SERVER['HTTP_HOST'].$realdir."', '', '', 'ph-default', 'N', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '|||||||||');

CREATE TABLE IF NOT EXISTS {$req['pfx']}sitemap (
    idx int(11) NOT NULL,
    caidx text collate utf8_bin,
    title varchar(255) collate utf8_bin default NULL,
    href text collate utf8_bin,
    visible char(1) collate utf8_bin NOT NULL default 'Y',
    children int(11) NOT NULL default '0',
    PRIMARY KEY  (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$req['pfx']}sitemap (idx, caidx, title, href, visible, children) VALUES
(1, '0001', 'Normal page', 'subpage/normalpage', 'Y', 0),
(2, '0002', 'Contents', 'subpage/contents', 'Y', 0),
(3, '0003', 'Board', 'subpage/news', 'Y', 2),
(4, '00030001', 'News', 'subpage/news', 'Y', 0),
(5, '00030002', 'Freeboard', 'subpage/freeboard', 'Y', 0),
(6, '0004', 'Contact Us', 'subpage/contactus', 'Y', 0);

CREATE TABLE IF NOT EXISTS {$req['pfx']}visitcount (
    idx int(11) NOT NULL auto_increment,
    mb_idx int(11) default NULL,
    mb_id varchar(255) default NULL,
    ip varchar(255) default NULL,
    device varchar(255) default NULL,
    browser varchar(255) default NULL,
    regdate datetime default NULL,
    PRIMARY KEY  (idx)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

";
