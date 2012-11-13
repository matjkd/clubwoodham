<?= form_open_multipart("admin/submit_content") ?> 
<?php 
$format = 'l jS \of F Y';
$startdate = date($format, $row->start_publish);
$enddate = date($format, $row->end_publish);

?>
<p>
    Title:<br/>
    <?= form_input('title', set_value('title')) ?>
</p>

<p>
    Menu link (not required field):<br/>
    <?= form_input('menu', set_value('menu')) ?>
</p>

Start Date (this item will not show on site until this date):<br/>
	The news will be ordered by this date too. Latest at the top. Contact Hotegg if you want earliest at the top<br/>
 <input type="text" id="datepicker" name="startdate" value="<?=$startdate?>"/>
<input type="hidden" id="alternate" name="startdate_unix" value="<?=$row->start_publish?>"/>
<br/>
End Date (after this date passes, the item will not show on site)<br/>
<input type="text" id="datepicker2" name="enddate" value="<?=$enddate?>"/>
<input type="hidden" id="alternate2" name="enddate_unix" value="<?=$row->end_publish?>"/><br/>

<?php
if (!isset($category)) {
    $category = "";
}
?>

<p>
    Category:<br/>
    <input type="text" name="category"  value="<?= set_value('category', $category) ?>"  disable="disabled" onFocus="this.blur();"><br/>
</p>



<p class="Image">
    <?= form_label('Image') ?> <br/>

<?= form_upload('file') ?>
</p>

<?php if ($category == "gallery") { ?>

    <p>
        Gallery:<br/>

        <?php
        $options = array(
            'driveways' => 'driveways',
            'landscapes' => 'Landscapes',
            'outdoor_buildings' => 'Outdoor Buildings',
            'patios' => 'Patios',
            'ponds_and_pools' => 'Ponds and Pools',
            'wallsgatesrailings' => 'Walls Gates Railings',
            'artists_impressions' => 'Artists Impressions',
              'orangeries' => 'Orangeries',
        );
        ?>
    <?= form_dropdown('gallery', $options) ?>
    </p>

<?php } ?>

<p>
    Content:<br/>
    <textarea cols=75 rows=20 name="content" id="content"  class='wymeditor'></textarea>

</p>
<input type="submit" name="upload" class="wymupdate" />

<?= form_close() ?> 
