<?php	
	function readCSV($csvFile){
			$file_handle = fopen($csvFile, 'r');
			if ($file_handle) {
				while (!feof($file_handle) ) {
				$line_of_text[] = fgetcsv($file_handle, 1024);
				}
			}else{
				return false;
			}
			
			fclose($file_handle);
			return $line_of_text;
	}

	function deleteCSV($filepath){
		if (file_exists($filepath)) {
			$did_delete = unlink($filepath);
		}else{
			$did_delete= false;
		}
		
		return $did_delete;
	}

?>