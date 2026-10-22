<?php
// header.php - weka $pageTitle kabla ya kuiinclude

$pageTitle = $pageTitle ?? 'FJ Dashboard';
$cur = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle) ?> | FJ Dashboard</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block"><a href="index.php" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="#" class="nav-link"><i class="fas fa-phone"></i> Contact</a></li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="general-settings.php" title="Settings"><i class="fas fa-cog"></i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">10</span></a>
      </li>
      <li class="nav-item"><span class="nav-link">Admin User</span></li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
      </li>
    </ul>
  </nav>

  <?php include 'sidebar.php'; ?>

  <div class="content-wrapper">
