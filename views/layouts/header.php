<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF--8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Books Share</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Books Share</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- เมนู bar สำหรับ ADMIN-->                   
                <ul class="navbar-nav me-auto">
  
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=dashboard&action=index">Dashboard</a> </li>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=book&action=index">จัดการหนังสือ</a> 
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=dashboard&action=allRequests">คำขอในระบบ</a> </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=user&action=index">จัดการผู้ใช้</a>
                    </li>

                    <?php endif; ?>
                    

                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'member'): ?>
                    <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=exchange&action=index">ตลาดแลกเปลี่ยน</a> 
                    </li>
                    
                    <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=exchange&action=myBooks">หนังสือของฉัน</a> 
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=exchange&action=manageRequests">จัดการคำขอ</a> 
                    </li>
                    <?php endif; ?>
                    
                    
                </ul>

               
                
                <ul class="navbar-nav">
                     <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                สวัสดี, <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="index.php?controller=auth&action=logout">ออกจากระบบ</a></li>
                            </ul>
                        </li>
                     <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=auth&action=login">เข้าสู่ระบบ</a>
                        </li>
                     <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container py-4">
    