<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="card-title text-center mb-4 fs-3">สมัครสมาชิก</h1>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    

                    <form action="index.php?controller=auth&action=doRegister" method="post">
                        <input type="hidden" name="role" value="member">
                        <div class="mb-3">
                            <label for="username" class="form-label">ชื่อผู้ใช้ (Username)</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">รหัสผ่าน (Password)</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">ยืนยันรหัสผ่าน</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3">สมัครสมาชิก</button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">มีบัญชีอยู่แล้ว? <a href="index.php?controller=auth&action=login">เข้าสู่ระบบที่นี่</a></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>