<?php foreach($content as $row):
$format = 'l jS \of F Y';
$startdate = date($format, $row->start_publish);
$enddate = date($format, $row->end_publish);
$id = $row->content_id;?>


<?=form_open_multipart("admin/edit_content/$row->content_id")?> 
<p>
Title* <br/><?=form_input('title', $row->title)?><br/>
</p>



<?php if($row->category == 'news'){ ?>
	
	Start Date (this item will not show on site until this date):<br/>
	The news will be ordered by this date too. Contact Hotegg if you want the order reversed<br/>
 <input type="text" id="datepicker" name="startdate" value="<?=$startdate?>"/>
<input type="hidden" id="alternate" name="startdate_unix" value="<?=$row->start_publish?>"/>
<br/>
End Date (after this date passes, the item will not show on site)<br/>
<input type="text" id="datepicker2" name="enddate" value="<?=$enddate?>"/>
<input type="hidden" id="alternate2" name="enddate_unix" value="<?=$row->end_publish?>"/><br/>
<p>
Show on Frontpage <br/><?=form_checkbox('frontpage', '1', $row->frontpage)?><br/>
</p>
<?php } ?>
<?=form_hidden('menu', $row->menu)?>


 <?php if($row->news_image != NULL) { ?>
<img src="https://s3-eu-west-1.amazonaws.com/clubwoodham/<?=$row->news_image?>" style="padding:10px 10px 10px 0;" width="150px">
<?php } ?>
<p class="Image">
    <?= form_label('Image') ?> (not required field)<br/>

<?= form_upload('file') ?>
</p>

<?php if ($row->category == "gallery") { ?>

    <p>
        Gallery:<br/>

        <?php
        $options = array(
            'gallery' => 'gallery'
        );
        ?>
    <?= form_dropdown('gallery', $options, $row->gallery) ?>
    </p>

<?php } ?>




<br/>
<textarea cols=65 rows=20 name="content" id="content" class='wymeditor'><?=$row->content?></textarea>
<br/>


<strong>None of the fields below are required</strong>
<hr/>
Meta Description<br/>
<textarea  cols=65 rows=2 name="meta_desc"><?=$row->meta_desc?></textarea>
<br/>
Meta Keywords<br/>
<textarea  cols=65 rows=2 name="meta_keywords"><?=$row->meta_keywords?></textarea>
<br/>
Meta Title<br/>
<textarea  cols=65 rows=2 name="meta_title"><?=$row->meta_title?></textarea>
<br/>

Extra: 
<br/><?=form_input('extra', $row->extra)?><br/>
Sidebox:
<br/><?=form_input('sidebox', $row->sidebox)?><br/>

Slideshow:
<br/><?=form_input('slideshow', $row->slideshow)?><br/>
<input type="submit" class="wymupdate" />
<?=form_close()?> 
<?php endforeach;?>