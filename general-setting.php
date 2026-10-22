<?php
require 'db.php';

$fields = [
    'company_name' => 'Company Name',
    'email'        => 'Email',
    'phone'        => 'Phone',
    'currency'     => 'Currency (e.g. TZS)',
];

$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare(
        "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    );
    foreach ($fields as $key => $label) {
        $val = trim($_POST[$key] ?? '');
        $stmt->bind_param('ss', $key, $val);
        $stmt->execute();
    }
    header('Location: general-settings.php?saved=1');
    exit;
}

$values = [];
$res = $conn->query("SELECT setting_key, setting_value FROM settings");
while ($r = $res->fetch_assoc()) { $values[$r['setting_key']] = $r['setting_value']; }

$pageTitle = 'General Settings';
include 'header.php';
?>
    <div class="content-header">
      <div class="container-fluid">
        <h1 class="m-0">General Settings</h1>
        <p class="text-muted mb-0">Basic information about your company.</p>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <?php if (isset($_GET['saved'])): ?>
          <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Settings saved.
          </div>
        <?php endif; ?>

        <div class="card" style="max-width:640px">
          <form method="post">
            <div class="card-body">
              <?php foreach ($fields as $key => $label): ?>
                <div class="form-group">
                  <label for="<?= $key ?>"><?= $label ?></label>
                  <input type="text" class="form-control" id="<?= $key ?>" name="<?= $key ?>"
                         value="<?= htmlspecialchars($values[$key] ?? '') ?>">
                </div>
              <?php endforeach; ?>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Save settings</button>
            </div>
          </form>
        </div>
      </div>
    </section>
<?php include 'footer.php'; ?>
