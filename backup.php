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
				),
				"backup_actions"=>array(
					"color"=>"red"
				),
				"add_backup_header"=>array(
					"margin"=>"10px 5px",
					"padding"=>"5px 10px",
					"width"=>"calc(100% - 30px)",
					"font-size"=>"16px",
					"line-height"=>"30px",
					"background-color"=>"#cccccc",
					"color"=>"#ffffff",
					"float"=>"left"
				),
				"add_backup_form"=>array(
					"margin"=>"0px 5px",
					"padding"=>"0px",
					"width"=>"calc(100% - 10px)"
				),
				"add_backup_label"=>array(
					"margin"=>"0px",
					"padding"=>"10px 0",
					"width"=>"100%",
					"float"=>"left"
				),
				"add_backup_input_text"=>array(
					"margin"=>"0px",
					"padding"=>"10px",
					"outline"=>"none",
					"width"=>"100%",
					"height"=>"35px",
					"float"=>"left"
				),
				"add_backup_button"=>array(
					"margin"=>"15px 0",
					"padding"=>"0 20px",
					"border"=>"0px",
					"height"=>"35px",
					"line-height"=>"35px",
					"float"=>"left",
					"background-color"=>"red",
					"color"=>"#ffffff",
					"cursor"=>"pointer"
				),
				"add_backup_suggestion"=>array(
					"margin"=>"0px",
					"padding"=>"5px",
					"background-color"=>"#f2f2f2",
					"width"=>"calc(100% - 10px)",
					"float"=>"left",
					"line-height"=>"20px",
					"display"=>"none"
				),
				"add_backup_suggestion_link"=>array(
					"margin"=>"0px",
					"padding"=>"0",
					"line-height"=>"20px",
					"color"=>"#555555",
					"font-size"=>"14px"
				)
			)
		);
		$this->option = $options + $secondary;
		$this->backup_dir = sprintf(
			'%s/%s',
			$this->option['_path'],
			'backups'
		);
		$this->json_path = sprintf(
			'%s/%s',
			$this->option['_path'],
			'json'
		);
		$this->post_request();
	}

	public function load(){
		echo $this->backup_table();
	}

	private function get_all_dir_files(){
		$objects = new \RecursiveIteratorIterator(
			    new \RecursiveDirectoryIterator($this->option['_root']),
			    \RecursiveIteratorIterator::SELF_FIRST
		);
		foreach ($objects as $file => $object) {
		    $basename = $object->getBasename();
		    if ($basename == '.' or $basename == '..') {
		        continue;
		    }
		    $fileData[] = $object->getPathname();
		}
		return $fileData;
	}

	private function backup_table(){
		if($this->requests("GET","addbackup")=="true"){
			$content = sprintf(
				'<a href="%s" style="%s">&larr;&nbsp;&nbsp;%s</a>', 
				$this->option['slug']['home'],
				$this->arrayToStyle($this->option['css']['addlink']),
				$this->option['lang']['back']
			);
			$filex = json_encode($this->get_all_dir_files());
			// echo "<pre>";
			// print_r($filex);
			// echo "</pre>";

			$add_backup_label = $this->arrayToStyle($this->option['css']['add_backup_label']);
			$add_backup_input_text = $this->arrayToStyle($this->option['css']['add_backup_input_text']);
			$content .= sprintf(
				'
				<h3 style="%s">%s</h3><div style="clear:both"></div>
				<form style="%s" action="%s" method="post">
				<label style="%s">%s:</label>
				<input type="text" name="aname" value="" style="%s" />
				<label style="%s">%s:</label>
				<input type="text" style="%s" id="bpath" name="bpath" value="%s" autocomplete="off" />
				<div style="%s" id="sugg"></div>
				<input type="submit" value="&#43;&nbsp;&nbsp;%s" style="%s" />
				</form>
				<script type="text/javascript">
				var sugg = document.getElementById("sugg");
				var bpath = document.getElementById("bpath");
				var json = JSON.parse(\'%s\');
				bpath.addEventListener("keyup", function(){
					var val = bpath.value;
					sugg.style.display="block";
					var s = "";
					var inn = "";
					var is=1;
					for(var i = 0; i < json.length; i++)
					{
						s = json[i].startsWith(val);
						if(s){
							inn += "<a href=\'javascript:void(0)\' style=\'%s\' onclick=\'cha(\""+json[i]+"\")\'>"+json[i] + "</a><br />";
							is++;
							if(is>19){ break; }
						}
					}
					sugg.innerHTML = inn;
					console.log(json);
				});
				function cha(c){
					bpath.value = c;
					sugg.style.display="none";
				}		
				</script>
				',
				$this->arrayToStyle($this->option['css']['add_backup_header']),
				$this->option['lang']['addBackup'],
				$this->arrayToStyle($this->option['css']['add_backup_form']),
				$this->option['slug']['addlink'],
				$add_backup_label,
				$this->option['lang']['addBackupTitle'],
				$add_backup_input_text,
				$add_backup_label,
				$this->option['lang']['chooseDir'], 
				$add_backup_input_text,
				$this->option['_root']."/",
				$this->arrayToStyle($this->option['css']['add_backup_suggestion']),
				$this->option['lang']['addlink'],
				$this->arrayToStyle($this->option['css']['add_backup_button']),
				$filex,
				$this->arrayToStyle($this->option['css']['add_backup_suggestion_link'])
			);
		}else{
			$content = sprintf(
				'<a href="%s" style="%s">&#43;&nbsp;&nbsp;%s</a>', 
				$this->option['slug']['addlink'],
				$this->arrayToStyle($this->option['css']['addlink']),
				$this->option['lang']['addlink']
			);

			$content .= sprintf(
				'<table style="%s" cellspaceing="10" cellpadding="10">
				<tr style="%s">
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
				</tr>
				%s
				</table>',
				$this->arrayToStyle($this->option['css']['table']), 
				$this->arrayToStyle($this->option['css']['table.tr.head']), 
				$this->option['lang']['name'], 
				$this->option['lang']['backup'], 
				$this->option['lang']['date'], 
				$this->option['lang']['action'], 
				$this->load_tgzs()
			);
		}
		

		return $content;
	}

	private function load_tgzs(){
		$out = '';
		$command = 'ls '.$this->json_path.' 2>&1';
		$shell = shell_exec($command);
		$files = explode(".json", $shell);
		$files = array_map('trim', $files);
		$jArray = array();
		foreach ($files as $val) {
			if(!empty($val) && $val != ""){
				$json = sprintf(
					'%s/%s.json',
					$this->json_path, 
					$val
				);				
				if(file_exists($json)){
					$jArray[] = json_decode(file_get_contents($json), true);
				}				
			}
		}
		$jArray = array_reverse($jArray);
		foreach ($jArray as $j) {
			$download = sprintf(
				'%s/%s/%s',
				$this->backup_dir, 
				$j['date'], 
				$j['filename']
			);

			$out .= '<tr>';
			$out .= sprintf('<td>%s</td>', $j['name']);
			$out .= sprintf('<td>%s</td>', $j['backup']);
			$out .= sprintf('<td>%s</td>', $j['date']);
			$backup_actions = $this->arrayToStyle($this->option['css']['backup_actions']);
			$out .= sprintf(
				'<td><a href="%s" style="%s" target="_blank">%s</a> / <a href="" style="%s">%s</a></td>',
				$download, 
				$backup_actions,
				$this->option['lang']['download'],
				$backup_actions,
				$this->option['lang']['delete']
			);
			$out .= '</tr>';
		}
		return $out;
	}

	private function post_request(){
		if($this->requests("POST","bpath") && $this->requests("POST","aname")){
			$backup = sprintf(
				'%s',
				$this->requests("POST","bpath")
			);

			$this->tgz($backup, $this->requests("POST","aname"));
		}
	}

	private function tgz($backup, $archive_name){
		if((is_dir($backup) || is_file($backup)) && !empty($archive_name)){
			$date = date("d-m-Y");
			$tgzname = date("H-i-s").".tgz";
			$sh = "mkdir '".$this->backup_dir."/".$date."'; ";
			$sh .= "tar czf '".$this->backup_dir."/".$date."/".$tgzname."' ".$backup."; ";
			$jjson = '{"name":"'.$archive_name.'","backup":"'.$backup.'","date":"'.$date.'","filename":"'.$tgzname.'"}';
			$sh .= "echo '".$jjson."' > ".$this->json_path."/".time().".json"; 
			shell_exec($sh);
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