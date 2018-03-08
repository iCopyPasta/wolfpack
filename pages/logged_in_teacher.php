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
            padding: 0;
            margin: 0;
            list-style: none;

            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;

            -webkit-flex-flow: row wrap;
            justify-content: space-around;
        }

        .flex-item
        { 
            background: tomato;
            padding: 5px;
            width: 200px;
            height: 150px;
            margin-top: 10px;

            line-height: 150px;
            color: white;
            font-weight: bold;
            font-size: 3em;
            text-align: center;
        }

    </style>
  </head>

    <body>
        <?php include("../lib/php/header.php"); ?>
        
        <ul class="flex-container">
            <li class="flex-item">1</li>
            <li class="flex-item">2</li>
            <li class="flex-item">3</li>
            <li class="flex-item">4</li>
            <li class="flex-item">5</li>
            <li class="flex-item">6</li>
        </ul>

    
        <footer class="container">
            <p> Credit: Twitter bootstrap 4.0 documentation. Working demo for 2-13-18.</p>
        </footer>



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
