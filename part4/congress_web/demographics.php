<?php 

$congress = 115;// only for current congress

$Title = "Demographics of the $congress Congress";

include('header.php'); ?>

<div class="row">
    <div class="four columns">

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
    <div class="four columns">
        <h4>Counts of genders</h4>

<?php
$sql =<<<EOQ

select gender, count(gender) from (select distinct bioguide_id, start, end, state, gender from Legislator natural join Term
where start < (select end from Congress where id = $congress) and NOT (end <= (select begin from Congress where id = $congress))) as curr GROUP BY gender ORDER BY gender;
EOQ;

$r = $db->query($sql);

$columnname = array('gender', 'count');
?>
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
                   <td><?= $row[$i]; ?></td>
                <?php } ?>
                </tr>
            <?php } //end while ?>
            </tbody>
        </table>
    <?php $r->close(); ?>

    </div>

        <div class="four columns">
        <h4>Counts of parties</h4>

<?php
$sql =<<<EOQ

select party, count(party) from (select distinct bioguide_id, start, end, state, party from Legislator natural join Term
where start < (select end from Congress where id = $congress) and NOT (end <= (select begin from Congress where id = $congress))) as curr GROUP BY party ORDER BY party;
EOQ;

$r = $db->query($sql);

$columnname = array('party', 'count');
?>
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
                   <td><?= $row[$i]; ?></td>
                <?php } ?>
                </tr>
            <?php } //end while ?>
            </tbody>
        </table>
    <?php $r->close(); ?>

    </div>
</div>


<?php include('footer.php'); ?>