<?php

/**
 * Front End Controller
 */

require_once 'services/FileUtil.php';
require_once 'services/Converter.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	
	if ( isset($_FILES['file']) ) {
		
		$fileUtil = new FileUtil();
		
		$file = $_FILES['file'];
		
		if ( $fileUtil->validateUploadedFile($file) ) {
			
			$handle = fopen($file['tmp_name'], "r");
			
			if ( $handle !== FALSE ) {
				$out_arr = $fileUtil::extractData($handle);
			}
			
			if ( !empty($out_arr) ) {
				$converter = new Converter("1.0", "UTF-8");
				
				$xml_str = $converter->generateXML($out_arr);
				
				if ( !empty($xml_str) ) {
					$xml_file_output = 'output' . rand() . '.xml';
					$converter->save($xml_file_output);
					
					// Render response
					$file_location = $_SERVER["DOCUMENT_ROOT"] . '/dev/xmlcomposer/' . $xml_file_output;
					// var_dump($file_location);
					
					if (file_exists($file_location)) {
						// die('hola');
			            header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
			            header("Cache-Control: public"); // needed for i.e.
			            header("Content-Type: application/xml");
			            // header("Content-Transfer-Encoding: Binary");
			            header("Content-Length:".filesize($file_location));
			            // header("Content-Disposition: attachment; filename=file.zip");
			            readfile($file_location);
			            die();        
			        }
					
				}
				
			}
			
		}
		
	}
	
}

