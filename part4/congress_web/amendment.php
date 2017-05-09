<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    http_response_code(404);
    $Title = "Error 404, Amendment not found";
    include('header.php');
    include('404.php'); // provide your own HTML for the error page
    include('footer.php');
    die();
}


$Title = "Amendment " . $id;

include('header.php');
$stmt = $db->prepare("SELECT id, description, purpose, status, introduced_at, status_at, type, Bill_id, Amendment_id, congress, number FROM Amendment WHERE id=?");

$stmt->bind_param("s", $id);

$stmt->execute();

$stmt->store_result();

$stmt->bind_result($this_id, $description, $purpose, $status, $introduced_at, $status_at, $type, $Bill_id, $Amendment_id, $congress, $number);

if (!$stmt->fetch()) {
    http_response_code(404);
    $Title = "Error 404, Amendment not found";
    include('404.php'); // provide your own HTML for the error page
    include('footer.php');
    die();
}

?>

<div class="row">
    <div class="twelve column">
        <h4><?= $id ?></h4>
        <dl>
            <dt>id</dt>
            <dd><?= $this_id ?></dd>
            <?php if ($description) { ?>
            <dt>description</dt>
            <dd><?= $description ?></dd>
            <?php } ?>
            <?php if ($purpose) { ?>
            <dt>purpose</dt>
            <dd><?= $purpose ?></dd>
            <?php } ?>
            <dt>status</dt>
            <dd><?= $status ?></dd>
            <dt>introduced_at</dt>
            <dd><?= $introduced_at ?></dd>
            <dt>congress</dt>
            <dd><?= $congress ?></dd>
            <dt>number</dt>
            <dd><?= $number ?></dd>
            <?php if ($Bill_id) { ?>
            <dt>Related Bill</dt>
            <dd><a href="bill.php?id=<?= urlencode($Bill_id) ?>"><?= $Bill_id ?></a></dd>
            <?php } ?>
        </dl>
    </div>
</div>

<?php include('footer.php'); ?>