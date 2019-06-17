<div id="sub-tit">
    <h2>온라인 문의</h2>
    <em><i class="fa fa-exclamation-circle"></i>온라인 문의 내역 확인 및 답변</em>
</div>

<!-- sorting -->
<div id="list-sort">
    <ul>
        <li><a href="<?=$manage->sortlink("")?>"><em>전체 문의</em><p><?=$contactform_total?></p></a></li>
    </ul>
</div>

<!-- article -->
<article>

    <form id="list-sch" action="./" method="get">
        <?=$manage->print_hidden_inp()?>

        <select name="where">
            <option value="name" <?=$manage->sch_where("name")?>>이름</option>
            <option value="article" <?=$manage->sch_where("article")?>>문의 내용</option>
            <option value="email" <?=$manage->sch_where("email")?>>이메일</option>
            <option value="phone" <?=$manage->sch_where("phone")?>>연락처</option>
        </select>
        <input type="text" name="keyword" class="keyword" value="<?=$keyword?>" placeholder="검색어를 입력하세요." />
        <button type="submit" class="btn1 small sbm"><i class="fa fa-search"></i>검색</button>
    </form>

    <table class="table1 list">
        <colgroup>
            <col style="width: 50px;" />
            <col style="width: 100px;" />
            <col style="width: 250px;" />
            <col style="width: 200px;" />
            <col style="width: auto;" />
            <col style="width: 150px;" />
            <col style="width: 100px;" />
            <col style="width: 100px;" />
        </colgroup>
        <thead>
            <tr>
                <th>No.</th>
                <th><a href="<?=$manage->orderlink("name")?>">이름</a></th>
                <th><a href="<?=$manage->orderlink("email")?>">이메일</a></th>
                <th><a href="<?=$manage->orderlink("phone")?>">연락처</a></th>
                <th class="tal">내용</th>
                <th><a href="<?=$manage->orderlink("regdate")?>">문의시간</a></th>
                <th>답변</th>
                <th>보기</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($print_arr as $list){
            ?>
            <tr>
                <td class="no tac"><?=$list['no']?></td>
                <td class="tac"><?=$list[0]['print_name']?></td>
                <td><?=$list['email']?></td>
                <td><?=$list['phone']?></td>
                <td><?=$list['article']?></td>
                <td class="tac"><?=$list['regdate']?></td>
                <td class="tac"><?=$list[0]['print_reply']?></td>
                <td class="tac">
                    <a href="./?mod=<?=MOD_CONTACTFORM?>&href=list&p=view&idx=<?=$list['idx']?><?=$manage->lnk_def_param()?>" class="btn1 small">보기</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- no data -->
    <?php if(!$print_arr){ ?>
    <p id="list-nodata"><?=SET_NODATA_MSG?></p>
    <?php } ?>

    <!-- paging -->
    <div id="list-paging">
    <?=$pagingprint?>
    </div>

</article>
