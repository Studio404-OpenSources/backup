<?php
header("Content-type: text/html; charset=utf-8");


@include "backup.php"; 
$main_options = array(
	"project_path"=>"_temp",
	"slug"=>array(
		"addlink"=>"index.php?addbackup=true"
	),
	"lang"=>array(
		"addlink"=>"დამატება",
		"errorMsg"=>"მოხდა შეცდომა !"
	)
);

$backup = new studio404\backup($main_options);
$backup->load();
?>
