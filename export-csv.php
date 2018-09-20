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

$header = "";
$data = "";
$date = time();
	
$select = "SELECT * FROM `$table`";
$export = mysqli_query($db, $select) or die("Sql error : " . mysqli_error($db));
$fields = mysqli_num_fields($export);

for ($i = 0; $i < $fields; $i++)
{
  $field_name = mysqli_fetch_field_direct($export , $i)->name;
  if ($field_name!=="id"&&$field_name!=="farm_pic"&&$field_name!=="farmer_pic"&&$field_name!=="project_id"&&$field_name!=="device"&&$field_name!=="seen_as_notification"&&$field_name!=="deleted") {
    $header .= split_string($field_name . "\t");
  }
}

while($row = mysqli_fetch_assoc($export))
{
  $line = '';
  foreach ($row as $k => $value)
  {
    if ($k!=="id"&&$k!=="farm_pic"&&$k!=="farmer_pic"&&$k!=="project_id"&&$k!=="device"&&$k!=="seen_as_notification"&&$k!=="deleted")
    {        
      if ((!isset($value)) || ($value == ""))
      {
        $value = "\t";
      }
      else
      {
        $value = str_replace('"' , '""' , $value);
        $value = '"' . split_string($value) . '"' . "\t";
      }
      $line .= $value;
    }
  }
  $data .= trim($line) . "\n";
}
$data = str_replace("\r", "", $data);
$filename = $table . $date . '.csv';

if ($data == "")
{
    $data = "\n(0) Records Found!\n";                        
}

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$data";
?>