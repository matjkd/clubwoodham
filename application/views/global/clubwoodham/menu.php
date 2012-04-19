
<ul class="topnav">
    <li><?= anchor('/', 'Home') ?></li>
    <li><?= anchor('/gym', 'Gym') ?>

        <ul class="subnav">
            <li><?= anchor('/personaltraining', 'Personal Training') ?></li>
            <li><?= anchor('/goals', 'Achieving your goals') ?></li>
        </ul>
    </li>
    <li><?= anchor('/studio', ' Studio') ?></li>
    <li><?= anchor('/bar-restaurant', 'Bar/Restaurant') ?></li>

    <li><?= anchor('squash', 'Squash') ?></li>
    <li><?= anchor('juniors', 'Juniors') ?></li>
    <li><?= anchor('Seniors', 'seniors') ?></li>
    <li><?= anchor('circuit-gym', 'Circuit Gym') ?></li>
    <li><?= anchor('membership', 'Membership') ?></li>
    <li><?= anchor('/contact', 'contact us') ?></li>


    <?php
    $is_logged_in = $this->session->userdata('is_logged_in');
    $role = $this->session->userdata('role');
    if ($is_logged_in != 0 || $role == 1) {

        echo anchor('admin', 'Admin');
    }
    ?>

</ul>

