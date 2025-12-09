<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">จัดการข้อมูลผู้ใช้</h1>
        <a href="index.php?controller=user&action=create" class="btn btn-primary">เพิ่มผู้ใช้ใหม่</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>วันที่สมัคร</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <a href="index.php?controller=user&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                <?php if ($_SESSION['user_id'] != $row['id']): ?>
                                <a href="index.php?controller=user&action=destroy&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้นี้?');">ลบ</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>