<?php

$Title = "Votes";
include('header.php');
?>

<?php
$sql =<<<EOQ
SELECT id as number from Congress ORDER BY id DESC;
EOQ;

$r = $db->query($sql);
?>

<div class "row">
    <div class="twelve columns">
        <form action="search.php" method="get">
            <div class="row">
            <div class="six columns">
                <label for="query">Query</label>
                <input class="u-full-width" type="text" placeholder="e.g. vote question" name="query">
            </div>
            <div class="six columns">
                <label for="congress">Congress</label>
                <select class="u-full-width" name="congress">
                <option value="any">Any</option>
                <?php while ($row = $r->fetch_array()) { ?>
                <option value="<?= $row['number'] ?>"><?= $row['number']; ?></option>
                <?php } // end while ?>
            </select>
            </div>
            </div>
            <input class="button-primary" type="submit" value="Search">
        </form>
    </div>
</div>

<?php $r->close(); ?>

<?php

$limit = 20;

$sql =<<<EOQ
select id, question FROM Vote ORDER BY date limit $limit;
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