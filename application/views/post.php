<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
</head>
<body>

    <div class="container mt-5">
        <div class="col-sm-12 mb-3">
            <button class="btn btn-success" type="button" id="create-post" data-src="/index.php/post/store">Create</button>
        </div>
        <table class="table table-bordered table-striped">
        </table>
    </div>
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form action="">

        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Post</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <input type="hidden" name="id">
                    <div class="form-group mb-3">
                        <label >Title :</label>
                        <input class="form-control" name="title" type="text">
                    </div>
                    <div class="form-group mb-3">
                        <label >Description :</label>
                        <input class="form-control" name="description">
                    </div>
                    <div class="form-group mb-3">
                        <label >Content :</label>
                        <textarea class="form-control" name="content" cols="30" rows="10"></textarea>
                    </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.12.1/datatables.min.js"></script>
<script type="text/javascript">
$( document ).ready(function (){

    var table = $('.table').DataTable({
        ajax: '/index.php/post',
        serverSide: true,
        pagingType: 'full_numbers',
        columns: [
                { 
                    title: 'Id',
                    data: 'id',
                    className : 'id-column'
                },
                { 
                    title: 'Title',
                    data: 'title',
                    className : 'title-column'
                },
                {
                    title: 'Title',
                    data: 'description',
                    className : 'description-column'
                },
                {
                    title: 'Content',
                    data: 'content',
                    className : 'content-column'
                },
                {
                    title: 'Action',
                    data: 'action',
                    width: '20%',
                    bSortable: false,
                    className : 'action-column'
                },
                
            ],
    });
    var _formModal = $('#modal-form'),
        _form = $('form');



    $('#create-post').on('click', function (){
        let src = $(this).data('src');
        _formModal.modal('show');
        _form.attr('action', src)
    });


    $(document).on('click', '.update-post', function (event){
        event.preventDefault();
        _formModal.modal('show');
        let _this = $(this),
            parent = _this.closest('tr'),
            input_title = parent.children('.title-column').text(),
            input_description = parent.children('.description-column').text(),
            input_content = parent.children('.content-column').text(),
            input_id = _this.data('id'),
            url = _this.attr('href');
            
        _form.attr('action', url);
        _form.find('[name=id]').val(input_id);
        _form.find('[name=title]').val(input_title);
        _form.find('[name=description]').val(input_description);
        _form.find('[name=content]').val(input_content);
    })
    
    
    _form.on('submit', function(e){
        e.preventDefault();
        let _this = $(this),
            url = _this.attr('action'),
            data = _this.serializeArray();

        $.ajax({
            url: url,
            data: data,
            method: "post",
            success: function (response) {
                if(response.status == 1)
                {
                    table.ajax.reload();
                    _formModal.modal('hide');
                }
            }
        });

    });

    $(document).on('submit', '.delete-post', function(e){
        e.preventDefault();
        let _this = $(this),
            url = _this.attr('action'),
            data = _this.serializeArray();

        if (confirm("Remove this post")) {
            
            $.ajax({
                url: url,
                data: data,
                method: "post",
                success: function (response) {
                    console.log(response);
                    if(response.status == 1)
                    {
                        table.ajax.reload();
                    }
                }
            });
        }

    })

    _formModal.on('hidden.bs.modal', function () { 
        _form.find('[name=id]').val('');
        _form.find('[name=title]').val('');
        _form.find('[name=description]').val('');
        _form.find('[name=content]').val('');
        _form.attr('action', '');
    });
});

</script>
</body>
</html>
