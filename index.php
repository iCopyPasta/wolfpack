 <?php //include this code at the top of all pages with special functionality for a logged in user
 session_start(); ?>

 <?php if (isset($_SESSION['accountType']) && $_SESSION['accountType'] == "student") { //logged in as student
        header("Location: pages/logged_in_student.php");
        }

        if  (isset($_SESSION['accountType']) && $_SESSION['accountType'] == "teacher") { //logged in as teacher
            
        //include("lib/php/closeAllSessionsByTeacherId.php");   //this is what you would do if you want to close all active sessions 
        //closeAllSessions($_SESSION['id']); //flushes all active sessions and questions from database    
            
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
        <style>
            .btn-transparent-dark
            {
                border-color: black;
            }
            
        </style>
    </head>

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
                        <li><a href="#app">App</a></li>
                        <li><a href="#about">Mission</a></li>
                        <li><a href="#testimonials">About</a></li>
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
        
        <section class="intro-section" id="app">
        <div class="container">
            <h2 class="intro-text">Are you a student? Then try out our Student-Focused Android App Made just for you</h2>
            <div class="action-bar text-center">
                <a class="btn btn-transparent-dark btn-lg"><i class="fa fa-android"></i> Download Android App</a>
            </div>
            <div class="mockup-block"><img class="img-responsive" src="img/info-mockup.jpg"/></div>
        </div>
        </section>

        <section class="about-sec" id="about">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 pull-right">
                        <h2>Our Mission</h2>
                        <p>We here at Pollato believe that every teacher could use some help now and then. We have made it our mission to integrate technology seamlessly into the classroom. Pollato offers a simple way to engage with your students without slowing you down. Because we know how hard a teacher’s life is, and we want to make it easier.</p>
                        <ul>
                            <li>We leverage state of the art cloud storage.</li>
                            <li>Bug-free, hassle-free.</li>
                            <li>No more paper quizzes and late night grading</li>
                        </ul>
                        <p>The world is full of technology, now it's time to bring it into your classroom.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="testimonial-sec" id="testimonials">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center"><h2>Who We Are<br><small>Get to meet the creators of Pollato</small></h2></div>
                    <div class="col-md-10 col-md-offset-1 testimonial-container">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="4"></li>
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                            <div class="profile-circle text-center"><img src="img/pabloheadshot.jpg"></div>
                                  <blockquote>
                                      <p><em>"Hello, my name is Pablo Orellana. I’m a Computer Science IUG student at Penn State Harrisburg. I’m completing my undergraduate this year and plan to finish my master’s next year. You can find out more me at <a href="https://turing.cs.hbg.psu.edu/~peo5032/">My Penn State Page</a>, <a href="https://www.linkedin.com/in/pabloOrellanaIsHere">LinkedIn</a>, or <a href="https://www.facebook.com/pablo.orellana.712">Facebook</a>."</em></p>
                                    <footer>Pablo Orellana</footer>
                                  </blockquote>
                            </div>
                            
                            <div class="item">
                            <div class="profile-circle text-center"><img src="img/davidheadshot.jpg"></div>
                                  <blockquote>
                                    <p><em>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus."</em></p>
                                    <footer>David Rowe</footer>
                                  </blockquote>
                            </div>
                            
                            <div class="item">
                            <div class="profile-circle text-center"><img src="img/placeholder.jpg"></div>
                                  <blockquote>
                                    <p><em>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus."</em></p>
                                    <footer>Tyler Ruch</footer>
                                  </blockquote>
                            </div>
                            
                            <div class="item">
                            <div class="profile-circle text-center"><img src="img/placeholder.jpg"></div>
                                  <blockquote>
                                    <p><em>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus."</em></p>
                                    <footer>Scott Wilson</footer>
                                  </blockquote>
                            </div>
                            
                            <div class="item">
                            <div class="profile-circle text-center"><img src="img/placeholder.jpg"></div>
                                  <blockquote>
                                    <p><em>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus."</em></p>
                                    <footer>Tyler Hughes</footer>
                                  </blockquote>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-sec" id="contact">
            <div class="container text-center">
                <h2 class="text-center">Get in touch</h2>
                <p>Want to let us know how much you've enjoyed our product or any concerns you may have?</p>
                <p>Email us at: Pollato@gmail.com</p>
            </div>
        </section>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-4">&copy;<script type="text/javascript">document.write(new Date().getFullYear());</script> Pollato.com</div>
                    <a href="https://github.com/iCopyPasta/wolfpack" class="col-md-4 text-center">GitHub</a>
                    <a href="https://drive.google.com/drive/folders/1XNrIPk2gDKLrvpE7XLUqg_Cw9UCHvMtM?usp=sharing" class="col-md-4 pull-right text-right">See Our Deliverables</a>
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
