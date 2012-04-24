<!--Main content page for club woodham site-->

<div style=" padding-left:10px;">
<?php foreach ($content as $row): ?>
  <!--add image if set-->

    <h1><?= $row->title ?></h1>

    <?php
    $is_logged_in = $this->session->userdata('is_logged_in');
    if (!isset($is_logged_in) || $is_logged_in == true) {
        echo "<a href='" . base_url() . "admin/edit/" . $row->content_id . "'>edit this page</a><br/>";
    }
    ?>

    <?php
    if (isset($age)) {
        $body = str_replace("[age]", "$age", "$row->content");
    } else {
        $body = $row->content;
    }
    ?>


    <?php $body = str_replace("Club Woodham", "<strong>Club Woodham</strong>", "$body"); ?>


  

    <?= $body ?>

<?php endforeach; ?>


<?php foreach ($content as $row): ?>
    <?php if ($row->extra != NULL) { ?>
        <?= $this->load->view('extra/' . $row->extra) ?>
    <?php } ?>
<?php endforeach; ?>
    
  </div>