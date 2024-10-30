<!--<table>
             <tr>
                <td style='width: 100px'>Enter in a URL</td>
                <td><input type='text' name='url' value='<?php echo $csv_url ;?>'/></td>
            </tr>
            <tr>
                <td>Or </td>
            </tr>
             <tr>
                <td style='width: 100px'>Upload a File:</td>
                <td><input type='file' name='csv_file' value=''/> </td>
            </tr>
            
</table>-->
<label for="upload_image">
    <input id="url" type="text" size="36" name="url" value="<?php echo $csv_url ;?>" /> 
    <input id="upload_image_button" class="button" type="button" value="Upload File" />
    <input id="save_button" class="button" type="button" value="Save File" />
    
    <br />Enter a URL or upload a csv.

</label>
<input id="save_button" class="button" type="button" value="Save File" />