<?php
@include "backup.php"; 

$main_options = array(
	"shell_folder"=>"_shellx",
	"destination_folder"=>__DIR__."/_temp/backups"
);

$backup = new studio404\backup($main_options);
$backup->load();
?>
