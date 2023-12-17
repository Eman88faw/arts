<?php
include_once "config.php";
function Check($s){
    if(file_exists($s)){$r=$s;}else{$r= "./assets/images/default.jpg";}
    return $r;
}
?>

<?php include_once BL."includes/header.php"; ?>

<body>
    <?php include_once BL.'includes/navigation.php'; ?>
    <?php

    // Implement logic to determine which page to display
    if(isset($_GET['page'])){
        if($_GET['page'] == "artist"){
            if(!chech_for_Data($conn,"artists", "ArtistID",intval($_GET['Id']))){
                $page = '404';
            }
            else{
                $page = $_GET['page'];
            }
        }
        elseif($_GET['page'] == "work"){
            if(!chech_for_Data($conn,"artworks", "ArtWorkID",intval($_GET['Id']))){
                $page = '404';
            }
            else{
                $page = $_GET['page'];
            }
        }
        else{
            $page = $_GET['page'];
        }
    }
    else{
        $page = 'home';
    }
    // check for search page
    if (isset($_GET['search'])){
        $page = 'search';
    }

    include "pages/{$page}.php";

    ?>

    <?php include_once BL.'includes/scripts.php'; ?>
    <?php include BL.'includes/footer.php'; ?>

    <script>
/*
        function displayLarge(url){
            document.getElementById('imageLarge').src = url
            document.getElementById('imgLargeView').setAttribute("style", "display:block");
        }
        function closeModal(){
            document.getElementById('imageLarge').src = ""
            document.getElementById('imgLargeView').setAttribute("style", "display:none");
        }*/
    </script>
</body>