<?php

/* SVN FILE: $Id: zip.php 28 2007-02-08 20:51:38Z Andy $ */

/**
 * Create Zip files with PHP
 *
 * PHP versions 4 and 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 */

/**
 * ZipComponent tweaked mildly by AD7six.
 *
 * @author Rochak Chauhan, Shaun Shull, RosSoft, Jon Bennett
 */

/**
 * Description for var
 *
 * @var type
 * @access public/private/protected
 */
class ZipComponent {
	var $compressedData= array ();
	var $centralDirectory= array ();
	var $endOfCentralDirectory= "\x50\x4b\x05\x06\x00\x00\x00\x00";
	var $oldOffset= 0;

	function unix2DosTime($unixtime= 0) {
		$timearray= ($unixtime == 0) ? getdate() : getdate($unixtime);

		if ($timearray['year'] < 1980) {
			$timearray['year']= 1980;
			$timearray['mon']= 1;
			$timearray['mday']= 1;
			$timearray['hours']= 0;
			$timearray['minutes']= 0;
			$timearray['seconds']= 0;
		}

		return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) | ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
	}

	/**
	 * Creates a new directory in the zip file
	 * @param string $directoryName
	 */
	function addDirectory($directoryName) {
		$directoryName= r('\\', '/', $directoryName);

		$feedarrayRow= "\x50\x4b\x03\x04";
		$feedarrayRow .= "\x0a\x00";
		$feedarrayRow .= "\x00\x00";
		$feedarrayRow .= "\x00\x00";
		$feedarrayRow .= "\x00\x00\x00\x00";

		$feedarrayRow .= pack('V', 0);
		$feedarrayRow .= pack('V', 0);
		$feedarrayRow .= pack('V', 0);
		$feedarrayRow .= pack('v', strlen($directoryName));
		$feedarrayRow .= pack('v', 0);
		$feedarrayRow .= $directoryName;

		$feedarrayRow .= pack('V', 0);
		$feedarrayRow .= pack('V', 0);
		$feedarrayRow .= pack('V', 0);

		$this->compressedData[]= $feedarrayRow;

		$newOffset= strlen(implode('', $this->compressedData));

		$addCentralRecord= "\x50\x4b\x01\x02";
		$addCentralRecord .= "\x00\x00";
		$addCentralRecord .= "\x0a\x00";
		$addCentralRecord .= "\x00\x00";
		$addCentralRecord .= "\x00\x00";
		$addCentralRecord .= "\x00\x00\x00\x00";
		$addCentralRecord .= pack('V', 0);
		$addCentralRecord .= pack('V', 0);
		$addCentralRecord .= pack('V', 0);
		$addCentralRecord .= pack('v', strlen($directoryName));
		$addCentralRecord .= pack('v', 0);
		$addCentralRecord .= pack('v', 0);
		$addCentralRecord .= pack('v', 0);
		$addCentralRecord .= pack('v', 0);
		$ext= "\x00\x00\x10\x00";
		$ext= "\xff\xff\xff\xff";
		$addCentralRecord .= pack('V', 16);

		$addCentralRecord .= pack('V', $this->oldOffset);
		$this->oldOffset= $newOffset;

		$addCentralRecord .= $directoryName;

		$this->centralDirectory[]= $addCentralRecord;
	}

	/**
	 * Adds new data to the zip
	 * @param string $data The content of the file to be added
	 * @param string $directoryName The destination name of the file within the zip
	 */
	function addData($data, $directoryName, $time= 0) {
		$directoryName= r('\\', '/', $directoryName);

		$dtime= dechex($this->unix2DosTime($time));
		$hexdtime= '\x' . $dtime[6] . $dtime[7] . '\x' . $dtime[4] . $dtime[5] . '\x' . $dtime[2] . $dtime[3] . '\x' . $dtime[0] . $dtime[1];
		eval ('$hexdtime = "' . $hexdtime . '";');

		$feedarrayRow= "\x50\x4b\x03\x04";
		$feedarrayRow .= "\x14\x00";
		$feedarrayRow .= "\x00\x00";
		$feedarrayRow .= "\x08\x00";
		$feedarrayRow .= $hexdtime;

		$uncompressedLength= strlen($data);
		$compression= crc32($data);
		$gzCompressedData= gzcompress($data);
		$gzCompressedData= substr(substr($gzCompressedData, 0, strlen($gzCompressedData) - 4), 2);
		$compressedLength= strlen($gzCompressedData);
		$feedarrayRow .= pack('V', $compression);
		$feedarrayRow .= pack('V', $compressedLength);
		$feedarrayRow .= pack('V', $uncompressedLength);
		$feedarrayRow .= pack('v', strlen($directoryName));
		$feedarrayRow .= pack('v', 0);
		$feedarrayRow .= $directoryName;

		$feedarrayRow .= $gzCompressedData;

		//$feedarrayRow .= pack('V',$compression);
		//$feedarrayRow .= pack('V',$compressedLength);
		//$feedarrayRow .= pack('V',$uncompressedLength);

		$this->compressedData[]= $feedarrayRow;

		//$newOffset = strlen(implode('', $this->compressedData));

		$addCentralRecord= "\x50\x4b\x01\x02";
		$addCentralRecord .= "\x00\x00";
		$addCentralRecord .= "\x14\x00";
		$addCentralRecord .= "\x00\x00";
		$addCentralRecord .= "\x08\x00";
		$addCentralRecord .= $hexdtime;
		$addCentralRecord .= pack('V', $compression);
		$addCentralRecord .= pack('V', $compressedLength);
		$addCentralRecord .= pack('V', $uncompressedLength);
		$addCentralRecord .= pack('v', strlen($directoryName));
		$addCentralRecord .= pack('v', 0);
		$addCentralRecord .= pack('v', 0);
		$addCentralRecord .= pack('v', 0);
		$addCentralRecord .= pack('v', 0);
		$addCentralRecord .= pack('V', 32);

		$addCentralRecord .= pack('V', $this->oldOffset);
		$this->oldOffset += strlen($feedarrayRow);

		$addCentralRecord .= $directoryName;

		$this->centralDirectory[]= $addCentralRecord;
	}

	/**
	 * Adds a new file to the zip
	 * @param string $file Path of the filename to be added
	 * @param string $directoryName The destination name of the file within the zip
	 */
	function addFile($file, $directoryName) {
		$this->addData(file_get_contents($file), $directoryName, filectime($file));
	}

	/**
	 * Returns the content of the zip file
	 * @return string Zip file content
	 */
	function getZippedfile() {
		$data= implode('', $this->compressedData);
		$controlDirectory= implode('', $this->centralDirectory);
		return $data .
		$controlDirectory .
		$this->endOfCentralDirectory .
		pack('v', sizeof($this->centralDirectory)) .
		pack('v', sizeof($this->centralDirectory)) .
		pack('V', strlen($controlDirectory)) .
		pack('V', strlen($data)) .
		"\x00\x00";
	}

	/**
	 * Saves the zip to a file
	 * @param string $fileName Destination file of the current zip
	 *
	 */
	function saveZip($fileName) {
		$fd= fopen($fileName, 'wb');
		$out= fwrite($fd, $this->getZippedfile());
		fclose($fd);
	}

	/**
	 * Downloads the current zip file to the browser
	 * @param string $filename The name of the zipfile that will see the browser
	 */
	function forceDownload($filename= null) {
		if (ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}

		$data= $this->getZippedfile();

		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename=' . $filename . ';');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . strlen($data));
		echo $data;
		exit (); //skip problems with views
	}
}
?>