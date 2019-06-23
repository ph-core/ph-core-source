<header id="header">
    <div class="inner">

        <a href="<?php echo $this->layout->site_href(); ?>" class="logo">
            <h1><img src="<?php echo $this->layout->logo_src(); ?>" alt="<?php echo $this->layout->logo_title(); ?>"></h1>
        </a>

        <ul id="tnb">
            <?php if(!IS_MEMBER){ ?>
            <li><a href="<?php echo $this->layout->signin_href(); ?>">회원로그인</a></li>
            <li><a href="<?php echo $this->layout->site_href(); ?>/member/signup">회원가입</a></li>
            <?php }else{ ?>
            <li><a href="<?php echo $this->layout->site_href(); ?>/member/signout">로그아웃</a></li>
            <li><a href="<?php echo $this->layout->site_href(); ?>/member/mbinfo">회원정보</a></li>
            <?php if($MB['level']==1){ ?>
            <li><a href="<?php echo $this->layout->site_href(); ?>/manage/">Manage</a></li>
            <?php } ?>
            <?php } ?>
        </ul>

    </div>

    <nav>
        <ul id="gnb">
            <?php foreach($SITEMAP as $gnb){ ?>
            <li>
                <a href="<?php echo $gnb['href']; ?>" data-idx="<?php echo $gnb['idx']; ?>"><?php echo $gnb['title']; ?></a>
                <?php if(count($gnb['2d'])>0){ ?>
                <ul>
                    <?php foreach($gnb['2d'] as $gnb2){ ?>
                    <li>
                        <a href="<?php echo $gnb2['href']; ?>" data-idx="<?php echo $gnb2['idx']; ?>"><?php echo $gnb2['title']; ?></a>
                        <?php if(count($gnb2['3d'])>0){ ?>
                        <ul>
                            <?php foreach($gnb2['3d'] as $gnb3){ ?>
                            <li><a href="<?php echo $gnb3['href']; ?>" data-idx="<?php echo $gnb3['idx']; ?>"><?php echo $gnb3['title']; ?></a></li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </li>
            <?php } ?>
        </ul>
    </nav>

    <a href="#" id="slide-btn">
        <button><span></span></button>
        Open menu
    </a>

</header>

<!-- for mobile -->
<div id="slide-menu">
    <div class="inner">
        <ul id="mo-tnb">
            <?php if(!IS_MEMBER){ ?>
            <li><a href="<?php echo $this->layout->signin_href(); ?>">회원로그인</a></li>
            <li><a href="/member/signup">회원가입</a></li>
            <?php }else{ ?>
            <li><a href="/member/signout">로그아웃</a></li>
            <li><a href="/member/mbinfo">회원정보</a></li>
            <?php } ?>
        </ul>

        <ul id="mo-gnb">
            <?php foreach($SITEMAP as $gnb){ ?>
            <li>
                <a href="<?php echo $gnb['href']; ?>" data-idx="<?php echo $gnb['idx']; ?>"><?php echo $gnb['title']; ?></a>
                <?php if(count($gnb['2d'])>0){ ?>
                <ul>
                    <?php foreach($gnb['2d'] as $gnb2){ ?>
                    <li>
                        <a href="<?php echo $gnb2['href']; ?>" data-idx="<?php echo $gnb2['idx']; ?>"><?php echo $gnb2['title']; ?></a>
                        <?php if(count($gnb2['3d'])>0){ ?>
                        <ul>
                            <?php foreach($gnb2['3d'] as $gnb3){ ?>
                            <li><a href="<?php echo $gnb3['href']; ?>" data-idx="<?php echo $gnb3['idx']; ?>"><?php echo $gnb3['title']; ?></a></li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
<div id="slide-bg"></div>

<?php if(defined("MAINPAGE")){ ?>
<!-- Main page -->
<div id="main">
    <div id="content">

<?php }else{ ?>
<!-- Sub page -->
<div id="sub">
    <div id="content">

        <?php if($NAVIGATOR){ ?>
        <div id="sub-tit">

            <h2><?php echo $NAVIGATOR[count($NAVIGATOR)-1]['title']; ?></h2>

            <ul id="navi">
                <li>
                    <a href="<?php echo $this->layout->site_href(); ?>/"><i class="fa fa-home"></i></a>
                </li>
                <?php foreach($NAVIGATOR as $navigt){ ?>
                <li>
                    <i class="fa fa-angle-right"></i>
                    <a href="<?php echo $this->layout->site_href(); ?>/<?php echo $navigt['href']; ?>"><?php echo $navigt['title']; ?></a>
                </li>
                <?php } ?>
            </ul>

        </div>
        <?php } ?>

<?php } ?>
