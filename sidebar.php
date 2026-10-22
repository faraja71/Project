<?php
// sidebar.php - active state inajiweka yenyewe kulingana na ukurasa
$cur = basename($_SERVER['PHP_SELF']);
$catPages      = ['product-categories.php', 'industry-categories.php', 'customer-categories.php'];
$settingsPages = array_merge(['general-settings.php', 'roles.php'], $catPages);
$inCat      = in_array($cur, $catPages);
$inSettings = in_array($cur, $settingsPages);
function act($f, $cur) { return $f === $cur ? 'active' : ''; }
?>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link">
      <i class="fas fa-tachometer-alt brand-image" style="opacity:.8; margin-left:.8rem;"></i>
      <span class="brand-text font-weight-light">FJ Codder</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item">
            <a href="index.php" class="nav-link <?= act('index.php', $cur) ?>">
              <i class="nav-icon fas fa-home"></i><p>Dashboard</p></a>
          </li>

          <li class="nav-header">MANAGEMENT</li>
          <li class="nav-item"><a href="companies.php" class="nav-link <?= act('companies.php', $cur) ?>"><i class="nav-icon fas fa-building"></i><p>Companies</p></a></li>
          <li class="nav-item"><a href="customers.php" class="nav-link <?= act('customers.php', $cur) ?>"><i class="nav-icon fas fa-users"></i><p>Customers</p></a></li>
          <li class="nav-item"><a href="products.php" class="nav-link <?= act('products.php', $cur) ?>"><i class="nav-icon fas fa-box"></i><p>Products</p></a></li>
          <li class="nav-item"><a href="employees.php" class="nav-link <?= act('employees.php', $cur) ?>"><i class="nav-icon fas fa-user-tie"></i><p>Employees</p></a></li>
          <li class="nav-item"><a href="partners.php" class="nav-link <?= act('partners.php', $cur) ?>"><i class="nav-icon fas fa-handshake"></i><p>Partners</p></a></li>
          <li class="nav-item"><a href="accounts.php" class="nav-link <?= act('accounts.php', $cur) ?>"><i class="nav-icon fas fa-university"></i><p>Accounts</p></a></li>

          <li class="nav-header">SYSTEM</li>

          <li class="nav-item <?= $inSettings ? 'menu-open' : '' ?>">
            <a href="#" class="nav-link <?= $inSettings ? 'active' : '' ?>">
              <i class="nav-icon fas fa-cog"></i>
              <p>Settings <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="general-settings.php" class="nav-link <?= act('general-settings.php', $cur) ?>">
                  <i class="far fa-circle nav-icon"></i><p>General Settings</p></a>
              </li>

              <li class="nav-item <?= $inCat ? 'menu-open' : '' ?>">
                <a href="#" class="nav-link <?= $inCat ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Categories <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item"><a href="product-categories.php" class="nav-link <?= act('product-categories.php', $cur) ?>"><i class="far fa-dot-circle nav-icon"></i><p>Product Categories</p></a></li>
                  <li class="nav-item"><a href="industry-categories.php" class="nav-link <?= act('industry-categories.php', $cur) ?>"><i class="far fa-dot-circle nav-icon"></i><p>Industry Categories</p></a></li>
                  <li class="nav-item"><a href="customer-categories.php" class="nav-link <?= act('customer-categories.php', $cur) ?>"><i class="far fa-dot-circle nav-icon"></i><p>Customer Categories</p></a></li>
                </ul>
              </li>

              <li class="nav-item">
                <a href="roles.php" class="nav-link <?= act('roles.php', $cur) ?>">
                  <i class="far fa-circle nav-icon"></i><p>Roles &amp; Permissions</p></a>
              </li>
            </ul>
          </li>

        </ul>
      </nav>
    </div>
  </aside>
