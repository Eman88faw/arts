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
                        <li><a class="nav-link <?php if(isset($_GET['page']) && $_GET['page'] == "home" || !isset($_GET['page'])){echo 'active';}?>" href="?page=home" >Home</a></li>
                        <li><a class="nav-link <?php if(isset($_GET['page']) && $_GET['page'] == "about"){echo 'active';}?>" href="?page=about" >About Us</a></li>
                        <li>
                            <a class="nav-link <?php if(isset($_GET['page']) && ($_GET['page'] == "browse_artists" || $_GET['page'] == "browse_artworks" || $_GET['page'] == "browse_genres" || $_GET['page'] == "browse_subjects" || $_GET['page'] == "artist" || $_GET['page'] == "work")){echo 'active';}?>" >Browse</a>
                            <ul>
                                <li><a class="nav-link <?php if(isset($_GET['page']) && ($_GET['page'] == "browse_artists" || $_GET['page'] == "artist")){echo 'active';}?>" href="?page=browse_artists">Browse Artists</a></li>
                                <li><a class="nav-link <?php if(isset($_GET['page']) && ($_GET['page'] == "browse_artworks" || $_GET['page'] == "work")){echo 'active';}?>" href="?page=browse_artworks">Browse Artworks</a></li>
                                <li><a class="nav-link <?php if(isset($_GET['page']) && ($_GET['page'] == "browse_genres")){echo 'active';}?>" href="?page=browse_genres">Browse Genres</a></li>
                                <li><a class="nav-link <?php if(isset($_GET['page']) && ($_GET['page'] == "browse_subjects")){echo 'active';}?>" href="?page=browse_subjects">Browse Subjects</a></li>
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

<!-- wishlist -->
                <?php if(isset($_SESSION['user_name'])): ?>
                    <!-- Example split danger button -->
                    <ul class="auth-nav">
                        <li>
                            <a class="item-nav">
                                <img src="<?= ASSETS;?>images/heart.svg" class="icon" width="20" alt="">
                            </a>
                            <ul class="whichListContainer"> <?=wishListItem($conn);?></ul>
                        </li>
                        <li>
                            <a class="item-nav">
                                <img src="<?= ASSETS;?>images/user.jpg" width="40" alt="">
                                <span><?php echo $_SESSION['user_name'];?></span>
                            </a>
                            <ul>
                                <li><a class="dropdown-item" href="?page=my_account">My Account</a></li>
                                <li><a class="dropdown-item" href="?page=manage_users">Manage Users</a></li>
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