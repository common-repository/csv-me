<?php
	//if uninstall not called from WordPress exit
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	    exit();
	}

	//delete all uploaded files and remove csv-me uploads directory
	rrmdir(WP_CONTENT_DIR."/uploads/csv-me/");
	function rrmdir($dir) {
	   if (is_dir($dir)) {
	     $objects = scandir($dir);
	     foreach ($objects as $object) {
	       if ($object != "." && $object != "..") {

		         if (is_dir($dir."/".$object)) {
		         	rmdir($dir."/".$object); 
		         }else {
		         	unlink($dir."/".$object);
		         }
	       }
	     }
	     reset($objects);
	     rmdir($dir);
	    }
	}
?>