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

$Title = "Vote " . $id;

include('header.php');
$stmt = $db->prepare("SELECT id, chamber, category, question, congress, session, result, requires, number, date, type, Bill_id, Amendment_id FROM Vote WHERE id=?");

$stmt->bind_param("s", $id);

$stmt->execute();

$stmt->store_result();

$stmt->bind_result($vote_id, $chamber, $category, $question, $congress, $session, $result, $requires, $number, $date, $type, $Bill_id, $Amendment_id);

if(!$stmt->fetch()){
    http_response_code(404);
    $Title = "Error 404, Vote not found";
    include('404.php');
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
        <dt>chamber</dt>
        <dd><?= $chamber ?></dd>
        <dt>category</dt>
        <dd><?= $category ?></dd>
        <dt>question</dt>
        <dd><?= $question ?></dd>
        <dt>date</dt>
        <dd><?= $date ?></dd>
        </dl>
    </div>
</div>

    

<?php include('footer.php');?>