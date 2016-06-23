<?php
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Tbilisi');

@include "backup.php"; 
$main_options = array(
	"_root"=>$_SERVER['DOCUMENT_ROOT'],
	"_path"=>"_temp",
	"slug"=>array(
		"home"=>"index.php",
		"addlink"=>"index.php?addbackup=true",
	),
	"lang"=>array(
		"name"=>"დასახელება",
		"backup"=>"რეზერვი",
		"date"=>"თარიღი",
		"action"=>"მოქმედება",
		"addlink"=>"დამატება",
		"errorMsg"=>"მოხდა შეცდომა !", 
		"delete"=>"წაშლა", 
		"download"=>"ჩამოტვირთვა", 
		"back"=>"უკან", 
		"addBackup"=>"რეზერვის დამატება",
		"addBackupTitle"=>"რეზერვის სახელი",
		"chooseDir"=>"აირჩიეთ დირექტორია"
	)
);

$backup = new studio404\backup($main_options);
$backup->load();
?>
