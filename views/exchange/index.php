<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<h1 class="mb-4">ตลาดแลกเปลี่ยนหนังสือ</h1>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <?php if ($row['image']): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>" style="height: 250px; object-fit: cover;">
                <?php else: ?>
                    <div class="d-flex justify-content-center align-items-center card-img-top bg-secondary text-white" style="height: 250px;">
                        <span>ไม่มีรูปภาพ</span>
                    </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <h5 class="card-title text-truncate"><?php echo htmlspecialchars($row['title']); ?></h5>
                    <p class="card-text text-muted text-truncate">โดย: <?php echo htmlspecialchars($row['author']); ?></p>
                    <p class="card-text"><small class="text-muted">ประกาศโดย: <?php echo htmlspecialchars($row['username']); ?></small></p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                     <a href="index.php?controller=exchange&action=show&id=<?php echo $row['id']; ?>" class="btn btn-outline-primary w-100">ดูรายละเอียด</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>