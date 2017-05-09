
<?php
if (isset($_GET['subject'])) {
    $subject = $_GET['subject'];
} else {
    http_response_code(404);
    $Title = "Error 404, No Subject Given";
    include('header.php');
    include('searchbar.php');
    include('404.php'); // provide your own HTML for the error page
    include('footer.php');
    die();
}

?>
<?php 

$Title = "Subject";

include('header.php');

include('searchbar.php'); ?>


<div class="row">
    <div class="twelve columns">
<?php

$limit = 20;
$stmt = $db->prepare("SELECT id, title, status, introduction_date, congress FROM Bill join Subject ON Bill.id = Subject.Bill_id  WHERE subject = ? limit 20");

$stmt->bind_param("s", $subject);

$stmt->execute();

$stmt->store_result();

$stmt->bind_result($bill_id, $title, $status, $introduction_date, $bill_congress);


$columnname = array('Bill ID', 'Title', 'Introduction Date', 'Congress');
?>
        <h4>Search Results (20 max results)</h4>

        <h3>From Subject: <?= htmlspecialchars($subject) ?></h3>

        <table>
            <thead>
                <tr>
                <?php for ($i=0; $i<4; $i++) { ?>
                    <th>
                        <?= $columnname[$i]; ?>
                    </th>
                <?php } //end for ?>
                </tr>
            </thead>
            <tbody>
            <?php while ($stmt->fetch()) { ?>
                <tr>
                    <td><a href="bill.php?id=<?= $bill_id ?>"><?= $bill_id ?></a></td>
                    <td><a href="bill.php?id=<?= $bill_id ?>"><?= $title ?></a></td>
                    <td><?= $introduction_date ?></td>
                    <td><?= $bill_congress ?></td>
                </tr>
            <?php } //end while ?>
            </tbody>
        </table>
<?php $stmt->close(); ?>
    </div>
</div>

<?php include('footer.php'); ?>