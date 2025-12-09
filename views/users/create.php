<?php include_once __DIR__ . '/../layouts/header.php'; ?>


<div class="container mt-4">
    <h1 class="mb-4">เพิ่มผู้ใช้ใหม่</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" role="alert">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

    <form action="index.php?controller=user&action=store" method="post">

    <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" id="username" class="form-control" name="username" required>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" id="password" class="form-control" name="password" required>
    </div>
    
    <div class="mb-3">
        <label for="role" class="form-label">Role:</label>
        <select name="role" id="role" class="form-select">
            <option value="member">Member</option>
            <option value="admin">Admin</option>
        </select>
    </div>


    

    <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>

</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>