<?php if(count($rows)):?>
    <?php foreach($rows as $row):?>
        <p class="text-left" onclick="set_code('<?=$row['contractor_id']?>')"><?=$row['contractor_name']."(".$row['contractor_id'].")"?></p>
    <?php endforeach;?>
<?php else:?>
    <p class="text-left"><?php echo Yii::t('common','no record');?></p>
<?php endif;?>