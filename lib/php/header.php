<header class="navbar navbar-expand-md navbar-dark fixed-top bg-dark" role="navigation">
  <a class="navbar-brand" href="../index.php">Pollato</a>
  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="/pages/manage_class.php">Class Manager</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="/pages/manage_questions.php">Question Manager</a>
      </li>        
    </ul>
    <span class="navbar-text">
      <?php if (isset($_SESSION['user'])) {
        // logged in
        echo $_SESSION['user']." <a href=\"logout.php\">Logout</a>"; 
        } else {
        // not logged in
        echo "Not logged in.";
        }
      ?>
    </span>
  </div>
</header>