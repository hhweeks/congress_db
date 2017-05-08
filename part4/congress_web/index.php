<?php 

$Title = "Home";

include('header.php'); ?>


<div class="row">
	<div class="twelve column">

<?php
$sql =<<<EOQ
SELECT Bill.id, title, Bill.type, status, date, result
FROM Bill join Vote ON Bill.id = Vote.Bill_id where Vote.type = 'On Passage of the Bill' AND result = 'Bill Passed' AND Vote.chamber = 's' ORDER BY date limit 5;
EOQ;

$r = $db->query($sql);

if (!$r) {
    printf ("Query '$sql' failed: %s (%d)\n", $db->error, $db->errno);
    exit();
}

// TODO 2: Paste the $columnname variable from exercise 2 here
$columnname =  array("Bill ID", "Title", "Type", "Current Status", "Date");
?>

		<h2>5 most recently passed bills in the Senate:</h2>
		<table>
		    <thead>
		        <tr>
		        <?php for ($i=0; $i<5; $i++) { ?>
		            <th>
		                <?= $columnname[$i]; ?>
		            </th>
		        <?php } //end for ?>
		        </tr>
		    </thead>
		    <tbody>
		    <?php while ($row = $r->fetch_array()) { ?>
		        <tr>
		        <?php 
		        $id = urlencode($row['id']);
		        for ($i=0; $i<5; $i++) { 
                    if ($i < 2) {
		              print ("<td><a href=\"bill.php?id=$id\">$row[$i]</a></td>");
                    } else {
                        print ("<td>$row[$i]</td>");
                    }

		        }
		        ?>
		        </tr>
		    <?php } //end while ?>
		    </tbody>
		</table>
	</div>
</div>

<?php $r->close(); ?>

<div class="row">
	<div class="twelve column">

<?php
$sql =<<<EOQ
SELECT Bill.id, title, Bill.type, status, date, result
FROM Bill join Vote ON Bill.id = Vote.Bill_id where Vote.type = 'On Passage of the Bill' AND result = 'Passed' AND Vote.chamber = 'h' ORDER BY date limit 5;
EOQ;

$r = $db->query($sql);

if (!$r) {
    printf ("Query '$sql' failed: %s (%d)\n", $db->error, $db->errno);
    exit();
}
?>

		<h2>5 most recently passed bills in the house:</h2>
		<table>
		    <thead>
		        <tr>
		        <?php for ($i=0; $i<5; $i++) { ?>
		            <th>
		                <?= $columnname[$i]; ?>
		            </th>
		        <?php } //end for ?>
		        </tr>
		    </thead>
		    <tbody>
		    <?php while ($row = $r->fetch_array()) { ?>
		        <tr>
		        <?php 
		        
		        for ($i=0; $i<5; $i++) { 
                    if ($i < 2) {
                      print ("<td><a href=\"bill.php?id=$id\">$row[$i]</a></td>");
                    } else {
                        print ("<td>$row[$i]</td>");
                    }
		        }
		        ?>
		        </tr>
		    <?php } //end while ?>
		    </tbody>
		</table>
	</div>
</div>

<?php include('footer.php'); ?>