<?php
header("Content-type: text/html; charset=utf-8");


@include "backup.php"; 
$main_options = array(
	"shell_folder"=>"_shellx",
	"destination_folder"=>"_temp/backups",
	"slug"=>"index.php"
);

$backup = new studio404\backup($main_options);
$backup->load();
?>
