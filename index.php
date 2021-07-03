<?php 

require __DIR__ . '/vendor/autoload.php';

require("partials/head.php");
require("HandleCsv.php");

use ADCI\FullNameParser\Parser;

$parser = new Parser();
$handler = new HandleCsv();

foreach($handler->csvToArray() as $line) {
    echo'<pre>';
    print_r($parser->parse($line));
    echo'</pre>';
}

?>

<?php require("partials/foot.php"); ?>