<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <?php if ($book->image): ?>
                <img src="uploads/<?php echo htmlspecialchars($book->image); ?>" class="img-fluid rounded shadow-sm" alt="<?php echo htmlspecialchars($book->title); ?>">
            <?php else: ?>
                 <div class="d-flex justify-content-center align-items-center rounded shadow-sm bg-secondary text-white" style="height: 400px; width:100%;">
                    <span>ไม่มีรูปภาพ</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            
            <h1 class="mb-3"><?php echo htmlspecialchars($book->title); ?></h1>
            <h4 class="text-muted mb-3">โดย <?php echo htmlspecialchars($book->author); ?></h4>
            <p class="lead"><?php echo nl2br(htmlspecialchars($book->description)); ?></p>
            <hr>
            <p><strong>สถานะ:</strong> 
            <?php if ($book->status == 'available'): ?>
            <span class="badge bg-success">พร้อมแลกเปลี่ยน</span>
            <?php else: ?>
            <span class="badge bg-secondary">แลกเปลี่ยนแล้ว</span>
            <?php endif; ?>
            <p><strong>เจ้าของ:</strong> <?php echo htmlspecialchars($book->owner_username); ?></p>

            <hr>
            <?php
            // Logic การแสดงผลปุ่ม/ข้อความ
            $isOwner = ($book->user_id == $_SESSION['user_id']);
            $isAvailable = ($book->status == 'available'); // ต้องดึง status มาใน readOne() ก่อน
            $alreadyRequested = (isset($_GET['status']) && $_GET['status'] == 'already_requested');

            if ($isOwner) {
                echo '<p class="text-info">นี่คือหนังสือของคุณ</p>';
            } elseif (!$isAvailable) {
                echo '<p class="text-danger">หนังสือเล่มนี้ถูกแลกเปลี่ยนไปแล้ว</p>';
            } elseif ($alreadyRequested) {
                echo '<div class="alert alert-warning">คุณได้ส่งคำขอสำหรับหนังสือเล่มนี้ไปแล้ว</div>';
            } else {
                // ถ้าทุกอย่างผ่าน ให้แสดงปุ่ม
                echo '<a href="index.php?controller=exchange&action=requestExchange&id=' . $book->id . '" class="btn btn-primary btn-lg mt-3">ขอแลกเปลี่ยน</a>';
            }

            if(isset($_GET['status']) && $_GET['status'] == 'requested') {
                echo '<div class="alert alert-success mt-3">ส่งคำขอแลกเปลี่ยนสำเร็จ!</div>';
            }
            ?>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>