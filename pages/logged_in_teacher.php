<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Student Center</title>

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

  <body>

    <?php include("../lib/php/header.php"); ?>

    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-3 mx-auto text-center">
      <h1 class="display-4">Student Center</h1>
      <p>Welcome back, David Rowe!</p>
    </div>
    <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center" style="max-width:960px">
      <h1 class="display-5">Search for a class to add</h1>
      <input class="form-control mr-sm-2" type="text" placeholder="Search" style="width:30%;display:inline; " aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </div>

    <div class="container">
    <h1 class="display-5 text-center">Current Classes</h1>
      <div class="card-deck mb-3 text-center">
        <div class="card mb-4 box-shadow">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">CMPSC 488</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">35 <small class="text-muted">/ 50</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>Added: 1-12-18</li>
            </ul>
            <button type="button" class="btn btn-lg btn-block btn-primary">Enter Class</button>
          </div>
        </div>
        <div class="card mb-4 box-shadow">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">CMPSC 462</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">0 <small class="text-muted">/ 10</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
                <li>Added: 1-14-18</li>
            </ul>
            <button type="button" class="btn btn-lg btn-block btn-primary">Enter Class</button>
          </div>
        </div>
        <div class="card mb-4 box-shadow">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">CMPSC 463</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">7 <small class="text-muted">/ 11</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>Added: 1-17-18</li>
            </ul>
            <button type="button" class="btn btn-lg btn-block btn-primary">Enter Class</button>
          </div>
        </div>
      </div>
      
      <h1 class="display-5 text-center">Invitations</h1>
      
      <div class="card-deck mb-3 text-center">
        <div class="card bg-secondary mb-4 text-white">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">CMPSC 425</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title"></small>Wanda Kunkel</h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>Received: 1-15-18</li>
            </ul>
            <button type="button" class="btn btn-lg btn-block btn-success">Join Class</button>
          </div>
        </div>
        <div class="card bg-secondary mb-4 text-white">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">CMPSC 430</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">Jeremy Blum</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>Received: 1-19-18</li>
            </ul>
            <button type="button" class="btn btn-lg btn-block btn-success">Join Class</button>
          </div>
        </div>
        
      </div>

      <footer class="container">
      <p> Credit: Twitter bootstrap 4.0 documentation. Working demo for 2-13-18.</p>
    </footer>
    </div>


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
