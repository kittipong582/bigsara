<?php
include_once('../controllers/quo_function.php');
?>
<!doctype html>
<html lang="en">

<head>
  <title></title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">


</head>

<body>
  <div class="container">
    <div class="row mt-5">
      <div class="col">
        From <input type="date" id="firstdate" value="" class="form-control" data-date-format="DD MMMM YYYY" />
      </div>
      <div class="col">
        To<input type="date" id="todate" value="" class="form-control"data-date-format="DD MMMM YYYY"/>

      </div>
      <div class="col">
        <button class="btn btn-success mt-4" onclick="seachQuo()">Seach</button>
      </div>
      <div class="mt-3">
        <table class="table">
          <thead>
            <td>เลขที่</td>
            <td>วันที่เสนอราคา</td>
            <td>วันที่ทำรายการ</td>
            <td>ชื่อลูกค้า</td>
            <td>ยอดเสนอราคา</td>
            <td></td>
          </thead>

          <tbody id="datatable"></tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html>
<script>
  $(document).ready(function() {

    function formatDate(date) {
      var d = new Date(date),
        day = '' + d.getDate(),
        month = '' + (d.getMonth() + 1),
        year = '' + d.getFullYear();
      if (day.length < 2)
        day = '0' + day;

      if (month.length < 2)
        month = '0' + month;

      return [year, month, day].join('-');
    }

    function getFtstDay() {
      var date = new Date();
      return new Date(date.getFullYear(), date.getMonth(), 1)
    }

    let firstdate = document.getElementById('firstdate').value = formatDate(getFtstDay());
    let todate = document.getElementById('todate').value = formatDate(new Date());

    seachQuo();

  });

  function seachQuo() {

    let action = 'getreport';

    let firstdate = document.getElementById('firstdate').value;
    let todate = document.getElementById('todate').value;

    console.log(firstdate);
    console.log(todate);

    $.ajax({
      url: "../controllers/quo_action.php",
      method: "POST",
      data: {
        action: action,
        firstdate: firstdate,
        todate: todate
      },
      success: function(data) {
        console.log(data);
        $('#datatable').html(data);
      }
    })
  }
</script>