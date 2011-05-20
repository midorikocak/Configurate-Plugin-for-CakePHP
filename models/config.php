<?php
define("BACKUPS",     APP."plugins/configurate/backups/");
class config extends AppModel {

	var $name = 'config';
	var $useTable = false;

	function sqlDump()
    { //buraya backup klasörü gelecek
		//var_dump(exec(CAKE.'console/cake schema dump -write filename.sql'));
        //var_dump($this->query("SHOW TABLES"));
		$tables = $this->query("SHOW TABLES");
		///Configure::load('Database');
		//var_dump(Configure::read('debug'));
		//var_dump($this->database);
		$dbConfig = $this->getDataSource()->config;
		//var_dump($dbConfig['database']);
		$time =date("dmyhms");
		$backup = new Folder();
		$backup->create(APP."plugins/configurate/backups/"."sqlBackup".$time);
		
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($tables)); //ARRAY FLATTENER!!! PHP SPL KUTUPHANESI! 
		foreach($it as $v) {
		//echo $v, " ";
		$path =  str_replace('\\', '/', APP);
		$theQuery = "SELECT * 
		INTO OUTFILE '".$path."plugins/configurate/backups/"."sqlBackup".$time."/".$v.".txt'
		FROM ".$v.";
		";
		$this->query($theQuery);
		}
		//return $this->query($sql);
    }
	
	function sqlRecover($time)
	{
	$tables = $this->query("SHOW TABLES");
	///Configure::load('Database');
	//var_dump(Configure::read('debug'));
	//var_dump($this->database);
	$dbConfig = $this->getDataSource()->config;
	//var_dump($dbConfig['database']);
	
	$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($tables)); //ARRAY FLATTENER!!! PHP SPL KUTUPHANESI! 
	foreach($it as $v) {
	//echo $v, " ";
	$path =  str_replace('\\', '/', APP);
	$truncate = "TRUNCATE TABLE ".$v.";"; 
	$theQuery = "LOAD DATA INFILE '".$path."plugins/configurate/backups/"."sqlBackup".$time."/".$v.".txt'INTO TABLE ".$v.";
	
	";
	$this->query($truncate);
	$this->query($theQuery);
	}
	}

/*
function afterSave() {
   $this->_cacheNav();
}
 
function afterDelete() {
   $this->_cacheNav();
}
 
function _cacheNav() {
$types = $this->find('threaded',array('order' => 'order ASC'));
//Cache::config(null, array('engine'=>'File', 'path'=>CACHE));
//Cache::set(array('duration' => 7200));
Cache::set(array('duration' => '+1 year'));
Cache::write('types', $types);
}*/
	
}
?>