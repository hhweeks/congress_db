<?php

//ini_set('display_errors', 1);

foreach (array_keys ($_GET) as $key) {
    $v = $_GET[$key];
    if (!is_numeric($v)) {
        $_GET[$key] = $db->real_escape_string($v);
    }
}

foreach (array_keys ($_POST) as $key) {
    $v = $_POST[$key];
    if (!is_numeric($v)) {
        $_POST[$key] = $db->real_escape_string($v);
    }
}

require_once('config.php');
$limit = 5;
if (!empty($_GET['limit'])) {
    $limit = $_GET['limit'];
}

// TODO 3: Add $limit to the value of the <input> limit
echo <<<EOH
<form action="index.php">
Limit: <input type="text" name="limit" value="$limit"/>
<input type="submit" />
</form>
EOH;


// TODO 1: Copy and paste your query from exercise 2 here,
//       then add a LIMIT and use the variable $limit from above.

$sql =<<<EOQ
SELECT `Last Name`, COUNT(`Last Name`) from Legislator group by `Last Name` limit $limit
EOQ;

$r = $db->query($sql);

if (!$r) {
    printf ("Query '$sql' failed: %s (%d)\n", $db->error, $db->errno);
    exit();
}

// TODO 2: Paste the $columnname variable from exercise 2 here
$columnname =  array("Last Name", "Count");

echo "<table>\n";
echo "<tr><th>$columnname[0]</th><th>$columnname[1]</th></tr>\n";
while ($row = $r->fetch_array()) {
    print ("<tr>\n");
    for ($i=0; $i<2; $i++) {
        print ("<td>$row[$i]</td>");
    }
    print ("</tr>\n");
}
echo "</table>\n";

?>
