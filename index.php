<?php
$shellx = "_shellx";
$backup_files = __DIR__."/index.php";
$dest = __DIR__."/_temp/backups";
$archive_folder = date("d-m-Y"); 
$archive_neme = date("H-i-s").".tgz"; 

$command = "sh ".$shellx."/b.sh ".$backup_files." ".$dest." ".$archive_folder." ".$archive_neme;

if((is_dir($backup_files) || file_exists($backup_files)) && is_dir($dest) )
{
	echo $command;
	shell_exec($command);
}
?>
