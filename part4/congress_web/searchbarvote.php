
<?php 

$sql =<<<EOQ
SELECT id as number from Congress ORDER BY id DESC;
EOQ;

$r = $db->query($sql);
?>

<div class "row">
    <div class="twelve columns">
        <form action="searchvote.php" method="get">
            <div class="row">
            <div class="six columns">
                <label for="query">Query</label>
                <input class="u-full-width" type="text" placeholder="e.g. question" name="query">
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