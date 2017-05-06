<?php 

$Title = "Legislators";

include('header.php'); ?>
<!-- Primary Page Layout -->
<div class="container">
<div class="row">
    <div class="one-half column">
    <h4>Basic Page</h4>
    <p>This index.html page is a placeholder with the CSS, font and favicon. It's just waiting for you to add some content! If you need some help hit up the <a href="http://www.getskeleton.com">Skeleton documentation</a>.</p>
    </div>

<div class="twelve column">
<?php
$sql =<<<EOQ
SELECT `bioguide_id`, `Last Name`, `First Name`, birthday, gender, govtrack_id 
from Legislator;
EOQ;

$r = $db->query($sql);

if (!$r) {
    printf ("Query '$sql' failed: %s (%d)\n", $db->error, $db->errno);
    exit();
}

// TODO 2: Paste the $columnname variable from exercise 2 here
$columnname =  array("Bioguide ID", "First Name", "Last Name", "birthday", "gender");
?>

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
            print ("<td>$row[$i]</td>");
        }
        ?>
        </tr>
    <?php } //end while ?>
    </tbody>
</table>
</div>
</div>
</div>
<?php include('footer.php'); ?>