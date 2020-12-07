
$(document).ready(function(){

// AJAX Request for Retrieved Data
function showData(){
    output = "";
    $.ajax({
        url:"retrieved.php",
        method:"GET",
        dataType:"json",
        success:function(data){
            // console.log(data[0].name);
            // console.log(data);
            if(data){
                x = data;
            }else{
                x = "";
            }
            for(i = 0 ; i <x.length; i++){
                // console.log(x[i].name);
                // console.log(x[i]);

                output += "<tr><td>" + x[i].id + "</td><td>" + x[i].name
                 + "</td><td>" + x[i].email + "</td><td>" + x[i].password 
                 + "</td> <td> <button class='btn btn-warning btn-sm btn-edit' data-sid=" + x[i].id + ">Edit</button> <button class='btn btn-danger btn-sm btn-delete' data-sid=" + x[i].id + "> Delete </button> </td></tr>";
            }
            $('tbody').html(output);
        }
    });
}

showData();

// AJAX Request for Insert Data
$("#btnadd").click(function(e){
    e.preventDefault();
    console.log("Save button clicked");
    let sid = $("#stuid").val();
    let nm = $("#nameid").val();
    let em = $("#emailid").val();
    let pw = $("#password").val();

    myData = {id:sid,name:nm, email:em, password:pw};
    // console.log(myData);

    $.ajax({
        url:"insert.php",
        method:"POST",
        data:JSON.stringify(myData),
        success:function(data){
            console.log(data);
            msg = "<div class='alert alert-dark mt-3'>"+ data + "</div>";
            $("#msg").html(msg);
            $("#myform")[0].reset();
            showData();
        },
    });

});

 // Ajax Request for Deleting Data
 $("tbody").on("click", ".btn-delete", function(){
     console.log("Deleted Data");
    let id = $(this).attr("data-sid");
    // console.log(id);
    myData = {id:id};
    myThis = this;
    $.ajax({
        url:"delete.php",
        method:"POST",
        data:JSON.stringify(myData),
        success:function(data){
            // console.log(data);

            if(data == 1){
                msg = "<div class='alert alert-dark mt-3'>Student Deleted Successfully</div>";
                $(myThis).closest("tr").fadeOut(); // when deleted the data then simply refresh the table. In ajax method this keyword does not work outside declare the this keyword myThis name variable 
            }else if(data == 0){
                msg = "<div class='alert alert-dark mt-3'>Unable Deleted Student</div>";
            }
            $("#msg").html(msg);
            // showData(); // when this function call and data deleted then table refresh
            
        },
    });
 });


  // Ajax Request for Editing Data
  $("tbody").on("click", ".btn-edit", function(){
        // console.log(" Edit Button Clicked");
        let id = $(this).attr("data-sid");
        // console.log(id);
        myData = {id:id};
        $.ajax({
            url:"edit.php",
            method:"POST",
            dataType:"json",
            data: JSON.stringify(myData),
            success: function(data){
                // console.log(data.name);
                $("#stuid").val(data.id);
                $("#nameid").val(data.name);
                $("#emailid").val(data.email);
                $("#password").val(data.password);
            },
        });
    });
});