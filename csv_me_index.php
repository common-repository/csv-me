<?php

/*
 *	Plugin Name: CSV Me
 *	Plugin URI: http://www.zootowntech.com/wordpress-plugins/csv-me/
 *	Description: Add csv files via upload or from a web address and display anywhere on your site using shortcodes
 *	Version: 2.0
 *	Author: Scott LaForest
 *	Author URI: http://www.zootowntech.com
 *	License: GPLv2
 *
*/
	register_activation_hook(__FILE__, 'csv_me_activation');
	function csv_me_activation(){
			//if uploads directory does not exist, then create it and populate with example file
		if (!file_exists(WP_CONTENT_DIR."/uploads/csv-me/")) {
	    	mkdir(WP_CONTENT_DIR."/uploads/csv-me/", 0777, true);
	    	
		}
		if (!file_exists(WP_CONTENT_DIR."/uploads/csv-me/example-file.csv")) {
	    	
	    	$oldpath = WP_PLUGIN_DIR."/csv-me/example-file.csv";
	    	$newpath = WP_CONTENT_DIR."/uploads/csv-me/example-file.csv";
	    	if(file_exists($oldpath)){
	    		copy($oldpath, $newpath);
	    	}
	    	

		}
	}
	

	$added_files_dir = WP_CONTENT_DIR."/uploads/csv-me/";
	add_action( 'admin_menu', 'csv_me_create_menu' );
	add_action('wp_dashboard_setup', 'csv_me_dashboard_widget');
	function csv_me_create_menu() {
		
		//create a submenu under Tools
		add_management_page( 'CSV Me File Manager', 'CSV Me File Manager', 'manage_options', __FILE__, 'csv_me_options_page' );
		
	}
	function csv_me_dashboard_widget(){
		//create dashboard widget
		wp_add_dashboard_widget('csv_me_files_widget', 'CSV Me File Manager', 'csv_me_options_page');
	}

	

	function csv_me_options_page(){
		global $added_files_dir;
		require('csv-functions.php');
		if( !current_user_can( 'manage_options' ) ) {

			wp_die( 'You do not have sufficient permissions to access this page.' );

		}
	
		
		//check if a url was entered
		if( isset($_POST['nonced_url_form_submitted']) ) {

			//$hidden_field = esc_html( $_POST['csv_url_form_submitted'] );
			$file_types = array( 'text/comma-separated-values', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel', 'text/plain');

			//if( $hidden_field == 'Y' ) {
				if(isset($_POST['url']) && $_POST['url'] !=''){

					$csv_url = esc_url( $_POST['url'] );
					//get filename from url
					$filename = preg_split('/\//', $csv_url);
					$filename = $filename[count($filename)-1];
					
					//add csv extension if not on end
					preg_match('/\.csv$/', $filename, $matches);
					if (!$matches) {
						$filename .= '.csv';
					}
					$file = $added_files_dir.$filename;

					$result = wp_remote_get($csv_url);
					//get header content-type
					$header_type = $result['headers']['content-type'];
					$content_type  = preg_split('/;/', $header_type);
					$content_type = $content_type[0];
					
					if (is_wp_error($result)) {
						$error_string = $result->get_error_message();
						echo "<div class='updated settings-error'> Could not access $csv_url. $error_string</div>";

					}else{
						//make sure it is a csv file
						if (!in_array($content_type, $file_types)) {
							echo "<div class='updated settings-error'> Your file, $filename , does not seem to be a properly formatted CSV file and was not added to your available csv files.</div>";

						}
						else{//all is good so add the file
							$data = $result['body'];
							$fp = fopen($file, "w");
							fwrite($fp, $data);
							fclose($fp);
						}
					}
				
				}
				elseif (is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
					$filepath = $added_files_dir.$_FILES['csv_file']['name'];
					$filename = preg_split('/\//', $filepath);
					$filename = $filename[count($filename)-1];
					
					$fileTmpLoc = $_FILES["csv_file"]["tmp_name"];

					//****** finfo requires PHP 5.3 *******
					$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
					
					    if (!in_array(finfo_file($finfo, $fileTmpLoc), $file_types)) {
					    	echo "<div class='updated settings-error'> Your file, $filename , does not seem to be a CSV file and was not added to your available csv files.</div>";
					    }else{
					    	$moveResult = move_uploaded_file($fileTmpLoc, $filepath);
					    	if (!$moveResult) {
					    		echo "<div class='updated settings-error'> Error, could not save file.</div>";
					    	}
					    }
					
					finfo_close($finfo);
					
					
				}
			//}


		}

		if(isset($_POST['delete']))
		{
			foreach ($_POST['delete'] as $value) {
			
				$filepath = $value;
				$filename = preg_split('/\//', $filepath);
				$filename = $filename[count($filename)-1];
				/*
				$file_handle = fopen($filepath, 'r');
				fclose($file_handle);
				unlink($filepath);
				*/
				$is_deleted = deleteCSV($filepath);
				if ($is_deleted) {
					echo "<div class='updated settings-error'> Successfully deleted $filename.</div>";

				}
			}
		}

		
		
		require( 'views/options-page-wrapper.php' );


	//}
	}

	
	function csv_me_shortcode($atts, $content = null){
		global $post;
		global $added_files_dir;
		require('csv-functions.php');

		$defaults = array('name' =>'',
			'row_end' => 0,
			'row_start' => 1,
			'columns' => 'all',
			'sortable' => false,
			'header_style' => 'blue');
		$options = shortcode_atts($defaults, $atts);

		//get filename
		$filename = $options['name'];
		$filepath = $added_files_dir.$filename;

		/*	turn csv into array of line arrays
		*	each line is an array with firs line the headers, i.e.
		*	headerArray [head1, head2, ...],
		*	lineArray1 [data1, data2, ...],
		*	lineArray2 [data1, data2, ...],
		*	...
		*/
		$file_array = readCSV($filepath);
		
		//get specified columns or all columns
		if ($options['columns'] != 'all') 
		{
			$col_names = explode(',', $options['columns']);

			foreach ($col_names as $value) 
			{
				$value = trim($value);
			}
			$headers = $col_names;

			//get index of each column name
			$col_nums = array();
			for ($i=0; $i < count($headers) ; $i++) 
			{ 
				$current_col_num = array_search(trim($headers[$i]), $file_array[0]);
				
				//check if array search return false since 0 = false =  null for an integer
				if ($current_col_num === false) {
					$current_col_num = -1;
					array_splice($col_names, $i, 1);//remove column name since it is invalid
				}
				
				//only add to col_nums array if array_search returns a valid index
				if ($current_col_num >= 0) {
					array_push($col_nums, $current_col_num);
				}
				
			}
			$headers = $col_names;
			
		}
		else
		{
			$headers = $file_array[0];
			$col_nums = array_keys($file_array[0]);
		}
		
		//get specified rows
		$row_end = $options['row_end'];
		//handle invalid row end
		if ($row_end <= 0) {
			$row_end = count($file_array) - 1;
		}
		$row_start = $options['row_start'];
		//handle invalid row start
		if ($row_start <= 0) {
			//assumes first row is headers so starts on 2nd row
			$row_start = 1;
		}

		if ($row_start > $row_end) {
			$row_start = 1;
			$row_end = count($file_array) - 1;
		}


		//column name error handling
		if (empty($col_nums)) {
			//invalid column names so show all columns
			$headers = $file_array[0];
			$col_nums = array_keys($file_array[0]);
		}
		
		//check for sortable and enqueue scripts/styles if needed
		$is_sortable = $options['sortable'];
		if ('yes' == $is_sortable || 'Y' == $is_sortable || 'Yes' == $is_sortable || 'y' == $is_sortable ||
			'true' == $is_sortable || 'True' == $is_sortable || true === $is_sortable) {
			$is_sortable = (bool)true;
			$icons_theme = $options['header_style'];
		}else{
			$is_sortable = (bool)false;
			$icons_theme = '';
		}

		if ($is_sortable) {

			wp_register_script( 'tablesorter', plugins_url( 'tablesorter/jquery.tablesorter.js', __FILE__), array( 'jquery' ), false, false); 
			wp_register_script( 'csv_me_tablesorter', plugins_url( 'tablesorter/csv_me_tablesorter.js', __FILE__)); 
			wp_register_style( 'tablesorter_style', plugins_url( 'tablesorter/tablesorter_style.css', __FILE__), false, false);
			wp_enqueue_script('tablesorter');
			wp_enqueue_script('csv_me_tablesorter');
			wp_enqueue_style('tablesorter_style');
		}else{
			wp_deregister_script( 'tablesorter' );
			wp_deregister_script( 'csv_me_tablesorter' );
			wp_deregister_style( 'tablesorter_style' );
		}

		//show shortcode html and logic
		if ($file_array) {
			ob_start();
			require( 'views/csv-shortcode.php' );

			$content = ob_get_clean();
		}else{
			$content = "Error, could not read csv file.";
		}
		

		return $content;

}

add_shortcode('csv_me', 'csv_me_shortcode');






?>