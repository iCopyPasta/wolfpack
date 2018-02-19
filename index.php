<?php
    $page_title = "Pollato";
    $page_description = "";
    
    include("includes/header.php");
?> 

<main role="main">

  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron">
    <div class="container">
      <h1 class="display-3">iClicker++</h1>
      <h5 style="padding-left:2rem">A new way to teach.</h5>
      <p><a class="btn btn-primary btn-lg" href="Sign_up.php" role="button">Sign up &raquo;</a></p>
    </div>
  </div>

  <div class="container">
    <!-- Example row of columns -->
    <div class="row">
      <div class="col-md-6">
        <h2>Instructors</h2>
        <p>Explore the classroom of the future.</p>
        <p><a class="btn btn-secondary" href="Sign_in_teacher.html" role="button">Sign in as Teacher &raquo;</a></p>
      </div>
      <div class="col-md-6">
        <h2>Students</h2>
        <p>Learn like never before.</p>
        <p><a class="btn btn-secondary" href="Sign_in_student.php" role="button">Sign in as Student &raquo;</a></p>
      </div>

    </div>

    <hr>

  </div> <!-- /container -->

</main>

<?php
    $page_footer_text = "Credit: Twitter bootstrap 4.0 documentation. Working Demo.";
    
    include("includes/footer.php");
?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../../../assets/js/vendor/popper.min.js"></script>
    <script src="../../../../dist/js/bootstrap.min.js"></script>
  </body>
</html>

