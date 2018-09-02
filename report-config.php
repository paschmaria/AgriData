<?php
  include('functions.php');
  include('fpdf/fpdf.php');

  if (isset($_POST['generate_PDF'])) {
    generate_PDF();
  }

  function ca($val) {
    global $db, $errors;
    
    if (isset($val)&&is_array($val)) {
      $new_val = array();
      foreach ($val as $key => $value) {
        array_push($new_val, mysqli_real_escape_string($db, $value));
      }
      return $new_val;
    } else {
      array_push($errors, "Please select Table column headings.");
    }
  }

  function cie($val) {
    if (preg_match("/email/", $val)) {
      return 61;
    } elseif (preg_match("/date_of_registration/", $val)) {
      return 52;
    } elseif (preg_match("/gender/", $val)) {
      return 20;
    } elseif (preg_match("/town/", $val)) {
      return 25;
    } else {
      return 35;
    }
  }

  function generate_PDF() {
    if (isset($_GET['name'])&&isset($_GET['id'])) {
      global $db, $errors, $project_name, $project_id, $alert;

      global  $header_bgcolor,
              $header_txtcolor,
              $header_border,
              $header_bdcolor,
              $header_bdwidth,
              $body_bgcolor,
              $body_txtcolor,
              $body_border,
              $body_bdcolor,
              $body_bdwidth,
              $body_fontname,
              $body_fontsize,
              $body_fontstyle,
              $stripped_rows;
    
      $project_name = e($_GET['name']);
      $project_id = e($_GET['id']);

      // receive all input values from the form. 
      // call the e() function to escape form values
      $header_bgcolor  = e($_POST['header_bgcolor']);
      $header_txtcolor = e($_POST['header_txtcolor']);
      $header_border   = e($_POST['header_border']);
      $header_bdcolor  = e($_POST['header_bdcolor']);
      $header_bdwidth  = e($_POST['header_bdwidth']);
      $body_bgcolor    = e($_POST['body_bgcolor']);
      $body_txtcolor   = e($_POST['body_txtcolor']);
      $body_border     = e($_POST['body_border']);
      $body_bdcolor    = $header_bdcolor;
      $body_bdwidth    = $header_bdwidth;
      $stripped_rows   = e($_POST['stripped_body_rows']);
      $body_fontname   = e($_POST['body_fontname']);
      $body_fontsize   = e($_POST['body_fontsize']);
      $body_fontstyle  = e($_POST['body_fontstyle']);
      $pdf_heading     = ca($_POST['pdf_heading']);
      
      // form validation: ensure that the form is correctly filled
      $n = $header_bgcolor;
      switch (empty($n)) {
        case $header_bgcolor:
          array_push($errors, "Table header background color is required.");
          break;
        
        case $header_txtcolor:
          array_push($errors, "Table header text color is required.");
          break;
        
        case $header_border:
          array_push($errors, "Table header border type is required.");
          break;
        
        case $header_bdcolor:
          array_push($errors, "Border color is required.");
          break;
        
        case $header_bdwidth:
          array_push($errors, "Border width is required.");
          break;
          
        case $body_bgcolor:
          array_push($errors, "Table body background color is required.");
          break;
        
        case $body_txtcolor:
          array_push($errors, "Table body text color is required.");
          break;
        
        case $body_border:
          array_push($errors, "Table body border type is required.");
          break;
        
        case $stripped_rows:
          array_push($errors, "Please select whether table rows should be stripped.");
          break;
          
        case $body_fontname:
          array_push($errors, "Please select font.");
          break;
        
        case $body_fontsize:
          array_push($errors, "Please select font-size.");
          break;
        
        case $body_fontstyle:
          array_push($errors, "Please select font-style.");
          break;
          
        default:
          break;
      }

      $header_border = $header_border==='zero'?0:1;
      $body_border = $body_border==='zero'?0:1;

      class PDF extends FPDF {
        // Page header
        function Header() {
          global $body_fontname,$body_fontsize,$header_bdcolor,$project_name;
          $time = date("F j, Y, g:i a", time());
          
          $this->SetDrawColor(hex_to_RGB($header_bdcolor));
          $this->SetFont($body_fontname,'',10);
          $this->SetX(20);
          $this->Cell(100,7,'Generated from AgriData on '.$time,0,0,'C');
          // Move to the right
          $this->Cell(20);
          // Title
          $this->SetFont($body_fontname,'B',$body_fontsize);
          $this->Cell(60,7,strtoupper(split_string($project_name)),1,0,'C');
          // Line break
          $this->Ln(15);
        }
        
        // Page footer
        function Footer() {
          global $body_fontname;

          // Position at 1.5 cm from bottom
          $this->SetY(-15);
          $this->SetFont($body_fontname,'I',8);
          // Page number
          $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }

        // Body
        function create_table($header,$data,$a,$b,$c,$d,$e) {
          global $project_name,
                 $header_bgcolor,
                 $header_txtcolor,
                 $header_border,
                 $header_bdcolor,
                 $body_bgcolor,
                 $body_txtcolor,
                 $body_border,
                 $body_fontname,
                 $body_fontstyle,
                 $body_fontsize,
                 $stripped_rows;
          
          // var_dump($a.$b.$c.$d.$e);
          // Colors, line width and bold font
          $this->SetFillColor(hex_to_RGB($a));
          $this->SetTextColor(hex_to_RGB($b));
          $this->SetDrawColor(hex_to_RGB($a));
          $this->SetLineWidth(.3);
          $this->SetFont($body_fontname,($body_fontstyle==='N'?'':$body_fontstyle),$body_fontsize);
          // Header
          for($i=0;$i<count($header);$i++)
            $this->Cell(cie($header[$i]),7,strtoupper(split_string($header[$i])),$header_border,0,'C');
          $this->Ln();
          // Color and font restoration
          $this->SetFillColor(hex_to_RGB($d));
          $this->SetTextColor(hex_to_RGB($e));
          $this->SetFont('');
          // Data
          $fill = false;
          foreach($data as $row) {
            $this->Ln();
            for($i=0;$i<count($header);$i++)
              $this->Cell(cie($header[$i]),6,ctype_digit($row[$header[$i]])
                                            ? number_format($row[$header[$i]])
                                            : ucwords($row[$header[$i]]),
                                            $body_border,0,'C');
              $this->Ln(3);
            $fill = !$fill;
          }
          // Closing line
          // $this->Cell(300,0,'','B');
        }
      }

      if (count($errors)===0) {
        $query = "SELECT * FROM $project_name";
        $results = mysqli_query($db, $query);
        $pdf = new PDF;
        $td_arr = array();
        foreach ($results as $key => $result) {
          array_push($td_arr, $result);
        }

        // var_dump($header_bgcolor.$header_txtcolor.$header_bdcolor.$body_bgcolor.$body_txtcolor);
        $pdf->AliasNbPages();
        $pdf->SetFont($body_fontname,($body_fontstyle==='N'?'':$body_fontstyle),$body_fontsize);
        $pdf->AddPage((count($pdf_heading)>5?'L':'P'),(count($pdf_heading)>7?'A3':'A4'));
        $pdf->create_table($pdf_heading,$td_arr,$header_bgcolor,$header_txtcolor,$header_bdcolor,$body_bgcolor,$body_txtcolor);
        $pdf->Output();
      } else {
        // var_dump($errors);
      }
    }
  }