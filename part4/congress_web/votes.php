<?php

$Title = "Votes";
include('header.php');

include('searchbarvote.php');
?>


<?php

$limit = 20;

$sql =<<<EOQ
select id, question FROM Vote ORDER BY date DESC limit $limit;
EOQ;

$r = $db->query($sql);

$columnname = array('id', 'question');
?>
        <h4>Recent votes:</h4>
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
                   <td><a href="vote.php?id=<?= urlencode($row[0]) ?>"><?= $row[$i] ?></td>
                <?php } ?>
                </tr>
            <?php } //end while ?>
            </tbody>
        </table>

<?php include('footer.php'); ?>