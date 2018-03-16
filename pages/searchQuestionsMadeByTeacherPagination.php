  <?php
      $alertString="";
      if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['professor_id']) && !empty($_GET['professor_id'])) {
          $student_id = $_GET['professor_id'];
//          echo 'profID: ' . $professor_id . '<br>';

          include_once('/Connection.php');
          $connection = new Connection;
          $pdo = $connection->getConnection();

          // find out how many rows are in the table
          $sql = "SELECT COUNT(*)
                  FROM question, owns, professor_account
                  WHERE professor_account.professor_id = owns.professor_id
                    AND owns.question_id = question.question_id
                    AND professor_account.professor_id = :professor_id";

          $result = $pdo->prepare($sql);
          $result->bindValue(':professor_id', $professor_id);
          try {
            $result->execute();
          } catch (Exception $e) {
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR SELECTING: " . $e->getMessage();
            $response["success"] = 0;
            echo json_encode($response);
          }

          $r = $result->fetch(PDO::FETCH_NUM);
          $numrows = $r[0];
          echo 'numrows: ' . $numrows . '<br>';

          // number of rows to show per page
          $rowsperpage = 10;
          // find out total pages
          $totalpages = ceil($numrows / $rowsperpage);
          echo 'rowsPerPage: ' . $rowsperpage . '<br>';
          echo 'totalpages:  ' . $totalpages . '<br>';

          // get the current page or set a default
          if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
            // cast var as int
            $currentpage = (int)$_GET['currentpage'];
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
//          $sql = "SELECT class_id, class_course_number FROM class_course LIMIT $offset, $rowsperpage";

          $sql = "SELECT question.question_id, question.description, question.tags, question.asked, question.potential_answers, question.correct_answers
                  FROM question, owns, professor_account
                  WHERE professor_account.professor_id = owns.professor_id
                    AND owns.question_id = question.question_id
                    AND professor_account.professor_id = :professor_id
                    LIMIT $offset, $rowsperpage";

          $result = $pdo->prepare($sql);
          $result->bindValue(':professor_id', $professor_id);
          try {
            $result->execute();
          } catch (Exception $e) {
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR SELECTING: " . $e->getMessage();
            $response["success"] = 0;
            echo json_encode($response);
          }

          // while there are rows to be fetched...
          while ($list = $result->fetch(PDO::FETCH_ASSOC)) {
            // echo data
            echo $list['question_id'] . " : " . $list['class_course_number'] . "<br />";
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

        }
      }

  ?>