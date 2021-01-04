<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprintas 1</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- <style>
    td, th, tr {
        border: 1px solid;
        padding: 10px;
        margin: 0;
    }
    th {
        background-color: grey;
    }
    </style> -->
</head>
<body>
    <?php
        /* $path = isset($_GET['path']) ? $_GET['path'] : './';
        $contents = scandir($path);
        print('<table style="width:100%">
            <tr>
                <th>Type</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>');
        foreach($contents as $content){
            print('<tr><td>' . is_dir($content). '</td>');
            print('<td>' . 
                '<a href=?path=' . $path . '/' . $content . '>' . $content . '</a>' 
                . '</td><td>Delete</td></tr>');
        }
        print('</table>'); */
    ?>
    <br /><br />
    <div class="container">
        <h2 align="center">Folder List</h2>
        <br />
        <div align="right">
            <button type="button" name="create_folder" id="create_folder" class="btn btn-success">Create</button>
        </div>
        <div id="folder_table" class="table_responsive">

        </div>
    </div>
    
</body>
</html>

<!-- MODAL FOR CREATING A FOLDER -->
<div id="folderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span id="change_title">Create Folder</span></h4>
            </div>
            <div class="modal-body">
                <p>Enter Folder Name<input type="text" name="folder_name" id="folder_name" class="from-control" /></p>
                <input type="hidden" name="action" id="action" />
                <input type="hidden" name="old_name" id="old_name" />
                <input type="button" name="folder_button" id="folder_button" class="btn btn-info" value="Create" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FOR UPLOADING FILE IN FOLDER -->
<div id="uploadModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span id="change_title">Upload File</span></h4>
            </div>
            <div class="modal-body">
                <form method="post" id="upload_form" enctype="multipart/form-data">
                <p>Select Image
                <input type="file" name="upload_file" /></p>
                <br />
                <input type="hidden" name="hidden_folder_name" id="hidden_folder_name" />
                <input type="submit" name="upload_button" class="btn btn-info" value="Upload" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FOR LISTING FILES -->
<div id="filelistModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span id="change_title">Files List</span></h4>
            </div>
            <div class="modal-body" id="file_list">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

    load_folder_list();

    function load_folder_list()
    {
        var action = "fetch";
        $.ajax({
            url : "action.php",
            method: "POST",
            data:{action:action},
            success:function(data)
            {
                $('#folder_table').html(data);
            }
        })
    }

    $(document).on('click', '#create_folder', function(){
        $('#action').val('create');
        $('#folder_name').val('');
        $('#folder_button').val('Create');
        $('#old_name').val('');
        $('#change_title').val('Create Folder');
        $('#folderModal').val('show');
    });

    $(document).on('click', '#folder_button', function(){
        var folder_name = $('#folder_name').val();
        var action = $('#action').val();
        var old_name = $('#old_name').val();
        if(folder_name != '')
        {
            $.ajax({
                url:"action.php",
                method: "POST",
                data:{folder_name:folder_name, old_name:old_name, action:action},
                success:function(data)
                {
                    $('#folderModal').modal('hide');
                    load_folder_list();
                    alert(data);
                }
            })
        }
        else
        {
            aler("Enter Folder Name");
        }
    });
    $(document).on('click', '.update', function(){
        var folder_name = $(this).data("name");
        $('#old_name').val(folder_name);
        $('#folder_name').val(folder_name);
        $('#action').val("change");
        $('#folder_button').val('Update');
        $('#change_title').text("Change Folder Name");
        $('#folderModal').modal("show");
    });

    $(document).on('click', '.upload', function(){
        var folder_name = $(this).data("name");
        $('#hidden_folder_name').val(folder_name);
        $('#uploadModal').modal('show');
    });

    $('#upload_form').on('submit', function(){
        $.ajax({
            url:"upload.php",
            method: "POST",
            data:new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            success:function(data)
            {
                load_folder_list();
                aler(data);
            }
        })
    });

    $(document).on('click', '.view_files', function(){
        var folder_name = $(this).data("name");
        var action = "fetch_files";
        $.ajax({
            url:"action.php",
            method: "POST",
            data:{action:action, folder_name:folder_name},
            success:function(data)
            {
                $('#file_list').html(data);
                $('#filelistModal').modal('show');
            }
        })
    })

    $(document).on('click', '.remove_file', function(){
        var path = $(this).attr("id");
        var action = "Remove_file";
        if(confirm("Are you want to remove this item?"))
        {
            $.ajax({
                url: "action.php",
                method: "POST",
                DATA:{path:path, action:action},
                success: function (data)
                {
                    alert(data);
                    $('#filelistModal').modal('hide');
                    load_folder_list();
                }
            })
        }
        else
        {
            return false;
        }
    });

    $(document).on('click', '.delete', function(){
        var folder_name = $(this).data("name");
        var action = "delete";
        if(confirm ("Are you sure you want to remove it?"))
        {
            $.ajax({
                url: "action.php",
                method:"POST",
                data:{folder_name:folder_name, action:action},
                success:function(data)
                {
                    load_folder_list();
                    alert(data);
                }
            });
        }
    })
});
</script>