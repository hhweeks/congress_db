<?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    http_response_code(404);
    $Title = "Error 404, Bill not found";
    include('header.php');
    include('404.php'); // provide your own HTML for the error page
    include('footer.php');
    die();
}

$Title = "Bill " . $id;

include('header.php');
$stmt = $db->prepare("SELECT id, type, title, popular_title, short_title, status, introduction_date, summary, congress, number FROM Bill WHERE id=?");

$stmt->bind_param("s", $id);

$stmt->execute();

$stmt->store_result();

$stmt->bind_result($bill_id, $type, $title, $popular_title, $short_title, $status, $introduction_date, $summary, $congress, $number);

$stmt->fetch();

?>

<div class="row">
    <div class="twelve column">
        <h4><?= $title ?></h4>
        <dl>
            <dt>id</dt>
            <dd><?= $id ?></dd>
            <dt>type</dt>
            <dd><?= $type ?></dd>
            <dt>status</dt>
            <dd><?= $status ?></dd>
            <dt>introduction_date</dt>
            <dd><?= $introduction_date ?></dd>
            <dt>congress</dt>
            <dd><?= $congress ?></dd>
            <dt>number</dt>
            <dd><?= $number ?></dd>
            <dt>summary</dt>
            <dd><?= nl2br($summary); ?></dd>
        </dl>
    </div>
</div>

<?php stmt->close(); ?>

<?php include('footer.php'); ?>