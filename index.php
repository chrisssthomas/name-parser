<?php 
require("partials/head.php");
require("HandleCsv.php");

include("Parser.php");

$handler = new HandleCsv();


$parser = new FullNameParser();





foreach($handler->csvToArray() as $line) {
    echo'<pre>';
    print_r($parser->parse_name($line));
    echo'</pre>';
}

?>

<?php require("partials/foot.php"); ?>