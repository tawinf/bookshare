<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-2">หนังสือของฉัน</h1>
    <a href="index.php?controller=exchange&action=create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> เพิ่มหนังสือสำหรับแลกเปลี่ยน
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>รูปปก</th>
                    <th>ชื่อเรื่อง</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $row['image'] ? 'uploads/' . htmlspecialchars($row['image']) : 'https://via.placeholder.com/50x70.png?text=No+Image'; ?>" 
                                 alt="<?php echo htmlspecialchars($row['title']); ?>" 
                                 style="width: 50px; height: 70px; object-fit: cover;">
                        </td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td>
                            <?php if ($row['status'] == 'available'): ?>
                                <span class="badge bg-success">พร้อมแลกเปลี่ยน</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">แลกเปลี่ยนแล้ว</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?controller=exchange&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                            <a href="index.php?controller=exchange&action=destroy&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่?');">ลบ</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>