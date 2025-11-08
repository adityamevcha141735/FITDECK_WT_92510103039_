<?php session_start();?>
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a href="#" class="navbar-brand col-md-3 md-lg-2 me-0 px-3">Vogue</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <?php if(isset($_SESSION['admin_logged_in'])){ ?>
                <a href="logout.php?logout=1" class="nav-link px-3">Sign out</a>
                <?php } ?>
            </div>
        </div>
    </header>