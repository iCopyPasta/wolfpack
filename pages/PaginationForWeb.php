<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Teacher Center</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <!-- Custom styles for this template -->
    <style>
      html {
        font-size: 14px;
      }
      @media (min-width: 768px) {
      html {
        font-size: 16px;
      }
      }

      .container {
        max-width: 960px;
      }

      .pricing-header {
        max-width: 700px;
      }

      .card-deck .card {
        min-width: 220px;
      }

      .border-top { border-top: 1px solid #e5e5e5; }
      .border-bottom { border-bottom: 1px solid #e5e5e5; }

      .box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
    </style>
  </head>

  <?php
      $alertString="";
      if ($_SERVER["REQUEST_METHOD"] == "GET") {
        include_once('/Connection.php');

        // insert a bunch of classes
//        for($x = 100; $x < 300; $x++){
//          $newClass = new ClassCourse('%', $x, 'Olmstead Room '.$x, 'something', 'Capstone');
//          $newClass->insert();
//        }


        $connection = new Connection;
        $pdo = $connection->getConnection();

        // find out how many rows are in the table
        $sql = "SELECT COUNT(*) FROM class_course";
        $result = $pdo->prepare($sql);

        try{
          $result->execute();
        }catch (Exception $e){
          // fail JSON response
          $response = array();
          $response["message"] = "ERROR SELECTING: ".$e->getMessage();
          $response["success"] = 0;
          echo json_encode($response);
        }

        $r = $result->fetch(PDO::FETCH_NUM);
        $numrows = $r[0];
        echo 'numrows: '.$numrows.'<br>';

        // number of rows to show per page
        $rowsperpage = 10;
        // find out total pages
        $totalpages = ceil($numrows / $rowsperpage);
        echo 'rowsPerPage: '.$rowsperpage.'<br>';
        echo 'totalpages:  '.$totalpages.'<br>';

        // get the current page or set a default
        if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
          // cast var as int
          $currentpage = (int) $_GET['currentpage'];
        } else {
          // default page num
          $currentpage = 1;
        } // end if

        // if current page is greater than total pages...
        if ($currentpage > $totalpages) {
          // set current page to last page
          $currentpage = $totalpages;
        } // end if
        // if current page is less than first page...
        if ($currentpage < 1) {
          // set current page to first page
          $currentpage = 1;
        } // end if

        // the offset of the list, based on current page
        $offset = ($currentpage - 1) * $rowsperpage;

        // get the info from the db
        $sql = "SELECT class_id, class_course_number FROM class_course LIMIT $offset, $rowsperpage";
        $result = $pdo->prepare($sql);
        try{
          $result->execute();
        }catch (Exception $e){
          // fail JSON response
          $response = array();
          $response["message"] = "ERROR SELECTING: ".$e->getMessage();
          $response["success"] = 0;
          echo json_encode($response);
        }

        // while there are rows to be fetched...
        while ($list = $result->fetch(PDO::FETCH_ASSOC)) {
          // echo data
          echo $list['class_id'] . " : " . $list['class_course_number'] . "<br />";
        } // end while

        /******  build the pagination links ******/
        // range of num links to show
        $range = 3;

        // if not on page 1, don't show back links
        if ($currentpage > 1) {
          // show << link to go back to page 1
          echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";
          // get previous page num
          $prevpage = $currentpage - 1;
          // show < link to go back to 1 page
          echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";
        } // end if

        // loop to show links to range of pages around current page
        for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
          // if it's a valid page number...
          if (($x > 0) && ($x <= $totalpages)) {
            // if we're on current page...
            if ($x == $currentpage) {
              // 'highlight' it but don't make a link
              echo " [<b>$x</b>] ";
              // if not current page...
            } else {
              // make it a link
              echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
            } // end else
          } // end if
        } // end for

        // if not on last page, show forward and last page links
        if ($currentpage != $totalpages) {
          // get next page
          $nextpage = $currentpage + 1;
          // echo forward link for next page
          echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
          // echo forward link for lastpage
          echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>>></a> ";
        } // end if
        /****** end build pagination links ******/



//
////        $search = searchClassesByStudent(1, 10, 11);
//        $qJSON = json_decode($search);
//        // var_dump($qJSON, true);
//        // foreach($qJSON as $key => $value){
//        //   echo "$key => $value"."<br>";
//        // }
//        echo json_encode($qJSON);
      }

  ?>

  <body>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
      <div class="form-group">
        <label for="class_course_number">Class Course Number:</label>
        <input type="text" class="form-control" id="class_course_number" name="class_course_number">
      </div>
      <div class="form-group">
        <label for="location">Location:</label>
        <input type="text" class="form-control" id="location" name="location">
      </div>
      <div class="form-group">
        <label for="offering">Offering:</label>
        <input type="text" class="form-control" id="offering" name="offering">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../../../assets/js/vendor/popper.min.js"></script>
    <script src="../../../../dist/js/bootstrap.min.js"></script>
    <script src="../../../../assets/js/vendor/holder.min.js"></script>
    <script>
      Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
      });
    </script>
  </body>
</html>
