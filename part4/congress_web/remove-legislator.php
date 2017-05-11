<?php
session_start();

$Title = 'Remove Legislator';

include('header.php') ?>

<?php if ((!isset($_SESSION['login'])) || $_SESSION['login'] != true) { ?>

<div class="row">
    <div class="twelve columns">
    <h4>This page is only for admins please login.</h4>    
    </div>

</div>

<?php } else if (isset($_POST['submit'])) {

    if (isset($_POST['bioguide_id']))
    {
        $bioguide_id = $_POST['bioguide_id'];
    } else {
        echo "<h4>Form incorrect<h4>";
        echo '<a class="button" href="remove-legislator.php">Retry</a>';
        include 'footer.php';
        die();
    }

    ?>

    Submitted with
    <?php

    echo "<p>" . $bioguide_id . "</p>";
    $stmt = $db->prepare("delete from Legislator where bioguide_id=?;");

    $stmt->bind_param("s", $bioguide_id);

    if (!$stmt->execute()) {
        echo "<h4>Could note be deleted</h4>";
        echo "<p>Legislator cannot have a term or have participated in a vote to be deleted</p>";
        echo '<a class="button" href="remove-legislator.php">Retry</a>';
        include('footer.php');
        die();
    }
    
    $stmt->close();

    ?>

    <h4>Legislator Deleted</h4>

<?php } else { ?>

<form name='input' action='remove-legislator.php' method='post'>
    <label for='bioguide_id'>bioguide id</label><input type='text' id='bioguide_id' placeholder='biogude id' name='bioguide_id' />
    <input type='submit' value='Remove Legislator' name='submit' />
</form>


<?php } ?>
<?php include('footer.php') ?>