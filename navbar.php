<nav id="navbar" class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Strutt</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Photos</a>
                <ul class="dropdown-menu dropdown-menu-inverse">
                    <li><a href="search.php?category=streetwear">Streetwear</a></li>
                    <li><a href="search.php?category=casualwear">Casualwear</a></li>
                    <li><a href="search.php?category=cosplay">Cosplay</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Art</a>
                <ul class="dropdown-menu dropdown-menu-inverse">
                    <li><a href="search.php?category=fantasy">Fantasy</a></li>
                    <li><a href="search.php?category=sketches">Sketches</a></li>
                    <li><a href="search.php?category=concept">Concept</a></li>
                </ul>
            </li>
        </ul>
        <form class="navbar-form navbar-left" action="search.php" method="get">
              <div class="input-group">
                <input type="search" name="term" class="form-control" placeholder="Search">
                <div class="input-group-btn">
                  <button class="btn btn-default" type="submit">
                    <div class="glyphicon glyphicon-search"></div>
                  </button>
                </div>
              </div>
        </form>
        <ul class="nav navbar-nav navbar-right">
            <?php
                if(!isset($_SESSION['username'])){
                    echo '<li><a href="login.php">Login</a></li>';
                    echo '<li><a href="signup.php">Sign Up</a></li>';
                }
                else{
                    echo '<button id="postit" type="button" class="btn btn-inverse navbar-btn" data-toggle="modal" data-target="#postModal">Post</button>';
                    echo '  <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown">'.$_SESSION["username"].'</a>
                                <ul class="dropdown-menu dropdown-menu-inverse">
                                    <li><a href="profile.php"><div type="button" class="glyphicon glyphicon-user"></div> Profile</a></li>
                                    <li><a href="edit.php"><div type="button" class="glyphicon glyphicon-cog"></div> Settings</a></li>
                                    <li><a href="logout.php"><div type="button" class="glyphicon glyphicon-remove-sign"></div> Log out</a></li>
                                </ul>
                            </li>';
                }
            ?>
        </ul>
    </div>
</nav>