
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Genetic Algorithms, Graph Coloring</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" 		content="Alan Rodríguez Romero">
	<meta name="keywords" 		content="<?= $SYS_metaKeyWords; ?>">
	<meta name="description" 	content="<?= $SYS_metaDescription; ?>">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le styles -->
	<link href="<?php echo base_url(); ?>static/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/css/local.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/css/loading.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/css/custom.css" rel="stylesheet">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="https://www.google.com/jsapi"></script>
	<script src="<?php echo base_url(); ?>static/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>static/js/arbor.js"></script>
	<script src="<?php echo base_url(); ?>static/js/arbor-tween.js"></script>
	<script src="<?php echo base_url(); ?>static/js/GA_GraphColoring.js"></script>
	<script src="<?php echo base_url(); ?>static/js/graph.js"></script>
  </head>

  <body class="preview" data-spy="scroll" data-target=".subnav" data-offset="50">
	
	<script>
	<!--
	jQuery(document).ready(function($) {
		$("#loading").fadeOut();
	});
	//-->
	</script>
	<div id="loading">
		<div class="animation">
			<img src="<?php echo base_url(); ?>static/img/ajax-loader.gif" width="31" height="31" alt="Cargando...">
		</div>
	</div>

  <!-- Navbar
    ================================================== -->
 <div class="navbar navbar-fixed-top">
   <div class="navbar-inner">
     <div class="container">
       <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
       </a>
       <a class="brand" href="">Genetic Algorithms: Graph Coloring</a>
       <div class="nav-collapse" id="main-menu">

       </div>
     </div>
   </div>
 </div>

    <div class="container" style="margin-top: 60px;">
		<?php $this->load->view($module); ?>


     <!-- Footer
      ================================================== -->
      <footer class="footer">
        <p class="pull-right"><a href="#">Arriba</a></p>
      </footer>

    </div><!-- /container -->

  </body>
</html>
