<?php 

$Title = "Amendments";

include('header.php'); ?>

<?php include('searchbaramendment.php'); ?>

<div class="row">
    <div class="twelve columns">
<?php

$limit = 20;

$sql =<<<EOQ
select id, description, purpose, introduced_at FROM Amendment ORDER BY introduced_at limit $limit;
EOQ;

$r = $db->query($sql);

$columnname = array('id', 'description', 'Introduced at');
?>
        <h4>Recently introduced amendments:</h4>
        <table>
            <thead>
                <tr>
                <?php for ($i=0; $i<3; $i++) { ?>
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
                   <td><a href="amendment.php?id=<?= urlencode($row[0]) ?>"><?= $row[$i] ? $row[$i] : $row[$i+1]; ?></td>

                <?php } ?>
                   <td><?= htmlspecialchars($row[3]) ?></td>

                </tr>
            <?php } //end while ?>
            </tbody>
        </table>
<?php $r->close(); ?>
    </div>

<?php include('footer.php'); ?>