<?php
  include('functions.php');

  $query_tablelist = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$database'";
  $tablelist = mysqli_query($db, $query_tablelist) or die(mysqli_error($db));
  $row_tablelist = mysqli_fetch_assoc($tablelist);
  $totalRows_tablelist = mysqli_num_rows($tablelist);

  $table = e($_GET['name']);

  $query_tablelist2 = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$table'";
  $tablelist2 = mysqli_query($db, $query_tablelist2) or die(mysqli_error($db));
  $row_tablelist2 = mysqli_fetch_assoc($tablelist2);
  $totalRows_tablelist2 = mysqli_num_rows($tablelist2);

  $rrr1 = "";
  $rrr12 = "";
  $tableQuery = "";
  $cid = "";
  $rrr2 = "";
  $cid = @$_GET['q'];
  $keyColumn = "";
  $uv_arr = [];

  if ($totalRows_tablelist2 > 0) {
    do {
        
      $rrr1.=$row_tablelist2['COLUMN_NAME'].",";
      $rrr2.="`".$table."`".".".$row_tablelist2['COLUMN_NAME']." LIKE '%$cid%' OR ";
      if ($row_tablelist2['COLUMN_KEY']=="PRI") {
        $keyColumn=$row_tablelist2['COLUMN_NAME'];	
      }

    }
    while ($row_tablelist2 = mysqli_fetch_assoc($tablelist2));
  }
  $tableQuery= substr($rrr1, 0, -1);
  $SercatableQuery= substr($rrr2, 0, -4);

  $coluMs = explode(",", $tableQuery);

  $query_total = "SELECT * FROM `$table`";
  $total = mysqli_query($db, $query_total) or die(mysqli_error($db));
  $row_total = mysqli_fetch_assoc($total);
  $totalRows_total = mysqli_num_rows($total);


  $query_datastyle1 = "SELECT * FROM `$table`";
  $datastyle1 = mysqli_query($db, $query_datastyle1) or die(mysqli_error($db));
  $row_datastyle1 = mysqli_fetch_assoc($datastyle1);
  $totalRows_datastyle1 = mysqli_num_rows($datastyle1);

  $date=time();
    
  $filename=$table . $date . ".xls";

  header("Cache-control: private");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Description: File Transfer");
  header("Content-Type: application/vnd.ms-excel");
  header("Content-disposition: attachment; filename=$filename");
  header("Expires: 0");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>MYSQL Report Generator</title>
  </head>
  <body>
    <table width="100%" border="1" cellpadding="5" cellspacing="0">
      <thead>
       <tr>
        <?php 
          foreach ($coluMs as $value) { 
            if ($value!=="id"&&$value!=="farm_pic"&&$value!=="farmer_pic"&&$value!=="project_id"&&$value!=="device"&&$value!=="seen_as_notification"&&$value!=="deleted") { ?>            
          <th>
            <?php echo split_string($value); ?>
          </th>
          <?php }}?>              
        </tr>
      </thead>
      <tbody>
        <?php do { ?>
        <tr>
          <?php foreach ($coluMs as $value) { 
            if ($value!=="id"&&$value!=="farm_pic"&&$value!=="farmer_pic"&&$value!=="project_id"&&$value!=="device"&&$value!=="seen_as_notification"&&$value!=="deleted") { ?>                
          <td>
            <?php echo split_string($row_datastyle1[$value]); ?>
          </td>
          <?php }}?>                
        </tr>
        <?php } while ($row_datastyle1 = mysqli_fetch_assoc($datastyle1)); ?>
      </tbody>
    </table>
  </body>
</html>