<?php
include("../connection.php");
$db = new dbObj();
$connection = $db->getConnstring();
$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method)
{
    case 'GET':
        //retrive product
        if(!empty($_GET["id"]))
        {
            $id=intval($_GET["id"]);
            get_employees($id);
        }
        else {
            get_employee();
        }
        break;

    case 'POST':
        //insert produk
        insert_employee();
        break;

    case 'PUT':
        //update produk
        $id=intval($_GET["id"]);
        update_employee($id);
        break;
    case 'DELETE':
        //update produk
        $id=intval($_GET["id"]);
        delete_employee($id);
        break;
    
    default:
    //invalid request method
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}

function get_employee(){
    global $connection;
    $query="SELECT * FROM tb_employee";
    $response=array();
    $result=mysqli_query($connection,$query);

    while($row=mysqli_fetch_array($result)){
        $response[]=$row;
    }
    header('Content-Type:application/json');
    echo json_encode($response);
}

function get_employees($id=0){
    global $connection;
    $query="SELECT * FROM `tb_employee`";

    if($id !=0){
        $query.="WHERE id=".$id." LIMIT 1";
    }
    $response=array();
    $result=mysqli_query($connection,$query);

    while($row=mysqli_fetch_array($result)){
        $response[]=$row;
    }
    header('Content-Type:application/json');
    echo json_encode($response);
}

function insert_employee() {
    global $connection;
    $data = json_decode(file_get_contents('php://input'), true);
    $employee_name=$data["employee_name"];
    $employee_salary=$data["employee_salary"];
    $employee_age=$data["employee_age"];
    
    $query="INSERT INTO tb_employee SET
    employee_name='".$employee_name."',
    employee_salary='".$employee_salary."',
    employee_age='".$employee_age."'";

    if(mysqli_query($connection, $query)){
        $response=array(
            'status' => 1,
            'status_message' =>'Employee Added Succesfully.'
        );
    }else{
        $response=array(
            'status' => 0,
            'status_message' =>'Employee Addition Failed.'
        );
        
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function update_employee($id) {
    global $connection;

    $post_vars =json_decode(file_get_contents("php://input"),true);
    $employee_name=$post_vars["employee_name"];
    $employee_salary=$post_vars["employee_salary"];
    $employee_age=$post_vars["employee_age"];
    $query="UPDATE tb_employee SET
    employee_name='".$employee_name."',
    employee_salary='".$employee_salary."',
    employee_age='".$employee_age."' WHERE id=".$id;

    if(mysqli_query($connection, $query)){
        $response=array(
            'status' => 1,
            'status_message' =>'Employee Update Succesfully.'
        );
    }else{
        $response=array(
            'status' => 0, 
            'status_message' =>'Employee Updation Failed.'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function delete_employee($id) {
    global $connection;
    $query="DELETE FROM tb_employee WHERE id=".$id;
    if(mysqli_query($connection, $query)) {
        $response=array(
            'status' => 1,
            'status_message' =>'Employee Deleted Succesfully.'
        ); 
        
    }else{
        $response=array(
            'status' => 0,
            'status_message' =>'Employee Deletion failed.'
        ); 
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>