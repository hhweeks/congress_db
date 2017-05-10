<?php
session_start();

$Title = 'Add Legislator';

include('header.php') ?>

<?php if ((!isset($_SESSION['login'])) || $_SESSION['login'] != true) { ?>

<div class="row">
    <div class="twelve columns">
    <h4>This page is only for admins please login.</h4>    
    </div>

</div>

<?php } else if (isset($_POST['submit'])) {

    $bioguide_min_invalid = 'A000000';
    $bioguide_max_invalid = 'Z999999';

    if (isset($_POST['bioguide_id']) &&
        isset($_POST['old_start']) &&
        isset($_POST['old_end']) &&
        isset($_POST['new_start']) &&
        isset($_POST['new_end']))
    {

        $bioguide_id = $_POST['bioguide_id'];
        $old_start = date_create_from_format('Y-m-d', $_POST['old_start']);
        $old_end = date_create_from_format('Y-m-d', $_POST['old_end']);
        $new_start = date_create_from_format('Y-m-d', $_POST['new_start']);
        $new_end = date_create_from_format('Y-m-d', $_POST['new_end']);

        if (!($old_start && $old_end && $new_start && $new_end))
        {
            echo "<h4>Form Incorrect</h4>";
                    echo '<a class="button" href="change-term.php">Retry</a>';

            include('footer.php');
            die();
        }
    } else {
        echo "<h4>Form incorrect<h4>";
        echo '<a class="button" href="change-term.php">Retry</a>';
        include 'footer.php';
        die();
    }

    ?>

    Submitted with
    <?php

    $old_start = date_format($old_start, 'Y-m-d');
    $old_end = date_format($old_end, 'Y-m-d');
    $new_start = date_format($new_start, 'Y-m-d');
    $new_end = date_format($new_end, 'Y-m-d');

    echo "<p>" . $bioguide_id . "</p>";
    echo "<p>" . $old_start . "</p>";
    echo "<p>" . $old_end . "</p>";
    echo "<p>" . $new_start . "</p>";
    echo "<p>" . $new_end . "</p>";

    $stmt = $db->prepare("SELECT count(bioguide_id) FROM Term WHERE bioguide_id=? AND start=? AND end=? limit 1;");

    $stmt->bind_param("sss", $bioguide_id, $old_start, $old_end);

    $stmt->execute();

    $stmt->bind_result($count);

    $stmt->fetch();

    $stmt->close();
    if ($count == 0) {
        echo "<h4>Term not found</h4>";
        echo '<a class="button" href="change-term.php">Retry</a>';
        include('footer.php');
        die();
    }

    $stmt = $db->prepare("UPDATE Term SET start=?, end=? WHERE bioguide_id=? AND start=? AND end=?;");

    $stmt->bind_param("sssss", $new_start, $new_end, $bioguide_id, $old_start, $old_end);

    if ($stmt->execute()) {
        echo "<h4>Term updated</h4>";
    }

    $stmt->close();

    ?>

<?php } else { ?>

<form name='input' action='add-legislator.php' method='post'>
    <label for='bioguide_id'>bioguide id</label><input type='text' id='bioguide_id' placeholder='biogude id' name='bioguide_id' />
    <label for='last_name'>Last Name</label><input type='text' id='last_name' placeholder='Last Name' name='last_name' />
    <label for='first_name'>First Name</label><input type='text' id='first_name' placeholder='First Name' name='first_name' />
    <label for='govtrack_id'>govtrack_id</label><input type='text' id='govtrack_id' placeholder='unique number' name='govtrack_id' />
    <input type='submit' value='Add Legislator' name='submit' />
</form>


<?php } ?>
<?php include('footer.php') ?>