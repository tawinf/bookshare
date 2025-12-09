<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4">แก้ไขข้อมูลผู้ใช้: <?php echo htmlspecialchars($user->username); ?></h1>

    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>


    <form action="index.php?controller=user&action=update" method="post">
        <input type="hidden" name="id" value="<?php echo $user->id; ?>">
        
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user->username); ?>" 
            readonly 
            style="background-color: #f5f5f5; cursor: not-allowed;" 
            required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password ใหม่:</label>
            <input type="password" class="form-control"id="password" name="password">
            <small>เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน</small>
        </div>
        
        <div class="mb-3">
            <label for="role">Role:</label>
            <select name="role" id="role" class="form-select">
                <option value="member" <?php if($user->role == 'member') echo 'selected'; ?>>Member</option>
                <?php //if ($_SESSION['user_id'] != $user->id): ?>
                <option value="admin" <?php if($user->role == 'admin') echo 'selected'; ?>>Admin</option>
                <?php //endif; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">อัปเดต</button>
    </form>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>