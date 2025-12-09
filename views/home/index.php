<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">คลังหนังสือสำหรับแลกเปลี่ยน</h1>
        <p class="lead text-muted">เข้าร่วมชุมชนของเราเพื่อเริ่มแลกเปลี่ยนหนังสือเล่มโปรดของคุณ</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <img src="<?php echo $row['image'] ? 'uploads/' . htmlspecialchars($row['image']) : 'https://via.placeholder.com/300x400.png?text=Book+Cover'; ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($row['title']); ?>" 
                             style="height: 300px; object-fit: cover;">
                        
                        <?php if ($row['status'] == 'available'): ?>
                            <span class="position-absolute top-0 start-0 bg-success text-white py-1 px-2 m-2 rounded-pill" style="font-size: 0.8rem;">พร้อมแลกเปลี่ยน</span>
                        <?php else: ?>
                            <span class="position-absolute top-0 start-0 bg-secondary text-white py-1 px-2 m-2 rounded-pill" style="font-size: 0.8rem;">แลกเปลี่ยนแล้ว</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title text-truncate"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="card-text text-muted text-truncate">โดย: <?php echo htmlspecialchars($row['author']); ?></p>
                        <p class="card-text"><small class="text-muted">ประกาศโดย: <?php echo htmlspecialchars($row['username']); ?></small></p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                         <?php if (isset($_SESSION['user_id'])): ?>
                             <a href="index.php?controller=exchange&action=show&id=<?php echo $row['id']; ?>" class="btn btn-outline-primary w-100">ดูรายละเอียด</a>
                         <?php else: ?>
                            <a href="index.php?controller=auth&action=login" class="btn btn-primary w-100">เข้าสู่ระบบเพื่อขอแลก</a>
                         <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>