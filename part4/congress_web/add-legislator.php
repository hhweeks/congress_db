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



    if (isset($_POST['bioguide_id']) &&
        isset($_POST['last_name']) &&
        isset($_POST['first_name']) &&
        isset($_POST['govtrack_id']))
    {

        $bioguide_id = $_POST['bioguide_id'];
        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $govtrack_id = intval($_POST['govtrack_id']);

    } else {
        echo "<h4>Form incorrect<h4>";
        echo '<a class="button" href="add-legislator.php">Retry</a>';
        include 'footer.php';
        die();
    }

    ?>

    Submitted with
    <?php

    echo "<p>" . $bioguide_id . "</p>";
    echo "<p>" . $last_name . "</p>";
    echo "<p>" . $first_name . "</p>";
    echo "<p>" . $govtrack_id . "</p>";

    $stmt = $db->prepare("SELECT count(bioguide_id) FROM Legislator WHERE bioguide_id=? OR govtrack_id=?;");

    $stmt->bind_param("sd", $bioguide_id, $govtrack_id);
    $stmt->execute();

    $stmt->bind_result($count);

    $stmt->fetch();

    $stmt->close();
    if ($count != 0) {
        echo "<h4>Legislator already exists with $bioguide_id</h4>";
        echo '<a class="button" href="add-legislator.php">Retry</a>';
        include('footer.php');
        die();
    }

    $stmt = $db->prepare("INSERT INTO Legislator (bioguide_id, `Last Name`, `First Name`, govtrack_id) VALUE (?,?,?,?);");

    $stmt->bind_param("sssd", $bioguide_id, $last_name, $first_name, $govtrack_id);

    if ($stmt->execute()) {
        echo "<h4>Legislator $bioguide_id added</h4>";
    } else {
        echo "<h4>Error inserting into database</h4>";
    }

    $stmt->close();

    ?>

<?php } else { ?>

<form name='input' action='add-legislator.php' method='post'>
    <label for='bioguide_id'>bioguide id</label><input type='text' id='bioguide_id' placeholder='unique string' name='bioguide_id' />
    <label for='last_name'>Last Name</label><input type='text' id='last_name' placeholder='Last Name' name='last_name' />
    <label for='first_name'>First Name</label><input type='text' id='first_name' placeholder='First Name' name='first_name' />
    <label for='govtrack_id'>govtrack_id</label><input type='text' id='govtrack_id' placeholder='unique number' name='govtrack_id' />
    <input type='submit' value='Add Legislator' name='submit' />
</form>


<?php } ?>
<?php include('footer.php') ?>