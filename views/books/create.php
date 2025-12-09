<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="card-body">
    <form action="index.php?controller=book&action=store" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">ชื่อเรื่อง (Title)</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">ผู้แต่ง (Author)</label>
            <input type="text" class="form-control" id="author" name="author" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">คำอธิบาย (Description)</label>
            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
        </div>
        <div class="mb-3">
        <label for="status" class="form-label">Status:</label>
        <select name="status" id="status" class="form-select">
            <option value="available">Available</option>
            <option value="exchanged">Exchanged</option>
        </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">รูปปกหนังสือ</label>
            <input class="form-control" type="file" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="index.php?controller=book&action=index" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>



<?php include_once __DIR__ . '/../layouts/footer.php'; ?>