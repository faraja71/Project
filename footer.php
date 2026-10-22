<?php // footer.php - weka $extraJs (string ya JavaScript) kabla ya kuiinclude kama unahitaji ?>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>FJ Dashboard</strong> &copy; 2026. All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<?php if (!empty($extraJs)): ?>
<script><?= $extraJs ?></script>
<?php endif; ?>
</body>
</html>
