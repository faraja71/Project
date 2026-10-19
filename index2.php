<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';  
require_once 'Employeesview.php';    // $conn inatengenezwa hapa kwanza

// ---- Data za menu na takwimu (zinatumika kwenye HTML chini) ----
$menus = [
    ['Customers', 'fa-users',    'customers'],
    ['Products',  'fa-box',      'products'],
    ['Employees', 'fa-user-tie', 'employees'],
    ['Partners',  'fa-handshake','partners'],
    ['Accounts',  'fa-wallet',   'accounts'],
];
$actions = [['View', 'info', 'view'], ['Add New', 'success', 'add'], ['Update', 'warning', 'update'], ['Delete', 'danger', 'delete']];

$stats = [
    ['info',    7,   'Companies', 'fa-building',   '#companies'],
    ['success', 120, 'Customers', 'fa-users',      '#customers'],
    ['warning', 40,  'Products',  'fa-box',        '#products'],
    ['primary', 15,  'Partners',  'fa-handshake',  '#partners'],
    ['secondary',100,'Accounts',  'fa-wallet',     '#accounts'],
    ['danger',  60,  'Employees', 'fa-user-tie',   '#view-employees'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- TOP NAVBAR -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <!-- Left links (ul MOJA tu) -->
        <ul class="navbar-nav align-items-center">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index.php" class="nav-link text-nowrap">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link d-flex align-items-center text-nowrap"><i class="fas fa-phone-alt mr-1"></i>Contact</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link d-flex align-items-center text-nowrap"><i class="fas fa-concierge-bell mr-1"></i>Service</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link d-flex align-items-center text-nowrap"><i class="fas fa-envelope mr-1"></i>Message</a>
            </li>
        </ul>

        <!-- Search -->
        <form class="form-inline ml-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>

        <!-- Right links -->
        <ul class="navbar-nav ml-auto align-items-center">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">10</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">10 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item"><i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span></a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item"><i class="fas fa-file mr-2"></i> 2 new reports
                        <span class="float-right text-muted text-sm">2 days</span></a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item"><i class="fas fa-file mr-2"></i> 4 new reports
                        <span class="float-right text-muted text-sm">4 days</span></a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>

            <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item d-none d-sm-inline-block">
                    <span class="nav-link text-nowrap">Admin User</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" title="sign out"><i class="fas fa-sign-out-alt"></i></a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php" title="sign in"><i class="fas fa-sign-in-alt"></i></a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- LEFT SIDEBAR -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="index.php" class="brand-link">
            <i class="fas fa-tachometer-alt ml-3"></i>
            <span class="brand-text font-weight-light">FJ Codder</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image"><i class="fas fa-user-shield fa-2x text-white"></i></div>
                <div class="info"><a href="#" class="d-block">FJ Administrator</a></div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                    <li class="nav-item">
                        <a href="index.php" class="nav-link active">
                            <i class="nav-icon fas fa-home"></i><p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">MANAGEMENT</li>

                    <li class="nav-item">
                        <a href="#companies" class="nav-link">
                            <i class="nav-icon fas fa-building"></i><p>Companies</p>
                        </a>
                    </li>

                    <?php foreach ($menus as [$title, $icon, $slug]): ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas <?= $icon ?>"></i>
                            <p><?= $title ?><i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview pl-3">
                            <?php foreach ($actions as [$label, $color, $key]): ?>
                            <li class="nav-item">
                                <a href="#<?= $key ?>-<?= $slug ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon text-<?= $color ?>"></i>
                                    <p><?= $label ?> <?= $title ?></p>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endforeach; ?>

                    <li class="nav-header">SYSTEM</li>
                    <li class="nav-item">
                        <a href="#settings" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i><p>Settings</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- MAIN CONTENT (kila kitu cha ukurasa kiko NDANI ya content-wrapper) -->
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <h1 class="text-align-center">FJ Company</h1>
                <p>Welcome to your administration system.</p>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <!-- STATISTICS CARDS -->
                <div class="row">
                    <?php foreach ($stats as [$bg, $count, $label, $icon, $link]): ?>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="small-box bg-<?= $bg ?>">
                            <div class="inner"><h3><?= $count ?></h3><p><?= $label ?></p></div>
                            <div class="icon"><i class="fas <?= $icon ?>"></i></div>
                            <a href="<?= $link ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- COMPANY TABLE -->
                <div class="row" id="companies">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Recent Companies</h3></div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead><tr><th>ID</th><th>Company Name</th><th>Industry</th><th>Email</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <tr><td>1</td><td>FJ Technologies</td><td>Technology</td><td>info@fjtechnologies.example</td><td><span class="badge badge-success">Active</span></td></tr>
                                        <tr><td>2</td><td>Green Valley Supplies</td><td>Wholesale</td><td>contact@greenvalley.example</td><td><span class="badge badge-success">Active</span></td></tr>
                                        <tr><td>3</td><td>Coastal Services</td><td>Professional Services</td><td>hello@coastalservices.example</td><td><span class="badge badge-secondary">Pending</span></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CUSTOMER TABLE -->
                <div class="row" id="customers">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Recent Customers</h3></div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <tr><td>1</td><td>Asha John</td><td>asha@example.com</td><td><span class="badge badge-success">Active</span></td></tr>
                                        <tr><td>2</td><td>Juma Peter</td><td>juma@example.com</td><td><span class="badge badge-success">Active</span></td></tr>
                                        <tr><td>3</td><td>Neema Ally</td><td>neema@example.com</td><td><span class="badge badge-success">Active</span></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRODUCTS DASHBOARD -->
                <div class="row" id="products">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-box mr-1"></i> Products Dashboard</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5>Manage Your Products</h5>
                                <button class="btn btn-success mb-3"><i class="fas fa-plus-circle mr-1"></i> Add New Product</button>
                                <button class="btn btn-info mb-3"><i class="fas fa-eye mr-1"></i> View Products</button>
                                <button class="btn btn-warning mb-3"><i class="fas fa-edit mr-1"></i> Update Product</button>
                                <button class="btn btn-danger mb-3"><i class="fas fa-trash mr-1"></i> Delete Product</button>
                                <div class="p-5 text-center bg-light border rounded">
                                    <p class="text-muted m-0">No products added yet. Click "Add New Product" to start.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EMPLOYEES (Employees.php inapakiwa HAPA, ndani ya ukurasa) -->
                <div class="row" id="view-employees">
                    <div class="col-12">
                        <?php require_once 'Employees.php'; ?>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- FOOTER -->
    <footer class="main-footer">
        <strong>FJ Dashboard</strong> &copy; 2026 All rights reserved.
    </footer>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>