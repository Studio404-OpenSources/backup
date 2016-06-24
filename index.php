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
		"removelink"=>"index.php?removebackup=true",
	),
	"uploadable_perm"=>"0755",
	"lang"=>array(
		"name"=>"დასახელება",
		"backup"=>"რეზერვი",
		"date"=>"თარიღი",
		"action"=>"მოქმედება",
		"addlink"=>"დამატება",
		"errorMsg"=>"მოხდა შეცდომა !", 
		"errorMsgPerm"=>"მოხდა შეცდომა ! <br /><br /><b>დირექტორიებს: </b> <br /> %s <br />%s/%s <br />%s/%s <br />%s/%s <br /><br />უნდა ჰქონდეს %s ნებართვა !", 
		"success"=>"ოპერაცია წარმატებით დასრულდა !",
		"delBackup"=>"გნებავთ წაშალოთ მონაცემი ?",
		"delete"=>"წაშლა", 
		"download"=>"ჩამოტვირთვა", 
		"download_pendding"=>"ფაილი ვერ მოიძებნა", 
		"back"=>"უკან", 
		"addBackup"=>"რეზერვის დამატება",
		"addBackupTitle"=>"რეზერვის სახელი",
		"chooseDir"=>"აირჩიეთ დირექტორია"
	)
);

$backup = new studio404\backup($main_options);
$backup->load();
?>
