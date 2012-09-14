<div>
    
<?php if ($news != NULL) {
    foreach ($news as $row): ?>
<div class="newsDiv">
        <div class="newsImage">
        	<?php if($row->news_image != NULL) { ?>}
            <img  src="https://s3-eu-west-1.amazonaws.com/clubwoodham/<?= $row->news_image ?>"/>
            <?php } ?>
        </div>

        <div class="newsContent">
            <h1><?= $row->title ?>
            <?php
            $is_logged_in = $this->session->userdata('is_logged_in');
            if (!isset($is_logged_in) || $is_logged_in == true) {
                echo " - <a href='" . base_url() . "admin/edit/" . $row->content_id . "'>edit</a><br/>";
            }
            ?></h1>
            <?= $row->content ?>
        </div>
        <div style="clear:both">
        </div>
</div>
    <?php endforeach;
} ?>

</div>

