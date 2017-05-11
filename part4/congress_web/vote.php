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
        <h4><?= $question ?></h4>
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
        <dt>result</dt>
        <dd><?= $result ?></dd>
        <dt>congress</dt>
        <dd><?= $congress ?></dd>
        <dt>number</dt>
        <dd><?= $number ?></dd>
        <dt>type</dt>
        <dd><?= $type ?></dd>
	<?php if ($Bill_id) { ?>
        <dt>Related Bill</dt>
        <dd><a href="bill.php?id=<?= urlencode($Bill_id) ?>"><?= $Bill_id ?></a></dd>
        <?php } ?>
        <?php if ($Amendment_id) { ?>
        <dt>Related Amendment</dt>
        <dd><a href="amendment.php?id=<?= urlencode($Amendment_id) ?>"><?= $Amendment_id ?></a></dd>

	<?php } ?>
	
	    <dt>Vote breakdown by party</dt>
            <dd>
            <?php 
            $substmt = $db->prepare("select how_voted, party, COUNT(party) from Legislator_Vote natural join (Term natural join Legislator) where Vote_id=? and party = 'Democrat' group by how_voted");

            $substmt->bind_param("s", $id);

            $substmt->execute();

            $substmt->store_result();

            $substmt->bind_result($how_voted, $party, $count_party);
	    $columnname = array('how_voted', 'party', 'count_party');

            ?>
            <table>
                <thead>
                    <tr>
			<th>how_voted</th>
			<th>party</th>
			<th>count_party</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($substmt->fetch()) { ?>
		   <tr>
			<td><?= $how_voted ?></td>
			<td><?= $party ?></td>
			<td><?= $count_party ?></td>			
		   </tr>
		   
                <?php } //end while ?>
		</tbody>
	    </table>
            
            <?php
            $substmt->close();

            $substmt = $db->prepare("select how_voted, party, COUNT(party) from Legislator_Vote natural join (Term natural join Legislator) where Vote_id=? and party = 'Republican' group by how_voted");

            $substmt->bind_param("s", $id);

            $substmt->execute();

            $substmt->store_result();

            $substmt->bind_result($how_voted, $party, $count_party);
	    $columnname = array('how_voted', 'party', 'count_party');

            ?>
            <table>
                <tbody>
                <?php while ($substmt->fetch()) { ?>
		   <tr>
			<td><?= $how_voted ?></td>
			<td><?= $party ?></td>
			<td><?= $count_party ?></td>			
		   </tr>
		   
                <?php } //end while ?>
		</tbody>
	    </table>
            
            <?php
            $substmt->close();

            ?>
            </dd>


            <dt>Voted on by Legislator</dt>
            <dd>
            <?php 
            $substmt = $db->prepare("SELECT bioguide_id, how_voted, `Last Name` FROM Legislator_Vote natural join Legislator WHERE Vote_id=?");

            $substmt->bind_param("s", $id);

            $substmt->execute();

            $substmt->store_result();

            $substmt->bind_result($bioguide_id, $how_voted, $last_name);

            ?>
            <ul>
                <?php while ($substmt->fetch()) { ?>

		   <li>
		   <a href="legislator.php?id=<?= urlencode($bioguide_id) ?>"><?= $last_name ?></a>
    		   <?= $how_voted ?>
		   </li>

		   
                <?php } //end while ?>
            </ul>
            
            <?php
            $substmt->close();
            ?>
            </dd>
        </dl>
    </div>
</div>

    

<?php include('footer.php');?>