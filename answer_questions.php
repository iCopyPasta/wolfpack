<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="" >
    <meta name="author" content="">


    <title>Pollato</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Custom styles for this template -->
    <style>

        body {
            padding-top: 3.5rem;
        }
    </style>
</head>

<body>
<?php include("includes/header.php"); ?>

    <div class = "container" >
        <form action="" method="">
            <div class="form-group">
                <h2><span class="badge badge-primary">Question 1:</span></h2>
                <p>How much wood could a woodchuck chuck if a woodchuck could chuck wood?</p>
            </div>
            <div class="form-group">
                <label for="answer"><h2><span class="badge badge-primary">Answer:</span></h2></label>
                <input type="" class="form-control" id="answer">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
        <hr>
    </div>

    <div class = "container" >
        <form action="" method="">
            <div class="form-group">
                <h2><span class="badge badge-primary">Question 2:</span></h2>
                <p>How much wood could a woodchuck chuck if a woodchuck could chuck wood?</p>
            </div>
            <label for="answer"><h2><span class="badge badge-primary">Answer:</span></h2></label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
                <label class="form-check-label" for="exampleRadios1">
                    42
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                <label class="form-check-label" for="exampleRadios2">
                    0010 1010
                </label>
            </div>
            <br>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
        <hr>
    </div>

<!--    code snippet for paging-->
<!--    <div class = "container">-->
<!--        <div class="d-flex">-->
<!--                <ul class="pagination mx-auto">-->
<!--                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>-->
<!--                    <li class="page-item"><a class="page-link" href="#">Next</a></li>-->
<!--                </ul>-->
<!--        </div>-->
<!--    </div>-->


</body>

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

