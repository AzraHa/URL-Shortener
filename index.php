<?php
require_once('database.php');
require_once('shortener.class.php');
$shortener = new Shortener();
//trenutna domena https://localhost/praksa/URLShortener
//vodi nas na redirect stranicu 
$shortURLprefix = 'https://localhost/praksa/URLShortener/redirect.php?c=';
?>
<!DOCTYPE html>
<html>
<head>
	<title>URL SHORTENER</title>
    <link rel="stylesheet" type="text/css" href="style.css">  
    <link href="https://fonts.googleapis.com/css?family=Gupter&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <nav>   
      <div class="topnav" id="myTopnav">
        <a href="#home">URL SHORTENER</a>
      </div> 
    </nav>
    <span id="home"></span>
    <div class="naslovna">
      <div class="naslovna-center">
        <form name="form" action="" method="post">
            <input name="link" type="text" placeholder="Enter link here">
            <button type="submit">SHORT URL</button>
        </form>
      
  
<?php
try
{
  if(isset($_POST['link'])){
    $longURL = $_POST['link'];
    $shortCode = $shortener->urlToShort($longURL);
    $shortURL = $shortURLprefix.$shortCode;
    echo '<p>Your shorten URL: <br>'.$shortURL.'</p>
    </div>
    </div>
  </header>';
  }
  else{
    echo '<p></p>
    </div>
    </div>
  </header>' ;
  }
   
}
catch(Exception $e)
{
    echo $e->getMessage();
}

?>
<div id="footer">
  <p>Azra Hadžihajdarević <i class="fa fa-copyright"></i>2020.</p>
</div>  
</body>
</html>

