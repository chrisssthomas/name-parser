<?php 
require("partials/head.php");
require("ParseName.php");

$parser = new ParseName();

$parser->buildPerson();

?>

<?php require("partials/foot.php"); ?>