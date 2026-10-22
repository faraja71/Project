<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $name   = trim($_POST['name'] ?? '');
    $desc   = trim($_POST['description'] ?? '');
    $id     = (int)($_POST['id'] ?? 0);

    if ($action === 'add' && $name !== '') {
        $s = $conn->prepare("INSERT INTO product_categories (name, description) VALUES (?, ?)");
        $s->bind_param('ss', $name, $desc);
        $s->execute();
        header('Location: product-categories.php?msg=added'); exit;
    }
    if ($action === 'update' && $name !== '') {
        $s = $conn->prepare("UPDATE product_categories SET name=?, description=? WHERE id=?");
        $s->bind_param('ssi', $name, $desc, $id);
        $s->execute();
        header('Location: product-categories.php?msg=updated'); exit;
    }
    if ($action === 'delete') {
        $s = $conn->prepare("DELETE FROM product_categories WHERE id=?");
        $s->bind_param('i', $id);
        $s->execute();
        header('Location: product-categories.php?msg=deleted'); exit;
    }
    header('Location: product-categories.php?msg=empty'); exit;
}

$rows = $conn->query("SELECT id, name, description FROM product_categories ORDER BY name");
$msgs = [
    'added'   => ['success', 'Category added.'],
    'updated' => ['info',    'Category updated.'],
    'deleted' => ['danger',  'Category deleted.'],
    'empty'   => ['warning', 'Category name is required.'],
];
$flash = $msgs[$_GET['msg'] ?? ''] ?? null;

$pageTitle = 'Product Categories';
$extraJs = <<<'JS'
$('#btnAdd').on('click', function () {
  $('#modalTitle').text('Add Category');
  $('#fAction').val('add'); $('#fId').val('');
  $('#fName').val(''); $('#fDesc').val('');
  $('#catModal').modal('show');
});
$('.btn-edit').on('click', function () {
  var b = $(this);
  $('#modalTitle').text('Update Category');
  $('#fAction').val('update'); $('#fId').val(b.data('id'));
  $('#fName').val(b.data('name')); $('#fDesc').val(b.data('desc'));
  $('#catModal').modal('show');
});
JS;
include 'header.php';
?>
    <div class="content-header">
      <div class="container-fluid">
        <h1 class="m-0">Product Categories</h1>
        <p class="text-muted mb-0">Group your products into categories.</p>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <?php if ($flash): ?>
          <div class="alert alert-<?= $flash[0] ?> alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?= htmlspecialchars($flash[1]) ?>
          </div>
        <?php endif; ?>

        <div class="card">
          <div class="card-header">
            <button class="btn btn-success" id="btnAdd"><i class="fas fa-plus-circle"></i> Add Category</button>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <thead>
                <tr><th style="width:60px">ID</th><th>Name</th><th>Description</th><th style="width:170px">Actions</th></tr>
              </thead>
              <tbody>
              <?php if ($rows && $rows->num_rows): while ($r = $rows->fetch_assoc()): ?>
                <tr>
                  <td><?= (int)$r['id'] ?></td>
                  <td><?= htmlspecialchars($r['name']) ?></td>
                  <td><?= htmlspecialchars($r['description']) ?></td>
                  <td>
                    <button class="btn btn-warning btn-sm btn-edit"
                      data-id="<?= (int)$r['id'] ?>"
                      data-name="<?= htmlspecialchars($r['name'], ENT_QUOTES) ?>"
                      data-desc="<?= htmlspecialchars($r['description'], ENT_QUOTES) ?>">
                      <i class="fas fa-edit"></i> Update</button>
                    <form method="post" class="d-inline" onsubmit="return confirm('Delete this category?');">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                      <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endwhile; else: ?>
                <tr><td colspan="4" class="text-center text-muted py-5">No categories yet. Click "Add Category" to start.</td></tr>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <div class="modal fade" id="catModal" tabindex="-1">
      <div class="modal-dialog">
        <form method="post" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add Category</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="action" id="fAction" value="add">
            <input type="hidden" name="id" id="fId">
            <div class="form-group">
              <label for="fName">Name</label>
              <input type="text" class="form-control" name="name" id="fName" required>
            </div>
            <div class="form-group">
              <label for="fDesc">Description</label>
              <textarea class="form-control" name="description" id="fDesc" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
<?php include 'footer.php'; ?>
