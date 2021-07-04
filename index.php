<?php 
require("partials/head.php");
require("ParseName.php");

$parser = new ParseName();

echo '<pre style="border-top: 5px solid black;">';
echo '<strong>Final list below</strong> <br />';
print_r($parser->makePeople());
echo '</pre>';
?>

<?php require("partials/foot.php"); ?>