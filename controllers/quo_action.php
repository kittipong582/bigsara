<?php
include_once('../connect.php');

$action = $_POST['action'];

if ($action == 'savedata') {

    if (isset($_POST['item'])) {

        $Now = new DateTime('now', new DateTimeZone('Asia/Bangkok'));
        $DateTime =  $Now->format('Y-m-d H:i:s');


        $sql_getid = "SELECT MAX(quantion_id) as quantion_id FROM tbl_quantion_detail";
        $res_getid = $connect->query($sql_getid);
        $row = $res_getid->fetch();

        $quantion_id = $row['quantion_id'];


        if ($quantion_id == '') {
            $quantion_id = 'QID00001';
        } else {
            $quantion_id++;
        }


        for ($count = 0; $count < count($_POST['item']); $count++) {

            $sql_detail = "INSERT INTO `tbl_quantion_detail`(`quantion_id`, `product_id`, `list_order`, `quantity`, `unit_price`, `amount`) 
                VALUES (:quantion_id,:product_id,:list_order,:quantity,:unit_price,:amount)";

            $statement = $connect->prepare($sql_detail);

            $statement->execute(
                array(
                    ':quantion_id'  =>    $quantion_id,
                    ':product_id'   =>    $_POST["item"][$count],
                    ':list_order'   =>    "1",
                    ':quantity'     =>    $_POST["qty"][$count],
                    ':unit_price'   =>    $_POST["price"][$count],
                    ':amount'       =>    $_POST["total"][$count]
                )
            );
        }

        echo $sql_getno = "SELECT MAX(quotation_no) as quotation_no FROM tbl_quantation_head";
        $res_getno = $connect->query($sql_getno);
        $row_no = $res_getno->fetch();

        $quantion_no = $row_no['quotation_no'];

        $date = substr(date("Y") + 543, -2) . date("m");
        if ($quantion_no == '') {
            $quantion_no = 'QTD' . $date . '0001';
        } else {
            $last = substr($quantion_no, -4);
            $quantion_no = 'QTD' . $date . '' . $last . '';
            $quantion_no++;
        }
        echo $quantion_no;

        $sql_head = "INSERT INTO `tbl_quantation_head`(`quotation_id`, `create_datetime`, `quotation_no`, `customer_name`, `remark`, `total_amount`, `discount_amount`, `total_after_discount`, `vat_amount`, `net_amount`, `quantation_date`) 
        VALUES (:quotation_id,:create_datetime,:quotation_no,:customer_name,:remark,:total_amount,:discount_amount,:total_after_discount,:vat_amount,:net_amount,:quantation_date)";

        $statement = $connect->prepare($sql_head);


        $statement->execute(
            array(
                ':quotation_id'  =>     $quantion_id,
                ':create_datetime'  =>    $DateTime,
                ':quotation_no'  =>    $quantion_no,
                ':customer_name'  =>    $_POST["customer"],
                ':remark'  =>    $_POST["remark"],
                ':total_amount'  =>    $_POST["total_amount"],
                ':discount_amount'  =>   $_POST["discount_amount"],
                ':total_after_discount'  =>    $_POST["total_after_discount"],
                ':vat_amount'  =>    $_POST["vat_amount"],
                ':net_amount'  =>    $_POST["net_amount"],
                ':quantation_date'  =>   $_POST['quodate'],
            )
        );

        $result = $statement->fetch();

        if (isset($result)) {

            echo $quantion_no;
        }
    }
}

if ($action == 'getreport') {


    $firstdate = $_POST['firstdate'];
    $todate = $_POST['todate'];
    $sql_report = "SELECT * FROM tbl_quantation_head WHERE quantation_date BETWEEN '$firstdate' AND '$todate'";
    $result = $connect->query($sql_report);
    while ($row = $result->fetch()) {
        echo $list = '  
             <tr>       
                  <td>' . $row['quotation_no'] . '</td>  
                  <td>' . $row['quantation_date'] . '</td>  
                  <td>' . $row['create_datetime'] . '</td>  
                  <td>' . $row['customer_name'] . '</td>  
                  <td>' . $row['net_amount'] . '</td>  
                  <td><button type="button" id="' . $row['quotation_no']  . '" class="btn btn-danger btn-xs delete">EDIT</button></td>  
             </tr>  
             ';
    }
}

if ($action == 'getdetail') {

    $product_code = $_POST['product_id'];
    $sql_product = "SELECT * FROM tbl_product WHERE product_id ='$product_code' ";

    $result = $connect->query($sql_product);
    while ($row = $result->fetch()) {
        $product_name = $row['product_name'];
        $product_price = $row['product_price'];

        $detail = array(
            "product_name" => $product_name,
            "product_price" => $product_price
        );
        echo json_encode($detail);
    }
    /*
    foreach ($result as $row) {

        $product_name = $row['product_name'];
        $product_price = $row['product_price'];

        $detail = array(
            "product_name" => $product_name,
            "product_price" => $product_price
        );
        echo json_encode($detail);
    }
    */
}
