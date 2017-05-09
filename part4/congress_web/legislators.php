<?php

if (isset($_GET['congress'])) {
    $congress = intval($_GET['congress']);
} else {
    $congress = 115; // Use current congress as default
}
?>
<?php 

$Title = "Legislators";

include('header.php');?>

<?php 

$sql =<<<EOQ
SELECT id as number from Congress ORDER BY id DESC;
EOQ;

$r = $db->query($sql);
?>

<div class "row">
    <div class="twelve columns">
        <form action="legislators.php" method="get">
            <div class="row">
            <div class="six columns">
                <label for="congress">Congress</label>
                <select class="u-full-width" name="congress">
                <?php while ($row = $r->fetch_array()) { ?>
                <option value="<?= $row['number'] ?>"><?= $row['number']; ?></option>
                <?php } // end while ?>
            </select>
            </div>
            </div>
            <input class="button-primary" type="submit" value="Select">
        </form>
    </div>
</div>

<?php $r->close(); ?>


<div class="row">
    <div class="twelve columns">
<?php

$stmt = $db->prepare("select distinct govtrack_id, bioguide_id, `First Name`, `Last Name`, state, chamber from Legislator natural join Term where start >= (select begin from Congress where id = ?) and end <= (select end from Congress where id = ?) ORDER BY state, `Last Name`");

$stmt->bind_param("dd", $congress, $congress); 

$stmt->execute();

$stmt->store_result();

$stmt->bind_result($govtrack_id, $bioguide_id, $first_name, $last_name, $state, $chamber);

$columnname = array('Photo', 'Name', 'State', 'Chamber');
?>
        <h4>Congress Members of the <?= $congress ?> Congress</h4>

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
                    <?php if (is_file("photos/" . $govtrack_id . "-200px.jpeg")) { ?>
                    <td><a href="legislator.php?id=<?= $bioguide_id ?>"><img src="<?= "photos/" . $govtrack_id . "-200px.jpeg"?>" alt="" /></a></td>

                    <?php } else { ?>
                    <td><a href="legislator.php?id=<?= $bioguide_id ?>"><img src="photos/placeholder-200px.jpeg" alt="" /></a></td>
                    <?php } ?>
                    <td><a href="legislator.php?id=<?= $bioguide_id ?>"><?= $first_name . " " . $last_name ?></a></td>
                    <td><?= $state ?></td>
                    <td><?= $chamber ?></td>
                </tr>
            <?php } //end while ?>
            </tbody>
        </table>
<?php $stmt->close(); ?>
    </div>
</div>

<?php include('footer.php'); ?>