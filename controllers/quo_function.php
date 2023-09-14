<?php
include_once('../connect.php');

function fetch_product($connect)
{

    $query = "SELECT * FROM tbl_product";
    $result = $connect->query($query);

    foreach ($result as $row) {
        echo "<option value=". $row['product_id'] .">".$row['product_name']."</option>";
    }


}

function getreport($connect)
{
    $sql_report = "SELECT * FROM tbl_quantation_head";
    $result = $connect->query($sql_report);

    foreach ($result as $row) {
        echo '  
             <tr>       
                  <td>' . $row['quotation_no'] . '</td>  
                  <td>' . $row['quantation_date'] . '</td>  
                  <td>' . $row['create_datetime'] . '</td>  
                  <td>' . $row['customer_name'] . '</td>  
                  <td>' . $row['net_amount'] . '</td>  
                  <td><button type="button" id="' . $row->net_amount . '" class="btn btn-danger btn-xs delete">EDIT</button></td>  
             </tr>  
             ';
    }

}

