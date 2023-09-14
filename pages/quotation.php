<?php
include_once('../controllers/quo_function.php');
?>
<!doctype html>
<html lang="en">

<head>
    <title></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <form method="post" id="insert_form">

                <button type="submit" name="submit" id="submit_button" class="btn btn-primary">Insert</button>
                <input type="hidden" name="action" id="action" value="savedata" />

                <div class="row mt-3">
                    <div class="col">
                        <label class=" fw-bold">วันที่เสนอราคา</label>
                        <input type="date" name="quodate" id="quodate" class="form-control">
                    </div>
                    <div class="col">
                        <label class="fw-bold">ชื่อลูกค้า</label>
                        <input type="text" name="customer" id="customer" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="table-repsonsive">
                        <hr>
                        <table class="table" id="item_table">
                            <input type="hidden" id="idcount" value="0">
                            <tr>
                                <th><button type="button" class="btn btn-success btn-sm add" onclick="AddMasterDetail()">เพิ่มรายการ</button></th>
                                <th><label class="float-start fw-bold">รายการ</label></th>
                                <th><label class="float-end fw-bold">ราคา</label></th>
                                <th><label class="float-end fw-bold">จำนวน</label></th>
                                <th><label class="float-end fw-bold">รวม</label></th>
                            </tr>
                        </table>
                        <table class="table">

                            <tr>
                                <td colspan="4">
                                    <label class="fw-bold">หมายเหตุ</label>
                                    <textarea name="remark" id="remark" cols="50" rows="5" class="form-control"></textarea>
                                </td>
                                <td>
                                    <div class="row mt-2">
                                        <div class="col"><label class="float-end fw-bold">รวม</label></div>
                                        <div class="col"><input type="text" name="total_amount" id="total_amount" class="form-control text-end" readonly></div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col"><label class="float-end fw-bold">ส่วนลด</label></div>
                                        <div class="col"><input type="text" name="discount_amount" id="discount_amount" onkeyup="caltotal()" class="form-control text-end"></div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col"><label class="float-end fw-bold">รวมก่อนภาษี</label></div>
                                        <div class="col"><input type="text" name="total_after_discount" id="total_after_discount" class="form-control  text-end" readonly></div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col"><label class="float-end fw-bold">ภาษี</label></div>
                                        <div class="col"><input type="text" name="vat_amount" id="vat_amount" class="form-control text-end" readonly></div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col"><label class="float-end fw-bold">สุทธิ</label></div>
                                        <div class="col"><input type="text" name="net_amount" id="net_amount" class="form-control text-end" readonly></div>
                                    </div>
                                </td>
                            </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </form>
        </div>
    </div>
</body>


</html>
<script>
    $(document).ready(function() {
        AddMasterDetail();
        caltotal()
    });

    function AddMasterDetail() {

        var idcount = document.getElementById("idcount").value;
        stre = "<tr id='listrow" + idcount + "'>";
        stre = stre + "<td><button type='button' class='btn btn-danger btn-sm' onclick='removeFormField(" + idcount + "); return false;'>X</button></td>";
        stre = stre + "<td><select class='form-control input-sm item' name='item[]' id='item" + idcount + "' data-idcount='" + idcount + "' >" +
            "<option value=''>Please Select</option>" +
            "<?php echo fetch_product($connect); ?></select></td>";
        stre = stre + "<td><input class='form-control input-sm price text-end' id='price" + idcount + "' placeholder='price'  name='price[]'  onkeyup='calprice(" + idcount + ")' /></td>";
        stre = stre + "<td><input class='form-control input-sm qty text-end' id='qty" + idcount + "' placeholder='qty'  name='qty[]' onkeyup='calprice(" + idcount + ")' /></td>";
        stre = stre + "<td><input class='form-control input-sm total text-end' id='total" + idcount + "' placeholder='total'  name='total[]' readonly/></td>";
        stre = stre + "</tr>";

        $("#item_table").append(stre);
        idcount++;
        document.getElementById("idcount").value = idcount;
    }

    function removeFormField(idcount) {
        $('#listrow' + idcount).remove();
    }

    $(document).on("change", ".item", function(e) {
        var select = $(this);
        var item_id = select.val();
        var idcount = select.data("idcount");
        $.ajax({
            url: "../controllers/quo_action.php",
            method: "POST",
            data: {
                action: 'getdetail',
                product_id: item_id
            },
            dataType: "json"
        }).done(function(hasil) {
            $("#price" + idcount).val(hasil.product_price);
        });
    });

    function calprice(idcount) {

        var price = document.getElementById("price" + idcount).value;
        var qty = document.getElementById("qty" + idcount).value;

        let sum = price * qty;
        document.getElementById("total" + idcount).value = sum;
        caltotal()
    }

    function caltotal() {
        var nidcount = document.getElementById("idcount").value;
        let total_amount = 0;
        for (let i = 0; i < nidcount; i++) {

            var total = document.getElementById("total" + i).value;

            if (total == '') {
                total = 0;
            }

            total_amount += parseInt(total);
        }

        document.getElementById('total_amount').value = total_amount;
        vat = 0.07;
        let discount_amount = document.getElementById('discount_amount').value

        let sumdis = total_amount - discount_amount;
        let after_discount = document.getElementById('total_after_discount').value = sumdis.toFixed(2)

        let calvat = after_discount * vat;
        let vat_amount = document.getElementById('vat_amount').value = calvat.toFixed(2);

        let totalnet = sumdis + calvat;
        let net_amount = document.getElementById('net_amount').value = totalnet.toFixed(2)


    }

    $('#insert_form').on('submit', function(event) {

        event.preventDefault();

        let error = '';

        if ($('#quodate').val() == '') {
            error += "<li>กรุณาเลือกวันที่</li>";
        }
        if ($('#customer').val() == '') {
            error += "<li>กรุณากรอกชื่อลูกค้า</li>";
        }

        product_count = 1;
        $("select[name='item[]']").each(function() {
            if ($(this).val() == '') {
                error += "<li>กรุณาเลือกสินค้าในรายการที่ " + product_count + "</li>";
            }
            product_count += 1;
        });

        price_count = 1;
        $('.price').each(function(index) {
            if ($(this).val() == '') {
                error += "<li>กรุณากรอกราคาในรายการที่ " + price_count + "</li>";
            }
            price_count += 1;
        });

        quantity_count = 1;
        $('.qty').each(function(index) {
            if ($(this).val() == '') {
                error += "<li>กรุณากรอกจำนวนในรายการที่ " + quantity_count + "</li>";
            }
            quantity_count += 1;
        });


        let form_data = $(this).serialize();
        if (error == '') {
            $.ajax({
                url: "../controllers/quo_action.php",
                method: "POST",
                data: form_data,
                beforeSend: function() {
                    console.log(form_data);
                },
                success: function(data) {
                    console.log(data);
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Your work has been saved',
                        showConfirmButton: false,
                        timer: 1500,
                    }).then(function() {
                        location.reload();
                    });

                }
            })
        } else {
            Swal.fire(error);
            $('#error').html('<div class="alert alert-danger"><ul>' + error + '</ul></div>');
        }
    });
</script>