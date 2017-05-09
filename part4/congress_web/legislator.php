<?php

include_once('config.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    http_response_code(404);
    $Title = "Error 404, Legislator not found";
    include('header.php');
    include('404.php'); // provide your own HTML for the error page
    include('footer.php');
    die();
}

$stmt = $db->prepare("select govtrack_id, bioguide_id, `First Name`, `Last Name`, birthday, gender from Legislator WHERE bioguide_id=?");

$stmt->bind_param("s", $id); 

$stmt->execute();

$stmt->store_result();

$stmt->bind_result($govtrack_id, $bioguide_id, $first_name, $last_name, $birthday, $gender);

$columnname = array('Photo', 'Name', 'State', 'Chamber');

if (!$stmt->fetch()) {
    http_response_code(404);
    $Title = "Error 404, Legislator not found";
    include('header.php');
    include('404.php'); // provide your own HTML for the error page
    include('footer.php');
    die();
}

$Title = $first_name . " " . $last_name;

include('header.php');?>


<div class="row">
    <div class="twelve column">
        <dl>
            <dt>photo</dt>
            <?php if (is_file("photos/" . $govtrack_id . "-200px.jpeg")) { ?>
            <dd><img src="<?= "photos/" . $govtrack_id . "-200px.jpeg"?>" alt="" /></dd>
            <?php } else { ?>
            <dd><img src="<?= "photos/placeholder-200px.jpeg"?>" alt="" /></dd>
            <?php } ?>
            <dt>birthday</dt>
            <dd><?= $birthday ?></dd>
            <dt>gender</dt>
            <dd><?= $gender ?></dd>
            <dt>Terms</dt>
            <dd>
            <?php 
            $substmt = $db->prepare("SELECT start, end, type, state, district, party, chamber FROM Term WHERE bioguide_id=? ORDER BY end DESC");

            $substmt->bind_param("s", $id);

            $substmt->execute();

            $substmt->store_result();

            $substmt->bind_result($start,$end, $type, $state, $district, $party, $chamber);
            $columnname = array('start', 'end', 'type', 'state', 'district', 'party', 'chamber');

            ?>
            <table>
                <thead>
                    <tr>
                        <th>start</th>
                        <th>end</th>
                        <th>type</th>
                        <th>state</th>
                        <th>district</th>
                        <th>party</th>
                        <th>chamber</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($substmt->fetch()) { ?>
                    <tr>
                        <td><?= $start ?></td>
                        <td><?= $end ?></td>
                        <td><?= $type ?></td>
                        <td><?= $state ?></td>
                        <td><?= $district ?></td>
                        <td><?= $party ?></td>
                        <td><?= $chamber ?></td>
                    </tr>
                <?php } //end while ?>
                </tbody>
            </table>
            
            <?php
            $substmt->close();
            ?>
            </dd>
        </dl>
    </div>
</div>

<?php include('footer.php'); ?>