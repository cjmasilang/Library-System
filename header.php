<!DOCTYPE html>
<html lang="en">
<head>
	<title>Library System</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

  <!--Bootstrap CSS-->
  <link rel="stylesheet" href="assets/bootstrap/4.0.0/css/bootstrap.min.css">

  <!-- JQuery Slim for Bootstrap-->
  <script src="assets/jquery/3.2.1/jquery-3.2.1.slim.min.js"></script> 
  
  <!-- JQuery-->
  <script src="assets/jquery/3.3.1/jquery-3.3.1.min.js"></script> 

  <!-- Popper.js-->
  <script src="assets/popper.js/1.12.9/popper.min.js"></script>

  <!--Bootstrap JS-->
  <script src="assets/bootstrap/4.0.0/js/bootstrap.min.js"></script>

  <!-- bootbox library -->
  <script src="assets\bootbox\bootbox.min.js"></script>

</head>
<body>

<div class="container">
  <nav class="navbar navbar-expand-lg bg-info navbar-dark">

  <a class="navbar-brand" href="index.php">Library System</a>

 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

   <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <!-- Links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Book</a>
      </li>
	    <li class="nav-item">
        <a class="nav-link" href="authorView.php">Author</a>
      </li>
      <li class="nav-item" hidden="">
        <a class="nav-link" href="borrowView.php" >Borrower</a>
      </li>
      <li class="nav-item" hidden="">
        <a class="nav-link" href="categoryView.php" >Categories</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="archiveView.php">Archived Books</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="fpdf/pdfdemo.php" target="_blank">Reports</a>
      </li>
	  <li class="nav-item">
        <a class="btn nav-link" data-toggle="modal" data-target="#reportModal">Modal</a>
      </li>
    </ul>
    <ul class="nav navbar-nav navbar-right ml-auto">
          <a role="button" onclick="logout()" class="nav-link btn">Logout</a>
        </ul>
    </div>
  </nav>
  <script>
    //message
    function logout(){
      var c = confirm("Are You Sure?");
        if(c==true){
          window.location.href = "logout.php";
        }
      }
  </script>
  <!-- Start of content -->
  <div class="row pt-3 mb-2">
    <div class="col-lg-3">
      <h3 class="page-header"><?php echo $page_title?></h3>
    </div>
    