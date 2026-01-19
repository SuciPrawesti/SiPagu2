<?php
/**
 * NAVBAR TEMPLATE - SiPagu (FINAL)
 * Lokasi: admin/includes/navbar.php
 */
?>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li>
                <a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none">
                    <i class="fas fa-search"></i>
                </a>
            </li>
        </ul>
    </form>

    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown"
               class="nav-link dropdown-toggle nav-link-lg nav-link-user">

                <!-- Avatar -->
                <img
                    alt="image"
                    src="<?= ASSETS_URL ?>/img/avatar/avatar-1.png"
                    class="rounded-circle mr-1"
                >

                <!-- Username -->
                <div class="d-sm-none d-lg-inline-block">
                    Hi, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">
                    Logged in as <?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
                </div>

                <a href="#" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>

                <a href="#" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Settings
                </a>

                <div class="dropdown-divider"></div>

                <!-- Logout -->
                <a href="<?= BASE_URL ?>admin/logout.php"
                   class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>