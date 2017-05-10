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

if (!$stmt->fetch()) {
    http_response_code(404);
    $Title = "Error 404, Bill not found";
    include('404.php'); // provide your own HTML for the error page
    include('footer.php');
    die();
}

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
            <dt>subjects</dt>
            <dd>
            <ul>
            <?php 
            $substmt = $db->prepare("SELECT subject FROM Subject WHERE Bill_id=?");

            $substmt->bind_param("s", $id);

            $substmt->execute();

            $substmt->store_result();

            $substmt->bind_result($subject);

            while ($substmt->fetch()) {
                $urlsubject = urlencode($subject);
                print("<li><a href=\"bill-subject.php?subject=$urlsubject\">$subject</a></li>");
            }

            $substmt->close();
            ?>
            </ul>
            </dd>
            <dt>Amendments</dt>
            <dd>
             <ul>
            <?php 
            $substmt = $db->prepare("SELECT id FROM Amendment WHERE Bill_id=?");

            $substmt->bind_param("s", $id);

            $substmt->execute();

            $substmt->store_result();

            $substmt->bind_result($Amendment_id);

            while ($substmt->fetch()) {
                $urlamend = urlencode($Amendment_id);
                print("<li><a href=\"amendment.php?id=$urlamend\">$Amendment_id</a></li>");
            }

            $substmt->close();
            ?>
            </ul>
            </dd>
            <dt>summary</dt>
            <dd><?= nl2br($summary); ?></dd>
        </dl>
    </div>
</div>

<?php include('footer.php'); ?>