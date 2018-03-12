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
            align-items: center;
        }

        .flex-item
        { 
            background: tomato;
            padding: 5px;
            width: 200px;
            height: 100px;
            margin-top: 100px;

            line-height: 50px;
            color: white;
            font-weight: bold;
            font-size: 1.7em;
            text-align: center;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
        }

    </style>
  </head>

    <body>
        <?php include("../lib/php/header.php"); ?>
        
        <ul class="flex-container">
            <button type="button" class="btn flex-item">View Section 1</button>
            <button type="button" class="btn flex-item">View Section 2</button>
            <button type="button" class="btn flex-item">View Section 3</button>
            <button type="button" class="btn flex-item">View Section 4</button>
            <button type="button" class="btn flex-item">View Section 5</button>
            <button type="button" class="btn flex-item">View Section 6</button>
        </ul>



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
