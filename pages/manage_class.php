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
        html
        {
            font-size: 14px;
            padding-top: 56px;
        }
        
        @media (min-width: 768px)
        {
            html
            {
                font-size: 16px;
            }
        }
        .flex-container
        {
            padding-right: 0;
            margin: 0;
            list-style: none;

            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;

            -webkit-flex-flow: row wrap;
            justify-content: left;
            align-items: center;
        }
        .card-button
        {
            max-width: 15px;
            max-height: 15px;
        }
        .card
        {
            max-width: 18rem;   
        }

    </style>
  </head>

    <body>
        <?php include("../lib/php/header.php"); ?>
        
        <!--
        <ul class="flex-container">
            <button type="button" class="btn flex-item">Add Section</button>
            <button type="button" class="btn flex-item">View Statistics</button>
            <button type="button" class="btn flex-item">Add Administrator</button>
            <button type="button" class="btn flex-item">Assign Question</button>
        </ul>
        -->
        
        <div class="container-fluid">
            <h1>Class 1 </h1>
                <div class="card-deck text-center">
                    <div class="card text-white bg-secondary mb-3 box-shadow">
                        <div class="card-header">
                          <h4 class="my-0 font-weight-normal">Section 1</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title pricing-card-title">35 <small>/ 50</small></h1>
                            <ul class="list-unstyled mt-3 mb-4">
                              <li>Added: 1-12-18</li>
                            </ul>
                            <a href="#" class="btn btn-lg btn-block btn-primary">Enter Section</a>
                        </div>
                    </div>
                    
                    <div class="card text-white bg-secondary mb-3 box-shadow">
                        <div class="card-header">
                          <h4 class="my-0 font-weight-normal">Section 2</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title pricing-card-title">15 <small>/ 20</small></h1>
                            <ul class="list-unstyled mt-3 mb-4">
                              <li>Added: 1-12-18</li>
                            </ul>
                            <a href="#" class="btn btn-lg btn-block btn-primary">Enter Section</a>
                        </div>
                    </div>
                    
                    <div class="card text-white text-center bg-secondary mb-3 box-shadow">
                        <a href="#" class="btn btn-lg btn-success">Add Section</a>
                    </div>
                </div>
            
            <a href="#" class="btn btn-lg btn-success">Add New Class</a>
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
