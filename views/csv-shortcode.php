<table id='csv_me_shortcode_table' class="tablesorter">
	<thead>
		<tr>
<?php
	//print headers
	

	foreach ($headers as $col_header) {
		 echo sprintf( "<th class='%s'> $col_header </th>", $icons_theme );
	}
?>
	</tr>
	</thead>
	<tbody>

<?php 		
	for ($i=$row_start; $i <= $row_end; $i++) { 
		$line = array();
		
		//get only the specified columns from each line of file_array
		for ($j=0; $j < count($col_nums); $j++) { 
			$col_index = $col_nums[$j];
			array_push($line, $file_array[$i][$col_index]);
		}
		
		//create html table for each line
		$line_str = '<tr>';
		foreach ($line as $value) {
			$line_str .= "<td> $value </td>";
		}
		$line_str .= '</tr>';
		echo $line_str;

	}
?>
	</tbody>
</table>