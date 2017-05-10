<?php 

$congress = 115;// only for current congress

$Title = "Demographics of the $congress Congress";

include('header.php'); ?>

<div class="row">
    <div class="four columns">
    <h4>Majority party by state</h4>

<?php
$sql =<<<EOQ

select state, party, count(party) from (select distinct bioguide_id, start, end, state, party from Legislator natural join Term
where start < (select end from Congress where id = $congress) and NOT (end <= (select begin from Congress where id = $congress))) as curr GROUP BY state, party ORDER BY state, party;
EOQ;

$r = $db->query($sql);

$columnname = array('state', 'party', 'count');
?>
        <h4>Party members by state:</h4>
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
                <?php for ($i=0; $i<3; $i++) { ?>
                   <td><?= $row[$i]; ?></td>
                <?php } ?>
                </tr>
            <?php } //end while ?>
            </tbody>
        </table>
    <?php $r->close(); ?>
    </div>

    </div>
</div>
<?php include('footer.php'); ?>