<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>

        <link rel="icon" type="image/ico" href="http://openclassroom.ingenus.pro/project5/public/pictures/logo/logo_Plume.ico" />

        <meta property="og:title" content="Teller"/>
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="http://openclassroom.ingenus.pro/project5/index.php"/> 
        <meta property="og:image" content="http://openclassroom.ingenus.pro/project5/public/pictures/logo/logo_Plume.jpg"/> 
        <meta property="og:description" content="Lancez vous dans l'écriture collaborative."/>

        <meta name="twitter:card" content="website">
        <meta name="twitter:site" content="@ingenus.pro">
        <meta name="twitter:title" content="Teller">
        <meta name="twitter:description" content="Lancez vous dans l'écriture collaborative.">
        <meta name="twitter:creator" content="@Antoine_Libert">
        <meta name="twitter:image" content="http://openclassroom.ingenus.pro/project5/public/pictures/logo/logo_Plume.jpg">



        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="public/css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
    	
        <?php require("view/include/navbar.php");?>

        <?= $content ?>

        <script src="vendor/jQuery/jquery-3.3.1.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/tinymce/js/tinymce.min.js"></script>
		<script src="vendor/tinymce/js/themes/init-tinymce.js"></script>

        <script src="public/js/EditArticle.js"></script>
        <script src="public/js/EditStatus.js"></script>
        <script src="public/js/Slide.js"></script>
        <script src="public/js/TellerResponsive.js"></script>
        <script src="public/js/TestForm.js"></script>
        <script src="public/js/main.js"></script>
    </body>
</html>