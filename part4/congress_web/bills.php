<?php 

$Title = "Bills";

include('header.php'); ?>



<div class="row">
    <div class="eight columns">
<?php

$limit = 20;

$sql =<<<EOQ
select id, title FROM Bill ORDER BY introduction_date limit $limit;
EOQ;

$r = $db->query($sql);

$columnname = array('id', 'title');
?>
        <h4>Recently introduced bills:</h4>
        <table>
            <thead>
                <tr>
                <?php for ($i=0; $i<2; $i++) { ?>
                    <th>
                        <?= $columnname[$i]; ?>
                    </th>
                <?php } //end for ?>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $r->fetch_array()) { ?>
                <tr>
                <?php for ($i=0; $i<2; $i++) { ?>
                   <td><a href="bill.php?id=<?= urlencode($row[0]) ?>"><?= $row[$i] ?></td>
                <?php } ?>
                </tr>
            <?php } //end while ?>
            </tbody>
        </table>
<?php $r->close(); ?>
    </div>
    <div class="four columns">
<?php

$sql =<<<EOQ
select subject from (select subject, count(subject) as sc from Subject group by subject) as c where sc > 2000 ORDER BY subject;
EOQ;

$r = $db->query($sql); ?>

        <h4>Common Subjects:</h4>
        <ul>
        <?php while ($row = $r->fetch_array()) { ?>
            <?php $subject = urlencode($row['subject']); ?>
            <li class="no-style"><a href="bill-subject.php?subject=<?= $subject ?>"><?= $row['subject']; ?></a></li>
        <?php } //end while ?>

<?php $r->close(); ?>

        </ul>
    </div>
</div>

<?php include('footer.php'); ?>