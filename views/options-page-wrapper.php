<div class="wrap">
	
	<div id="icon-options-general" class="icon32"></div>
	<h2>CSV Me File Manager</h2>
	
	<div id="poststuff">
	
		<div id="post-body" class="metabox-holder columns-2">
		
			<!-- main content -->
			<div id="post-body-content">

					<!-- file upload form   -->
					<form method="post" action="" enctype="multipart/form-data">
							<!--<input type="hidden" name="csv_url_form_submitted" value="Y">-->
							<?php wp_nonce_field('csv_me-get_url','nonced_url_form_submitted'); ?>
									Enter in a url for a csv:<br />
									<input name="url" id="url" type="text" value="" class="regular-text" />
									<input class="button-secondary" type="submit" name="csv_submit_url" value="Submit" />
									<br /><br />Or upload a csv.
									<br /><input type="file" name="csv_file" />
									<input class="button-secondary" type="submit" name="csv_save" value="Save" />
							
					</form>
					<br/>
					<br/>

				<?php 

					//display current files and form to delete them if necessary
					$thelist = '<form method="post" action=""><table ><tr><th>File Name</th> <th>Column Names</th> <th>Row Count</th> <th>Delete</th></tr>';
					$file_count = 0;
					if ($handle = opendir($added_files_dir)) {
						    while ($file = readdir($handle))
						    {
						    	
						    	$filepath = $added_files_dir.$file;
								$csv = readCSV($filepath);
								$cols = $csv[0];
								$col_names_str = '';
								
								//get all colum names
								for ($i=0; $i < count($cols); $i++) { 
									$col_names_str .= esc_html($cols[$i]).', ';
									
								}
								
						        if (strtolower(substr($file, strrpos($file, '.') + 1)) == 'csv')
						        {
						        	$file_count++;
						            $thelist .= '<tr>
						            				<td style="vertical-align:top; width:200px; text-align:center;">'.$file.'</td>
						            				<td style="vertical-align:top; width:200px; text-align:center;">'.$col_names_str.'</td>
						            				<td style="vertical-align:top; text-align:center;">'.count($csv).'</td> 
						            				<td style="vertical-align:top; text-align:center;"> <input type="checkbox" name="delete[]" value="'.$filepath.'"> </td></tr>';
						        }
						    }
						    closedir($handle);

						    //check if any csv files and display message or finish form
						    if ($file_count < 1) {
						    	$thelist = '</table></form> <h4> No CSV files uploaded yet.';
						    }else{
						    	$thelist .= '<tr>
						    				<td></td>
						    				<td></td>
						    				<td></td>
						    				<td> <input id="delete_button" class="button-secondary" type="submit" value="Delete Selected Files" /> </td></tr></table></form>';
						    }
						    
						    echo $thelist;
						 

					}else{
						echo "Could not open directory ".$upload_dir['path'];
					}

				 ?>
			</div> <!-- post-body-content -->
			
		</div> <!-- #post-body .metabox-holder .columns-2 -->
		
		<br class="clear">
	</div> <!-- #poststuff -->
	
</div> <!-- .wrap -->