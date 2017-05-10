
<?php
if (isset($_GET['query'])) {
    $query = $_GET['query'];
} else {
    http_response_code(404);
    $Title = "Error 404, No Query Given";
    include('header.php');
    include('searchbarvote.php');
    include('404.php'); // provide your own HTML for the error page
    include('footer.php');
    die();
}

if (strlen($query) < 5) {
    http_response_code(404);
    $Title = "Error 404, Query too short, need at least 5 characters";
    include('header.php');
    include('searchbarvote.php');   
    include('404.php'); // provide your own HTML for the error page
    include('footer.php');
    die();
}

if (isset($_GET['congress'])) {
    if ($_GET['congress'] == 'Any') {
        $congress = false;
    } else {
        $congress = intval($_GET['congress']);
    }
} else {
    http_response_code(404);
    $Title = "Error 404, No Congress Given";
    include('header.php');
    include('searchbarvote.php');
    include('404.php'); // provide your own HTML for the error page
    include('footer.php');
    die();
}
?>
<?php 

$Title = "Search";

include('header.php');

include('searchbarvote.php'); ?>


<div class="row">
    <div class="twelve columns">
<?php

$limit = 20;
$likequery = "%" . $query . "%";
if ($congress) {
    $stmt = $db->prepare("SELECT id, question, date, congress FROM Vote WHERE (question LIKE ? OR category LIKE ?) AND congress = ? limit 20");

    $stmt->bind_param("ssd", $likequery, $likequery, $congress); 
} else {
    $stmt = $db->prepare("SELECT id, question, date, congress FROM Vote WHERE question LIKE ? OR category LIKE ? limit 20");

    $stmt->bind_param("ss", $likequery, $likequery); 
}


$stmt->execute();

$stmt->store_result();

$stmt->bind_result($vote_id, $question, $date, $vote_congress);


$columnname = array('Vote ID', 'Title', 'Date', 'Congress');
?>
        <h4>Search Results (20 max results)</h4>

        <p>Your query was: <?= htmlspecialchars($query) ?><p>
        <p>From Congress: <?= $congress ? $congress : "Any" ?></p>

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
                    <td><a href="vote.php?id=<?= $vote_id ?>"><?= $vote_id ?></a></td>
                    <td><a href="vote.php?id=<?= $vote_id ?>"><?= $question ?></a></td>
                    <td><?= $date ?></td>
                    <td><?= $vote_congress ?></td>
                </tr>
            <?php } //end while ?>
            </tbody>
        </table>
<?php $stmt->close(); ?>
    </div>
</div>

<?php include('footer.php'); ?>