<?php
class ConfigsController extends AppController {

	var $name = 'Config';
	//var $helpers = array('Html', 'Form','Js','Javascript');
	var $components = array('Configurate.Zip'); 

	function editConfig($fileName = null)
	{
		$offset = 0;
		$confArray = array();
		$buffer['text'] ="";
		$combuff['comment'] = "";
		if (!empty($this->data)) {
			$fileOut ="";
			foreach($this->data['config'] as $key => $value)
			{
				if(substr($key,0,7)=='comment')
				{
					$fileOut .= $value;
				}
				elseif($key=="fileName")
				{
					$fileName = $value;
				}
				else
				{
					$fileOut .= base64_decode($key).",".$value.");";
				}
			}
			$filelast = new File(CONFIGS.$fileName);
			if ($filelast->write($fileOut)) {
				$filelast->close();
				$this->Session->setFlash(__('The Core.php has been saved', true));
				$this->redirect('/');
			} else {
				$this->Session->setFlash(__('The Core.php could not be saved. Please, try again.', true));
				$this->redirect('/');
			}
		}
		else
		{
			$file = new File(CONFIGS.$fileName);
			$readed = $file->read();
			$tokens = token_get_all($readed);
			if (!defined('T_ML_COMMENT')) {
				define('T_ML_COMMENT', T_COMMENT);
			} else {
				define('T_DOC_COMMENT', T_ML_COMMENT);
			}
			foreach ($tokens as $token) {
				if (is_string($token)) {
					$buffer['text'] .= $token;
					$offset += strlen($token);
					if($token==";")
					{
						array_push($confArray,$buffer);
						$buffer['text'] ="";
					}
				} else {
					list($id, $text,$line) = $token;
					$offset += strlen($text);
					switch ($id) { 
					case T_COMMENT: 
					case T_ML_COMMENT:
					case T_DOC_COMMENT:
					case T_WHITESPACE:
					case T_OPEN_TAG:
					case T_CLOSE_TAG:
						$combuff['comment'] .= $text;
						array_push($confArray,$combuff);
						$combuff['comment'] = "";
						break;

					default:
						
						$buffer['text'] .= $text;
						break;
					}
				}
			}
		}
		$this->set(compact('confArray','fileName'));
	}
	
	function backupFile($fileName)
	{
		$time =date("dmyhms");
		$file = new File(CONFIGS.$fileName);
		$readed = $file->read();
		
		$backup = new File(BACKUPS."/".$fileName.$time);
		$backup->write($readed);
		//$this->Zip->addFile(APP."plugins/".$this->plugin."/backups/".$backup->name,"core.php");
		//$this->Zip->saveZip(APP."plugins/".$this->plugin."/backups/"."core.".$time.".zip");
		//$backup->delete();
		//$this->set(compact('readed'));
	}
	
	function deleteFile($filename = null)
	{
		$file = new File(BACKUPS.$filename);
		var_dump($file->delete());
	}
	
	function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
 }
	
	function deleteFolder($foldername = null)
	{
		$folder = new Folder(BACKUPS.$foldername);
		closedir(opendir(BACKUPS.$foldername));
		$this->rrmdir(BACKUPS.$foldername);
		$this->Session->setFlash(__("$foldername is deleted", true));
		$this->redirect(array('plugin' => 'configurate','controller' =>'configs', 'action' => 'recoverFiles'));
	}
	
	function configsList()
	{
		$folder = new Folder(CONFIGS);
		var_dump($folder->read());
	}
	
	function writeFile()
	{
		//var_dump($this->data);
		if (!empty($this->data)) {
			$file = new File(BACKUPS.$this->data['config']['fileName']);
			if ($file->write($this->data['config']['fileContent'])) {
				$this->Session->setFlash(__('The File has been saved', true));
				$this->redirect('/configurate/configs/editFile/'.$this->data['config']['fileName']);
			} else {
				$this->Session->setFlash(__('The File could not be saved. Please, try again.', true));
			}
		}
	}
	
	function backupConfigFolder()
	{
		$folder = new Folder();
		$time =date("dmyhms");
		$folder->copy(array('from' => CONFIGS, 'to' => BACKUPS."configFolderBackup".$time));
		$this->Session->setFlash(__('Configuration Folder is backed up to Configurate/Backups folder', true));
		$this->redirect(array('plugin' => 'configurate','controller' =>'configs', 'action' => 'recoverFiles'));
	}
	
	function backupSQL()
	{ 
	$this->Config->sqlDump();
			$this->Session->setFlash(__('SQL is backed up to Configurate/Backups folder', true));
			$this->redirect(array('plugin' => 'configurate','controller' =>'configs', 'action' => 'recoverFiles'));
			}
	
	
	function recoverFiles()
	{
		//var_dump(	Configure::read('debug'));
		$folder =& new Folder(BACKUPS);

		$path = $folder->pwd();
		//$result = $this->Config->sqlRecover("060311080302");
		//var_dump($path);
		$list = $folder->read(true,true,false);
		$folders = $list[0];
		$files = $list[1];
	  	$this->set(compact('files','folders'));
		
	}
	
	function recoverSQL($time = null)
	{
		$result = $this->Config->sqlRecover($time);
		$this->Session->setFlash(__("SQL is recovered from $time", true));
		$this->redirect(array('plugin' => 'configurate','controller' =>'configurate', 'action' => 'index'));
		
	}
	
	function add($parent = null) {
		if (!empty($this->data)) {
			$this->Type->create();
			if ($this->Type->save($this->data)) {
				$this->Session->setFlash(__('The Type has been saved', true));
				$this->redirect('/');
			} else {
				$this->Session->setFlash(__('The Type could not be saved. Please, try again.', true));
			}
		}
		else
		{
			$parents[0] = "[Top]";
			$types = $this->Type->generatetreelist(null,null,null," - ");
			if($types && !$parent) {
				foreach ($types as $key=>$value)
				$parents[$key] = $value;
			}
			else if($parent)
			{
				$onlyParent = $this->Type->read(null, $parent);
				$parents = null;
				//var_dump($onlyParent); //Burada seзili Type'a зocuk atamak iзin index deрeri giriyoruz
				$parents[$parent] = $onlyParent['Type']['title'];
			}
			$this->set(compact('parents'));
		}
		$categories = $this->Type->Category->find('list');
		$locations = $this->Type->Location->find('list');
		$statuses = $this->Type->Status->find('list');
		$this->set(compact('parents','categories', 'locations','statuses'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Type', true));
			$this->redirect('/');
		}
		if (!empty($this->data)) {
			if ($this->Type->save($this->data)) {
				$this->Session->setFlash(__('The Type has been saved', true));
				$this->redirect('/');
			} else {
				$this->Session->setFlash(__('The Type could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Type->read(null, $id);
			$parents[0] = "[Top]";
			$types = $this->Type->generatetreelist(null,null,null," - ");
			if($types) {
				foreach ($types as $key=>$value)
				$parents[$key] = $value;
			}
			$this->set(compact('parents'));
		}
		$categories = $this->Type->Category->find('list');
		$locations = $this->Type->Location->find('list');
		$statuses = $this->Type->Status->find('list');
		$this->set(compact('categories','locations','statuses'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Type', true));
			$this->redirect('/');
		}
		if ($this->Type->delete($id)) {
			$this->Session->setFlash(__('Type deleted', true));
			$this->redirect('/');
		}
	}

}
?>