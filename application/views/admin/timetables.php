<div style=" padding-left:10px;">
<?php foreach ($content as $row): ?>
    <h1><?= $row->title ?></h1>
    <?php endforeach; ?>
<?php 
$count = 0;
?>

  <?php if($timetable != NULL) { foreach ($timetable as $row2): ?>

<?php

if($row2->day == 1) { $daysarray['1'] = 'Monday'; }
if($row2->day == 2) { $daysarray['2'] = 'Tuesday'; }
if($row2->day == 3) { $daysarray['3'] = 'Wednesday'; }
if($row2->day == 4) { $daysarray['4'] = 'Thursday'; }
if($row2->day == 5) { $daysarray['5'] = 'Friday'; }
if($row2->day == 6) { $daysarray['5'] = 'Saturday'; }
if($row2->day == 7) { $daysarray['5'] = 'Sunday'; }

?>



<?php endforeach; ?>



<?php foreach($daysarray as $days):?>
<?php $count = $count+1;?>

<h4><?=$days?> </h4>
<table class="timetable" id="box-table-a">
    <thead>
        <tr>
            <th>Time</th>
            <th>Class</th>
            <th>Instructor</th>
            <th>Level</th>
            <th>Location</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($timetable as $row): ?>

            <?php if ($row->day == $count) { ?>

               
                <tr>
                    <td> <?= $row->from ?> - <?= $row->to ?></td>
                    <td> <?= $row->class ?></td>
                    <td><?= $row->instructor ?></td>
                    <td><?= $row->level ?></td>
                    <td><?= $row->where ?></td>
                </tr>

            <?php } ?>

        <?php endforeach; ?>
    </tbody>
</table>
<?php endforeach; } else { echo "No timetable data has been added yet."; } ?>
</div>
<!--Main content page for club woodham site-->

<div style=" padding-left:10px;">
<?php foreach ($content as $row): ?>



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