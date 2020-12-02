<!DOCTYPE html>
<!--
Template Name: Miresa
Author: <a href="https://www.os-templates.com/">OS Templates</a>
Author URI: https://www.os-templates.com/
Licence: Free to use under our free template licence terms
Licence URI: https://www.os-templates.com/template-terms
-->
<html lang="">
<head>
<title>Home - Stock Market Simulator</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
</head>
<body id="top">

<!-- Top Background Image Wrapper -->
<div class="bgded overlay light" style="background-image:url('images/graph.jpg');"> 
  <div class="wrapper row0">
    <div id="topbar" class="hoc clear"> 
      <ul class="nospace">
        <li><a href="index.php"><i class="fa fa-lg fa-home"></i></a></li>
        <?php
        session_start();
        if ($_SESSION['username'] == '')
        {
          echo '<li><a href="../src/pages/login.html" title="Login"><i class="fa fa-lg fa-sign-in"></i></a></li>';
          echo '<li><a href="../src/pages/register.html" title="Sign Up"><i class="fa fa-lg fa-edit"></i></a></li>';
        }
        else
        {
          echo '<li><a href="../src/Logout.php" title="Logout"><i class="fa fa-lg fa-edit"></i></a></li>';
        }
        ?>
      </ul>
    </div>
  </div>
  <div class="wrapper row1">
    <header id="header" class="hoc clear"> 
      <div id="logo" class="fl_left">
        <?php
        session_start();
        if ($_SESSION['username'] == '')
        {
          echo '<h1>Welcome</h1>';
       
        }
        else
        {
          echo '<h1>Welcome ';
          echo $_SESSION["username"];
          echo '</h1>';
        }
        ?>
      </div>
      <nav id="mainav" class="fl_right">
        <ul class="clear">
          <li class="active"><a href="index.php">Home</a></li>
        </ul>
      </nav>
    </header>
  </div>
  <div id="pageintro" class="hoc clear"> 
    <article>
      <p>IT490</p>
      <h3 class="heading">Stock Market Simulator</h3>

    </article>
  </div>
</div>
<div class="wrapper row4">
  <footer id="footer" class="hoc clear"> 
    <article class="two_third first">
      <h6 class="heading">Our Mission</h6>
      <p>Due to COVID-19, many people across the globe have a lost or reduced source of income. People have sought after new ways to supplement this gap, some by starting a side business and some by taking to the stock market. For a first timer just being introduced to the stock market, it can seem like a daunting and chaotic experience, this is what our project aims to alleviate.</p>
      <p>The goal of our project, IT-490 Stock Market Simulator, is to gradually expose the user to the fluctuations of the stock market in real-time at no risk to their financial health. </p>
    </article>
  </footer>
</div>
<div class="wrapper row5">
  <div id="copyright" class="hoc clear"> 
    <p class="fl_left">Copyright &copy; 2018 - All Rights Reserved - <a href="#">Domain Name</a></p>
    <p class="fl_right">Template by <a target="_blank" href="https://www.os-templates.com/" title="Free Website Templates">OS Templates</a></p>
    <!-- ################################################################################################ -->
  </div>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<a id="backtotop" href="#top"><i class="fa fa-chevron-up"></i></a>
<!-- JAVASCRIPTS -->
<script src="layout/scripts/jquery.min.js"></script>
<script src="layout/scripts/jquery.backtotop.js"></script>
<script src="layout/scripts/jquery.mobilemenu.js"></script>
</body>
</html>
