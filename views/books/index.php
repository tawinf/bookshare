<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-2">หนังสือ</h1>
    <?php if (isset($_SESSION['user_id'])): // --- CHECK LOGIN --- ?>
    <a href="index.php?controller=book&action=create" class="btn btn-primary">เพิ่มหนังสือใหม่</a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">รูปปก</th> <th scope="col">ชื่อเรื่อง</th>
                    <th scope="col">ผู้แต่ง</th>
                    <th scope="col">สถานะ</th>

                    <?php if (isset($_SESSION['user_id'])): // --- CHECK LOGIN --- ?>

                    <th scope="col" style="width: 15%;">จัดการ</th>

                    <?php endif; ?>


                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <th scope="row"><?php echo $row['id']; ?></th>
                        <td>
                            <?php if ($row['image']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" style="width: 50px; height: 70px; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted">ไม่มีรูป</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td>
                            <?php if ($row['status'] == 'available'): ?>
                                <span class="badge bg-success">พร้อมแลกเปลี่ยน</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">แลกเปลี่ยนแล้ว</span>
                            <?php endif; ?>
                        </td>
                        
                        <?php if (isset($_SESSION['user_id'])): // --- CHECK LOGIN --- ?>

                        <td>
                            <a href="index.php?controller=book&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                            <a href="index.php?controller=book&action=destroy&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่?');">ลบ</a>
                        </td>

                        <?php endif; ?>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>