<?php include_once "config.php"?>

<header class="header fixed-top">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-2 logo">
                <h2 class="m-0">LOGO</h2>
            </div>
            <div class="col-6">
                <div class="menu ">
                    <ul class="align-items-center p-0 m-0">
                        <li><a class="nav-link active" href="?page=home" >Home</a></li>
                        <li><a class="nav-link" href="?page=about" >About Us</a></li>
                        <li>
                            <a class="nav-link" href="?page=about" >Browse</a>
                            <ul>
                                <li><a class="nav-link" href="?page=browse_artists">Browse Artists</a></li>
                                <li><a class="nav-link" href="?page=browse_artworks">Browse Artworks</a></li>
                                <li><a class="nav-link" href="?page=browse_genres">Browse Genres</a></li>
                                <li><a class="nav-link" href="?page=browse_subjects">Browse Subjects</a></li>
                            </ul>
                        </li>
                        <li>
                            <form action="?page=search" method="get">
                                <div class="input-group">
                                    <input type="search" class="form-control" name="search" placeholder="Search..">
                                    <div class="input-group-text">
                                        <button type="submit">
                                            <img src="<?= ASSETS;?>images/search.svg" width="20" alt="">
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="col-4 text-end authentication">

                <?php if(isset($_SESSION['user_name'])): ?>
                <?php
                    $user_id = $_SESSION['user_id'];
                    $query = "SELECT w.id AS wishlist_id, w.work_id, w.customer_id, w.created_at, aw.ArtWorkID AS artwork_id, aw.ImageFileName, aw.Title
                                    FROM wishlist w
                                    INNER JOIN artworks aw ON w.work_id = aw.ArtWorkID
                                    WHERE w.customer_id = :userId Limit 5";

                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':userId', $user_id, PDO::PARAM_INT);
                    $stmt->execute();

                    $wishlistArtworks = $stmt->fetchAll(PDO::FETCH_ASSOC);

                ?>
                    <!-- Example split danger button -->
                    <ul class="auth-nav">
                        <li class="dropdown">
                            <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="#" >
                                <img src="<?= ASSETS;?>images/heart.svg" class="icon" width="20" alt="">
                            </a>
                            <ul class="dropdown-menu p-2" role="menu" aria-labelledby="dLabel" style="width: 240px">
                                <?php foreach ($wishlistArtworks as $work):?>
                                <div class="art mb-2 border-bottom d-flex">
                                    <?php
                                        $img ="";
                                        if(strlen($work["ImageFileName"]) < 6){
                                            $img = ASSETS."images/works/medium/0".$work["ImageFileName"].".jpg";
                                        }
                                        if(!file_exists($img) & strlen($work["ImageFileName"]) >= 6){
                                            $img = ASSETS."images/works/medium/" . $work["ImageFileName"] . ".jpg";
                                        }
                                    ?>
                                    <a href="?page=work&Id=<?= $work['work_id'];?>">
                                        <img src="<?=$img?>" class="card-img-top" alt="...">
                                        <span class="ms-1">
                                            <h6 class="d-inline-flex">
                                                <?= $work["Title"]?>
                                            </h6>
                                            <small class="d-block">
                                                <?= date_format(date_create($work["created_at"]),'Y-m-d');?>
                                            </small>

                                        </span>
                                    </a>
                                </div>
                                <?php endforeach;?>
                                <li class="divider"></li>
                                <li class="text-center mt-2"><a href="?page=logout">show all</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <img src="<?= ASSETS;?>images/user.jpg" width="40" alt="">
                                <span><?php echo $_SESSION['user_name'];?></span>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li><a class="dropdown-item" href="?page=dashboard">My Account</a></li>
                                <li><a class="dropdown-item" href="?page=dashboard">Manage Users</a></li>
                                <li class="divider"></li>
                                <li><a class="dropdown-item" href="?page=logout">logout</a></li>
                            </ul>
                        </li>

                    </ul>

                <?php else:?>
                    <a href="?page=login">Login</a>
                    <a href="?page=register">Register</a>
                <?php endif;?>
            </div>
        </div>
    </div>
</header>