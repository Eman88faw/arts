<?php
include_once "config.php";
function Check($s){
    if(file_exists($s)){$r=$s;}else{$r= "./assets/images/default.jpg";}
    return $r;
}
$anfrage="select * from Artists";
$c=$conn->prepare($anfrage);
$c->execute();
$ar="select * from artists order by FirstName ASC";
$cc=$conn->query($ar);
?>

<?php include_once BL."includes/header.php"; ?>

<body>

    <?php include_once BL.'includes/navigation.php'; ?>

    <?php

    // Implement logic to determine which page to display
//    $page = isset($_GET['page']) ? $_GET['page'] || isset($_GET['search']) ? 'search' : 'home';
    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }
    elseif (isset($_GET['search'])){
        $page = 'search';
    }
    else{
        $page = 'home';
    }
        include "pages/{$page}.php";
    ?>

    <?php include_once BL.'includes/scripts.php'; ?>
    <?php include BL.'includes/footer.php'; ?>

    <script>
        /*
        // Set a timer to trigger the session destroy after 5 seconds
        var myElement = document.getElementsByClassName("alert");
        setTimeout(function() {
            myElement[0].remove();
        }, 5000); // 5000 milliseconds = 5 seconds

        $('.imgLargeView span').on('click', function(){
            $('.imgLargeView').hide();
        });

        $('img.imgWork').on('click',function(){
            src = $(this).data('img-large');
            $('.imgLargeView').show();
        })
          */  



    </script>
</body>