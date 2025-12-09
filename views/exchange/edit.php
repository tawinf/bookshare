<?php include_once __DIR__ . '/../layouts/header.php'; ?>


<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2 class="fs-4">แก้ไขข้อมูลหนังสือ: <?php echo htmlspecialchars($book->title); ?></h2>
            </div>
            <div class="card-body">
                <form action="index.php?controller=exchange&action=update" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $book->id; ?>">
                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($book->image); ?>">

                    <div class="mb-3">
                        <label for="title" class="form-label">ชื่อเรื่อง (Title)</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($book->title); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">ผู้แต่ง (Author)</label>
                        <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($book->author); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">คำอธิบาย (Description)</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($book->description); ?></textarea>
                    </div>
                    <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select name="status" id="status" class="form-select">
                        <option value="available" <?php if($book->status == 'available') echo 'selected'; ?>>Available</option>
                        <option value="exchanged" <?php if($book->status == 'exchanged') echo 'selected'; ?>>Exchanged</option>
                    </select>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">รูปปกใหม่ (เว้นว่างไว้หากไม่ต้องการเปลี่ยน)</label>
                        <input class="form-control" type="file" id="image" name="image">
                    </div>
                    
                    <?php if ($book->image): ?>
                        <div class="mb-3">
                            <label class="form-label">รูปปัจจุบัน:</label><br>
                            <img src="uploads/<?php echo htmlspecialchars($book->image); ?>" alt="<?php echo htmlspecialchars($book->title); ?>" style="max-width: 150px; height: auto;">
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary">อัปเดต</button>
                    <a href="index.php?controller=book&action=index" class="btn btn-secondary">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>