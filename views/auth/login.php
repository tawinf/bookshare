<?php 
include_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">                   
                    <h1 class="card-title text-center mb-4 fs-3">เข้าสู่ระบบ</h1>

                    <?php if (isset($_GET['status']) && $_GET['status'] == 'registered'): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                        สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?controller=auth&action=doLogin" method="post">
                    <div>
                        <label for="username" class="form-label">ชื่อผู้ใช้:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>

                    <div>
                        <label for="password" class="form-label">รหัสผ่าน:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3">เข้าสู่ระบบ</button>
                    </form>

                    <div class="text-center mt-4">
                        ยังไม่มีบัญชี? <a href="index.php?controller=auth&action=register" class="text-muted mb-0">สมัครสมาชิกที่นี่</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// ใช้ footer ที่ไม่มี layout หลัก
include_once __DIR__ . '/../layouts/footer.php'; 
?>