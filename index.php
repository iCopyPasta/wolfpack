 <?php //include this code at the top of all pages with special functionality for a logged in user
 session_start(); ?>

 <?php if (isset($_SESSION['accountType']) && $_SESSION['accountType'] == "student") { //logged in as student
        header("Location: pages/logged_in_student.php");
        }

        if  (isset($_SESSION['accountType']) && $_SESSION['accountType'] == "teacher") { //logged in as teacher
        header("Location: pages/logged_in_teacher.php");          
        }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Pollato</title>

    <!--Bootstrap core CSS-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    
    <!--Custom Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
   

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="css/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="css/contact-input-style.css">
    <link rel="stylesheet" href="css/hover-effect.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
  </head>
<!-- NAVBAR
================================================== -->
<body>
   


<nav class="navbar navbar-default top-bar affix" data-spy="affix" data-offset-top="250" >
    <div class="container">
        <!- Brand and toggle get grouped for better mobile display ->
        <div class="navbar-header page-scroll">
            <button data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" class="navbar-toggle" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="#particles-js" class="navbar-brand">Pollato</a>
        </div>
        <!- Collect the nav links, forms, and other content for toggling ->
        <div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
        <!- /.navbar-collapse ->
    </div>
    <!- /.container-fluid ->
</nav>


<!-- particles.js container -->
<section class="banner-sec" id="particles-js">
<div class="container">
<div class="jumbotron">
  <h1>Polling Made Easy<br><small>Experience Simplicity</small></h1>
  <a class="btn btn-tranparent" href="pages/Sign_up_teacher.php">Sign Up Now</a><br>
  <a class="btn btn-tranparent" href="pages/Sign_in_teacher.php">Sign In</a>
</div>
</div>
<div ></div>
</section>

<section class="about-sec" id="about">
<div class="container">
<div class="row">
<div class="col-md-6 pull-right">
<h2>Who We are</h2>
<p>Never in all their history have men been able truly to conceive of the world as one: a single sphere, a globe, having the qualities of a globe, a round earth in which all the directions eventually meet, in which there is no center because every points.</p>

<ul>
<li>It suddenly struck me that that tiny pea, pretty and blue, was the Earth. </li>
<li>I didn't feel like a giant.</li>
<li>The sky is the limit only for those who aren't afraid to fly!</li>
</ul>


<p>The airman's earth, if free men make it, will be truly round: a globe in practice, not in theory.</p>
<a class="btn btn-success" href="#">Get in Touch</a>
</div>
</div>
</div>

</section>

<section class="contact-sec" id="contact">
<div class="container text-center">
<h2>Keep in Touch!</h2>
<p>We want to explore. We’re curious people.
<br>
Look back over history, people have put their lives at stake to go out and explore … We believe in what we’re doing. Now it’s time to go.</p>

<div class="phone-sec">
1-800-265-3994   <span>|</span>   1-519-934-3037 
</div>

<a href="#" class="btn btn-tranparent">Request A Quote</a>
<a href="#" class="btn btn-tranparent">Contact Us</a>
</div>
</section>
  
<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-3">&copy;<script type="text/javascript">document.write(new Date().getFullYear());</script> Pollato.com</div>
            <div class="col-sm-6 col-md-3 pull-right text-right">Created with <i class="fa fa-heart"></i></div>
        </div>

    </div> 
</footer>

    


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/oppear_1.1.2.min.js"></script>
    <script src="js/particles.min.js"></script>
    <script src="js/app.js"></script>

    <script> 
	$(window).scroll(function () {
		var sticky = $('.navbar-brand'),
		    scroll = $(window).scrollTop();
			
			if (scroll >= 250) sticky.addClass('dark');
			else sticky.removeClass('dark');
			
	});
    </script>
    
     <script>
	$('a[href^="#"]').on('click', function(event) {

    var target = $( $(this).attr('href') );

    if( target.length ) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: target.offset().top
        }, 1000);
    }
    });

    </script>
    
    </body>
</html>
