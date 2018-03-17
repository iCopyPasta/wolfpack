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

    </style>
  </head>

    <body>
        <?php include("../lib/php/header.php"); ?>
        
        <form>
            <div class="form-group">
            <label for="questionType">Type of Question</label>
            <select class="form-control" id="questionType">
              <option>Multiple Choice</option>
              <option>True/False</option>
              <option>Short Answer</option>
            </select>
          </div>
            <div class="form-group">
                <label for="questionBody">Question Body</label>
                <textarea class="form-control" id="qustionBody" rows="3" placeholder="Enter Question Body"></textarea>
            </div>
            
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" aria-label="Checkbox for following text input">
                    </div>
                </div>
                <textarea class="form-control" id="qustionAnswer1" rows="2" placeholder="Enter Answer"></textarea>
            </div>
            
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" aria-label="Checkbox for following text input">
                    </div>
                </div>
                <textarea class="form-control" id="qustionAnswer2" rows="2" placeholder="Enter Answer"></textarea>
            </div>
            
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" aria-label="Checkbox for following text input">
                    </div>
                </div>
                <textarea class="form-control" id="qustionAnswer3" rows="2" placeholder="Enter Answer"></textarea>
            </div>
            
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" aria-label="Checkbox for following text input">
                    </div>
                </div>
                <textarea class="form-control" id="qustionAnswer4" rows="2" placeholder="Enter Answer"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Submit</button>
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
