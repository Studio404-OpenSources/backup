<?php
namespace studio404;

class backup{
	function __construct($options){
		$secondary = array(
			"css"=>array(
				"addlink"=>array(
					"margin"=>"10px 5px",
					"padding"=>"10px 20px",
					"background-color"=>"#f2f2f2",
					"text-decoration"=>"none",
					"color"=>"#555555",
					"text-align"=>"center",
					"float"=>"left"
				),
				"table"=>array(
					"margin"=>"10px 5px",
					"padding"=>"0px",
					"width"=>"calc(100% - 10px)",
					"float"=>"left",
					"border"=>"solid 1px #f2f2f2"
				),
				"table.tr.head"=>array(
					"margin"=>"0px",
					"padding"=>"10px",
					"background-color"=>"#cccccc",
					"color"=>"white"
				)
			)
		);
		$this->option = $options + $secondary;
		$this->shell_path = sprintf(
			'%s/%s',
			$this->option['project_path'],
			'shellx'
		);
		$this->backup_dir = sprintf(
			'%s/%s',
			$this->option['project_path'],
			'backups'
		);
		$this->json_path = sprintf(
			'%s/%s',
			$this->option['project_path'],
			'json'
		);
		$this->post_request();
	}

	public function load(){
		echo $this->backup_table();
	}

	private function backup_table(){
		$content = sprintf(
			'<a href="%s" style="%s">%s</a>', 
			$this->option['slug']['addlink'],
			$this->arrayToStyle($this->option['css']['addlink']),
			$this->option['lang']['addlink']
		);

		$content .= sprintf(
			'<table style="%s" cellspaceing="10" cellpadding="10">
			<tr style="%s">
				<td>დასახელება</td>
				<td>რეზერვი</td>
				<td>თარიღი</td>
				<td>მოქმედება</td>
			</tr>
			<tr>
				<td>ფოტო</td>
				<td>photoes/</td>
				<td>20-07-2016</td>
				<td><a href="">წაშლა</a> / <a href="">ჩამოტვირთვა</a></td>
			</tr>
			</table>',
			$this->arrayToStyle($this->option['css']['table']), 
			$this->arrayToStyle($this->option['css']['table.tr.head'])
		);
		$this->load_tgzs();

		return $content;
	}

	private function load_tgzs(){
		$command = 'ls '.$this->json_path.' 2>&1';
		$shell = shell_exec($command);
		$files = explode(".json", $shell);
		$files = array_map('trim', $files);
		
		foreach ($files as $val) {
			if(!empty($val) && $val != ""){
				echo $val.".json<br />";
			}
		}
	}

	private function post_request(){
		// $backup_path = __DIR__."/index.php";  $archive_name = date("H-i-s").".tgz";
		if($this->requests("POST","bpath") && $this->requests("POST","aname")){
			$backup = sprintf(
				'%s',
				$this->requests("POST","bpath")
			);

			$archive_name = sprintf(
				'%s.tgz',
				$this->requests("POST","aname")
			);
			$this->tgz($backup, $archive_name);
		}
	}

	private function tgz($backup, $archive_name){
		if((is_dir($backup) || is_file($backup)) &&  !empty($archive_name)){
			$command = sprintf(
				'sh %s/b.sh %s %s %s %s',
				$this->shell_path, 
				$backup, 
				$this->backup_dir, 
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