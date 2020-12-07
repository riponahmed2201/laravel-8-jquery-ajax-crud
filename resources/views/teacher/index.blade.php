<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel 8 Ajax Jquery CRUD</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
</head>
<body>

    <div class="" style="padding: 30px;">
        <div class="container">
            <h2 style="color:red;">
                <marquee behavior="" direction=""> Laravel 8 Ajax Crud Application</marquee>
            </h2>
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            All Teacher
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Title</th>
                                        <th>Institute</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <span id="addTitle">Add New Teacher</span>
                            <span id="updateTitle">Update Teacher</span>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" class="form-control" placeholder="Enter Name">
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" id="title" class="form-control" placeholder="Enter Job Position ">
                                <span class="text-danger" id="titleError"></span>
                            </div>
                            <div class="form-group">
                                <label for="institute">Institute</label>
                                <input type="text" id="institute" class="form-control" placeholder="Enter Institute">
                                <span class="text-danger" id="instituteError"></span>
                            </div>
                            <div class="form-group">
                                <input type="hidden" id="id">
                                <button id="addBtn" onclick="addData()" class="btn btn-primary">Add</button>
                                <button id="updateBtn" onclick="updateData()" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/poper_min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>

    <script>
        //show or hide add or update teacher title 
        $('#addTitle').show();
        $('#updateTitle').hide();

        //show or hide add or update button
        $('#addBtn').show();
        $('#updateBtn').hide();

        // SweetAlert2 
        const Toast = Swal.mixin({
                        toast:true,
                        position:'top-end',
                        icon:'success',
                        showConfirmbutton: false,
                        timer:3000
                    });

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });

        function allData(){
            $.ajax({
                type:"GET",
                dataType:"json",
                url:"teacher/all",
                success:function(response){
                    // console.log(data);
                    var data = "";
                    var i = 1;
                    $.each(response, function(key, value){
                        // console.log(value.title);
                        data = data + "<tr>"
                        data = data + "<td>" + i++ + "</td>"
                        data = data + "<td>" + value.name + "</td>"
                        data = data + "<td>" + value.title + "</td>"
                        data = data + "<td>" + value.institute + "</td>"
                        data = data + "<td>"
                        data = data + "<button class='btn btn-primary' onclick='editData("+ value.id  +")'>Edit</button>"
                        data = data + "<button class='btn btn-danger' onclick='deleteData("+ value.id  +")'>Delete</button>"
                        data = data + "</td>"
                        data = data + "<tr>"
                        data = data+ "</tr>"
                    })
                    $('tbody').html(data);
                }
            });
        }
        allData();

        function clearData(){
            $('#name').val('');
            $('#title').val('');
            $('#institute').val('');

            // clear all error when submit form
            $('#nameError').text('');
            $('#titleError').text('');
            $('#instituteError').text('');
        }

       function addData(){
            var name = $('#name').val();
            var title = $('#title').val();
            var institute = $('#institute').val();

            $.ajax({
                type:"POST",
                dataType:"json",
                data:{name:name, title:title, institute:institute},
                url:"teacher/store",
                success:function(data){
                    clearData();
                    allData();

                    // success message
                    Toast.fire({
                            type:'success',
                            title:'Teacher information successfully saved.',
                        });
                },
                error:function(error){
                    // console.log(error);
                    $('#nameError').text(error.responseJSON.errors.name);
                    $('#titleError').text(error.responseJSON.errors.title);
                    $('#instituteError').text(error.responseJSON.errors.institute);
                }
            });
       }


       // edit data 
      function editData(id){
        // alert(id);
        $.ajax({
            type:"GET",
            dataType:"json",
            url:"teacher/edit/"+id,
            success:function(data){
                //show or hide add or update teacher title 
                $('#addTitle').hide();
                $('#updateTitle').show();

                //show or hide add or update button
                $('#addBtn').hide();
                $('#updateBtn').show();

                // console.log(data);
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#title').val(data.title);
                $('#institute').val(data.institute);
            }
        });
       }

       // update data
       function updateData(){
            var id = $('#id').val();
            var name = $('#name').val();
            var title = $('#title').val();
            var institute = $('#institute').val();

            $.ajax({
                type:"POST",
                dataType:"json",
                data:{name:name, title:title, institute:institute},
                url:"teacher/update/"+id,
                success:function(data){

                    //show or hide add or update teacher title 
                    $('#addTitle').show();
                    $('#updateTitle').hide();

                    //show or hide add or update button
                    $('#addBtn').show();
                    $('#updateBtn').hide();

                    clearData();
                    allData();

                    // success message
                    Toast.fire({
                            type:'success',
                            title:'Teacher information successfully updated.',
                        });
                },
                error:function(error){
                    $('#nameError').text(error.responseJSON.errors.name);
                    $('#titleError').text(error.responseJSON.errors.title);
                    $('#instituteError').text(error.responseJSON.errors.institute);
                }
            });
       }

       //delete data
       function deleteData(id) {

                var url = "{{url('/teacher/destroy')}}"+"/"+id;
                Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete it!'
                })
            .then(function(result) {
                if (result.value) {
                    $.ajax({
                        url:url,
                        type:'GET',
                        contentType:false,
                        processData:false,
                        beforeSend:function () {
                            Swal.fire({
                            title: 'Deleting Data.......',
                            showConfirmButton: false,
                            html: '<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>',
                            allowOutsideClick: false
                        });
                        },
                        success:function (response) {
                            Swal.close();
                            console.log(response);
                            if (response==="success"){
                                Swal.fire({
                                title: 'Successfully Deleted',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                                }).then(function(result) {
                                        if (result.value) {
                                            window.location.reload();
                                        }
                                    });
                            }
                        },
                        error:function (error) {
                            Swal.close();
                            console.log(error);
                        }
                    })
                }
            });
            }
    </script>
</body>
</html>