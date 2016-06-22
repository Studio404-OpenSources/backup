<?php
namespace studio404;

class backup{
	function __construct($options){
		$secondary = array(
			"css"=>array(
				"addlink"=>array(
					"margin"=>"10px",
					"padding"=>"10px 20px",
					"background-color"=>"#f2f2f2",
					"text-decoration"=>"none",
					"color"=>"#555555",
					"text-align"=>"center",
					"float"=>"left"
				)
			),
			"lang"=>array(
				"addlink"=>"დამატება",
				"errorMsg"=>"მოხდა შეცდომა !"
			)
		);
		$this->option = $options + $secondary;
		$this->post_request();
	}

	public function load(){
		echo $this->backup_table();
	}

	private function backup_table(){
		$content = sprintf(
			'<a href="%s" style="%s">%s</a>', 
			$this->option['slug'],
			$this->arrayToStyle($this->option['css']['addlink']),
			$this->option['lang']['addlink']
		);
		return $content;
	}

	private function post_request(){
		// $backup_path = __DIR__."/index.php";  $archive_name = date("H-i-s").".tgz";
		if($this->requests("POST","bpath") && $this->requests("POST","aname")){
			$backup_path = sprintf(
				'%s',
				$this->requests("POST","bpath")
			);

			$archive_name = sprintf(
				'%s.tgz',
				$this->requests("POST","aname")
			);
			$this->tgz($backup_path, $archive_name);
		}
	}

	private function tgz($backup_path, $archive_name){
		if((is_dir($backup_path) || is_file($backup_path)) &&  !empty($archive_name)){
			$command = sprintf(
				'sh %s/b.sh %s %s %s %s',
				$this->option['shell_folder'], 
				$backup_path, 
				$this->option['destination_folder'], 
				date("d-m-Y"), 
				$archive_name
			);
			shell_exec($command);
		}
	}

	private function requests($type,$item){
		if($type=="POST" && isset($_POST[$item])){
			return filter_input(INPUT_POST, $item);
		}else if($type=="GET" && isset($_GET[$item])){
			return filter_input(INPUT_GET, $item);
		}else{
			return '';
		}
	}

	private function arrayToStyle($array){
		$output = '';
		try{
			if(is_array($array)){				
				$output = implode('; ', array_map(
					function ($v, $k) { 
						return sprintf("%s:%s", $k, $v); 
					},
					$array,
					array_keys($array)
				));
			}
		}catch(Exception $e){
			$this->outMessage = sprintf(
				'%s%s', 
				$this->option['lang']['errorMsg'],
				$e
			);
		}
		return $output;
	}

}
?>