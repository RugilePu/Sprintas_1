<?php
// action.php

if(isset($_POST["action"]))
{
    if($_POST["action"] == "fetch")
    {
        $folder = array_filter(glob('*'), 'is_dir');
        $output = '
        <table class="table table-bordered table-striped">
            <tr>
                <th>Folder name</th>
                <th>Total File</th>
                <th>Update</th>
                <th>Delete</th>
                <th>Upload</th>
                <th>View Uploaded File</th>
            </tr>
        ';

        if(count($folder) > 0)
        {
            foreach($folder as $name)
            {
                $output .= '
                 <tr>
                    <td><a href=?path=' . $path . '/' . $name . '>' . $name . '</a></td>
                    <td>'.(count(scandir($name)) - 2).'</td>
                    <td><button type="button" name="update" data-name="'.$name.'" class="update btn btn-warning btn-xs">Updeate</button></td>
                    <td><button type="button" name="delete" data-name="'.$name.'" class="delete btn btn-danger btn-xs">Delete</button></td>
                    <td><button type="button" name="upload" data-name="'.$name.'" class="upload btn btn-info btn-xs">Upload File</button></td>
                    <td><button type="button" name="view_files" data-name="'.$name.'" class="view_files btn btn-default btn-xs">View Files</button></td>
                 </tr>
                ';
            }
        }
        else
        {
            $output .= '
            <tr> 
                <td colspan="6> No Folder Found</td>
            </tr>
            ';
        }
        $output .= '</table>';
        echo $output; 
    }

    if($_POST["action"] == "create")
    {
        if(!file_exists($_POST["folder_name"]))
        {
            mkdir($_POST["folder_name"], 0777, true);
            echo 'Folder Created';
        }
        else
        {
            echo 'Folder Already Created';
        }
    }
    if($_POST["action"] == "change")
    {
        if(!file_exists($_POST["folder_name"]))
        {
            rename($_POST["old_name"], $_POST["folder_name"]);
            echo 'Thank you. The name has been changed';
        }
        else
        {
            echo 'Folder is already created';
        }
    }
    if($_POST["action"] == "fetch_files")
    {
        $file_data = scandir($_POST["folder_name"]);
        $output = '
        <table class="table table-bordered table-striped">
            <tr>
                <th>Image</th>
                <th>File Name</th>
                <th>Delete</th>
            </tr>
        ';
        foreach($file_data as $file)
        {
            if($file === '.' OR $file === '..')
            {
                continue;
            }
            else
            {
                $path = $_POST["folder_name"] . '/' . $file;
                $output .= '
                <tr>
                    <td><img src="'.$path.'" class="img-thumbnail" height="50" width="50"></td>
                    <td></td>
                    <td><button name="remove_file" class="remove_file btn btn-danger btn-xs" id="'.$path.'">Remove</button></td>
                </tr>
                ';
                $output .= '</table>';
                echo $output;
            }
        }
    }
    if($_POST["action"] == "remove_file")
    {
        if(file_exists($_POST["path"]))
        {
            unlink($_POST["path"]);
            echo 'File Deleted';
        }
    }
    if($_POST["action"] == "delete")
    {
        $files = scandir($_POST["folder_name"]);
        foreach($files as $file)
        {
            if($file == '.' || $file === '..')
            {
                continue;
            }
            else
            {
                unlink($_POST["folder_name"] . '/' . $file);
            }
        }
        if(rmdir($_POST["folder_name"]))
        {
            echo 'Folder Deleted';
        }
    }
}
?>