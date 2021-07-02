<?php 
require("partials/head.php");
require("HandleCsv.php");

$handler = new HandleCsv();

// foreach($handler->csvToArray() as $line) {
//     echo'<pre>';
//     echo $line;
//     echo'</pre>';
// }

$handler->parseNames();

?>

<?php require("partials/foot.php"); ?>