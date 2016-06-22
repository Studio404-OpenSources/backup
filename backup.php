<?php
namespace studio404;

class backup{
	function __construct($main_options){
		$this->shell_folder = $main_options['shell_folder'];
		$this->destination_folder = $main_options['destination_folder'];
	}

	public function load(){
		$backup_path = __DIR__."/index.php";
		$archive_name = date("H-i-s").".tgz";
		$this->tgz($backup_path, $archive_name);
	}

	private function tgz($backup_path, $archive_name){
		if((is_dir($backup_path) || is_file($backup_path)) &&  !empty($archive_name)){
			$command = sprintf(
				"sh %s/b.sh %s %s %s %s",
				$this->shell_folder, 
				$backup_path, 
				$this->destination_folder, 
				date("d-m-Y"), 
				$archive_name
			);
			shell_exec($command);
		}
	}
}
?>