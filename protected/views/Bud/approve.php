<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/jquery.steps.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/jquery-checktree.css" rel="stylesheet" type="text/css"/>

<div class="container" id="approve">
    <h3 style="text-align: center">การยืนยันข้อมูล<?=$roundword?> ประจำปี พ.ศ. <?=$year?></h3>
    <div id='approvesteps' round="<?=$round?>">
        <?php foreach($div as $rowdiv){
            if(!Yii::app()->user->isAdmin){
                ?><h3><?=$dep[0]['pname']?></h3>
                <section>
                    <?php foreach ($dep as $rowdep){ 
                        $check = $rowdep['state1']==0||($rowdep['state1']==3&&$rowdep['state2']==0)?'':'checked';
                        $disable = $rowdep['state1']>0||($rowdep['state1']==3&&$rowdep['state2']>0)||$rowdep['state1']==NULL?'disabled':'';
                        ?>
                    <label><input type="checkbox" <?=$check?> <?=$disable?> id="d-<?=$rowdep['cid']?>"/>&nbsp;<?=$rowdep['cname']?></label>
                    <span class="text-danger"><?=$disable == 'disabled'?'ยังไม่มีการกรอกข้อมูล':''?></span><br/>
                    <?php }?>
                </section>
                <?php break;
            }else{
                ?><h3><?=$rowdiv['name']?></h3>
                <section>
                    <?php foreach ($dep as $rowdep){ 
                        if($rowdiv['id'] != $rowdep['pid'])
                            continue;
                        $check = $rowdep['state1']<=1||($rowdep['state1']==3&&$rowdep['state2']<=1)?'':'checked';
                        $disable = $rowdep['state1']<=1||($rowdep['state1']==3&&$rowdep['state2']<=1)||$rowdep['state1']==NULL?'disabled':'';
                        ?>
                    <label><input type="checkbox" <?=$check?> <?=$disable?> id="d-<?=$rowdep['cid']?>"/>&nbsp;<?=$rowdep['cname']?></label>
                    <span class="text-danger"><?=$disable == 'disabled'?'ฝ่ายยังไม่ยืนยันข้อมูล':''?></span><br/>
                    <?php }?>
                </section>
            <?php }
        }?>
    </div>
</div>

<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery.steps.min.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery-checktree.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/approve.js" type="text/javascript"></script>