<?php
session_start();

$Title = 'Admin';

include('header.php') ?>

<?php if ((!isset($_SESSION['login'])) || $_SESSION['login'] != true) { ?>

<div class="row">
    <div class="twelve columns">
    <h4>This page is only for admins please login.</h4>    
    </div>

</div>

<?php } else { ?>

<div class="row">
    <div class="twelve columns">
    <h4>Choose a task:</h4>

    <ul>
        <li><a class="button" href="change-term.php">Change Term</a></li>
        <li><a class="button" href="add-legislator.php">Add legislator</a></li>
        <li><a class="button" href="remove-legislator.php">Remove legislator</a></li>
    </ul>    
    </div>

</div>

<?php } ?>
<?php include('footer.php') ?>