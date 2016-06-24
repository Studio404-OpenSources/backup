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
					"width"=>"calc(100% - 10px); -webkit-calc(100% - 10px); -moz-calc(100% - 10px)",
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
					"width"=>"calc(100% - 30px); -webkit-calc(100% - 30px); -moz-calc(100% - 30px)",
					"font-size"=>"16px",
					"line-height"=>"30px",
					"background-color"=>"#cccccc",
					"color"=>"#ffffff",
					"float"=>"left"
				),
				"add_backup_form"=>array(
					"margin"=>"0px 5px",
					"padding"=>"0px",
					"width"=>"calc(100% - 10px); -webkit-calc(100% - 10px); -moz-calc(100% - 10px)"
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
					"width"=>"calc(100% - 10px); -webkit-calc(100% - 10px); -moz-calc(100% - 10px)",
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
				),
				"success"=>array(
					"margin"=>"10px 0px",
					"padding"=>"0px",
					"width"=>"100%",
					"float"=>"left",
					"clear"=>"both"
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
		
	}

	public function load(){
		if(
			is_dir($this->option['_root']) && 
			is_dir($this->option['_path']) && 
			is_dir($this->backup_dir) && 
			is_dir($this->json_path) && 
			self::getPermition($this->option['_root'])==$this->option['uploadable_perm'] &&  
			self::getPermition($this->option['_path'])==$this->option['uploadable_perm'] &&  
			self::getPermition($this->backup_dir)==$this->option['uploadable_perm'] &&  
			self::getPermition($this->json_path)==$this->option['uploadable_perm'] && 
			function_exists('shell_exec')
		){
			$this->get_request();
			echo $this->backup_table();
		}else{
			echo sprintf(
				$this->option['lang']['errorMsgPerm'],
				$this->option['_root'],
				$this->option['_root'],
				$this->option['_path'],
				$this->option['_root'],
				$this->backup_dir,
				$this->option['_root'],
				$this->json_path,
				$this->option['uploadable_perm']
			);
			exit();
		}
	}

	private function backButton(){
		return sprintf(
			'<a href="%s" style="%s">&larr;&nbsp;&nbsp;%s</a>', 
			$this->option['slug']['home'],
			$this->arrayToStyle($this->option['css']['addlink']),
			$this->option['lang']['back']
		);
	}

	private function backup_table(){
		if(self::requests("GET","addbackup")=="true"){
			$content = $this->backButton();
			$filex = json_encode($this->get_all_dir_files());

			$add_backup_label = $this->arrayToStyle($this->option['css']['add_backup_label']);
			$add_backup_input_text = $this->arrayToStyle($this->option['css']['add_backup_input_text']);
			$content .= sprintf(
				'<h3 style="%s">%s</h3><div style="clear:both"></div>
				<form style="%s" action="%s" method="post">
				<label style="%s">%s: <font color="red">*</font></label>
				<input type="text" name="aname" value="" style="%s" />
				<label style="%s">%s: <font color="red">*</font></label>
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
				</table>
				<script type="text/javascript">
				function comf(g){
					if(confirm("%s")){
						location.href = g; 
					}
				}
				</script>',
				$this->arrayToStyle($this->option['css']['table']), 
				$this->arrayToStyle($this->option['css']['table.tr.head']), 
				$this->option['lang']['name'], 
				$this->option['lang']['backup'], 
				$this->option['lang']['date'], 
				$this->option['lang']['action'], 
				$this->load_tgzs(),
				$this->option['lang']['delBackup']
			);
		}
		

		return $content;
	}

	private function load_tgzs(){
		$out = '';
		$command = 'ls '.$this->json_path.' 2>&1';
		$shell = shell_exec($command);
		if(!empty($shell)){
			$files = explode(".json", $shell);
			$files = array_map('trim', $files);
			if(!empty($files) && count($files))
			{
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
				if(count($jArray)){
					$jArray = array_reverse($jArray);
					foreach ($jArray as $j) {
						$download = sprintf(
							'%s/%s/%s',
							$this->backup_dir, 
							$j['date'], 
							$j['filename']
						);
						if(!file_exists($download)){
							$download = "javascript:void(0);";
							$lang_download = $this->option['lang']['download_pendding'];
						}else{
							$lang_download = $this->option['lang']['download'];
						}

						$out .= '<tr>';
						$out .= sprintf('<td>%s</td>', $j['name']);
						$out .= sprintf('<td>%s</td>', $j['backup']);
						$out .= sprintf('<td>%s</td>', $j['date']);
						$backup_actions = $this->arrayToStyle($this->option['css']['backup_actions']);
						$remove_link = sprintf(
							'%s&d=%s&f=%s&j=%s',
							$this->option['slug']['removelink'], 
							$j['date'],
							$j['filename'],
							$j['json_file']
						);
						$out .= sprintf(
							'<td><a href="%s" style="%s" target="_blank">%s</a> / <a href="javascript:void(0)" onclick="comf(\'%s\')" style="%s">%s</a></td>',
							$download, 
							$backup_actions,
							$lang_download,
							$remove_link,
							$backup_actions,
							$this->option['lang']['delete']
						);
						$out .= '</tr>';
					}
				}
			}
		}
		return $out;
	}

	private function get_request(){
		if(self::requests("POST","bpath") && self::requests("POST","aname")){
			$backup = str_replace(
				array(
					";",
					"../",
					"$",
					"&"
				),
				"",
				sprintf(
					'%s',
					self::requests("POST","bpath")
				)
			);
			$this->tgz($backup, self::requests("POST","aname"));
		}else if(self::requests("GET","removebackup")=="true"){
			$rmFolder = sprintf(
				"%s/%s", 
				$this->backup_dir,
				self::requests("GET","d")
			);
			$rmFile = sprintf(
				"%s/%s",
				$rmFolder,
				self::requests("GET","f")
			);
			$rmJson = sprintf(
				"%s/%s.json",
				$this->json_path,
				self::requests("GET","j")
			);
			if(file_exists($rmFile) && file_exists($rmJson)){
				@unlink($rmJson);
				@unlink($rmFile);
				if(self::IsEmptyFolder($rmFolder)){
					@rmdir($rmFolder);
				}
			}
			self::url($this->option['slug']['home']);
		}
	}

	private function tgz($backup, $archive_name){
		if((is_dir($backup) || is_file($backup)) && !empty($archive_name)){
			$date = date("d-m-Y");
			$tgzname = date("H-i-s").".tgz";
			$jsonFileName = time();
			$jjson = sprintf(
				'{"name":"%s","backup":"%s","date":"%s","filename":"%s","json_file":"%s"}',
				$archive_name,
				$backup,
				$date,
				$tgzname,
				$jsonFileName
			);
			$sh = sprintf(
				"mkdir '%s/%s'; ",
				$this->backup_dir,
				$date
			);
			$sh .= sprintf(
				"tar czf '%s/%s/%s' '%s'; ",
				$this->backup_dir,
				$date,
				$tgzname,
				$backup
			);			
			$sh .= sprintf(
				"echo '%s' > '%s/%s.json'",
				$jjson, 
				$this->json_path,
				$jsonFileName
			); 
			shell_exec($sh);
			echo $this->backButton();
			echo sprintf(
				"<p style='%s'>%s</p>",
				$this->arrayToStyle($this->option['css']['success']),
				$this->option['lang']['success']
			);
			exit();
		}else{
			echo $this->option['lang']['errorMsg'];
			exit();
		}
	}

	private function get_all_dir_files(){
		try{
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
		}catch(Exception $e){
			echo $this->option['lang']['errorMsg']." - ".$e;
			exit();
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
			echo sprintf(
				'%s%s', 
				$this->option['lang']['errorMsg'],
				$e
			);
		}
		return $output;
	}

	private static function IsEmptyFolder($folder) {
		$glob = sprintf(
			'%s%s%s',
			$folder,
			DIRECTORY_SEPARATOR,
			"*"
		);
		return (count(
			array_diff(
				glob($glob), 
				Array(".", "..")
			)) == 0
		);
	}

	private static function url($url=""){
		if(empty($url)){
			echo '<meta http-equiv="refresh" content="0"/>';
		}else{
			echo '<meta http-equiv="refresh" content="0; url='.$url.'"/>';
		}
		exit();
	}

	private static function requests($type,$item){
		if($type=="POST" && isset($_POST[$item])){
			return filter_input(INPUT_POST, $item);
		}else if($type=="GET" && isset($_GET[$item])){
			return filter_input(INPUT_GET, $item);
		}else{
			return '';
		}
	}

	private static function getPermition($dir){
		if(is_dir($dir)){
			$fileperms = substr(
				sprintf(
					'%o', 
					fileperms($dir)
				), 
				-4
			);
			return $fileperms;
		}
		echo $this->option['lang']['errorMsg'];
		exit();
	}

}
?>