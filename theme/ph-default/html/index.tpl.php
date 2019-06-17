<?php
// 팝업 출력
$this->popup_fetch();
?>

<div class="vis">
    <div class="in">
        <h3><strong>Hello</strong> World!</h3>
        <p>
            Ph-Core는 가볍고 빠르지만 강력한 PHP MVC 기반의 CMS 입니다.<br />
            쉽고 빠르게 반응형 웹사이트를 구축하세요.
        </p>
    </div>
</div>

<div class="lat-wrap">

    <div class="lat">
        <a href="/subpage/news" class="more"><i class="fa fa-plus"></i><p>더보기</p></a>
        <?php
        // 최근게시물 출력
        $this->latest_fetch();
        ?>
    </div>

    <div class="lat">
        <a href="/subpage/freeboard" class="more"><i class="fa fa-plus"></i><p>더보기</p></a>
        <?php
        // 최근게시물 출력
        $this->latest_fetch2();
        ?>
    </div>

</div>

<div class="mid-bn">
    <?php
    // 배너 출력
    $this->banner_fetch();
    ?>
</div>
