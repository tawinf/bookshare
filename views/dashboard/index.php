<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<h1 class="mb-4">Dashboard ภาพรวมระบบ</h1>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary shadow">
            <div class="card-body">
                <h5 class="card-title fs-2"><?php echo $totalBooks; ?></h5>
                <p class="card-text">หนังสือทั้งหมดในระบบ</p>
                <a href="index.php?controller=book&action=index" class="text-white stretched-link">ดูรายละเอียด &rarr;</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h5 class="card-title fs-2"><?php echo $totalUsers; ?></h5>
                <p class="card-text">สมาชิกทั้งหมด</p>
                <a href="index.php?controller=user&action=index" class="text-white stretched-link">ดูรายละเอียด &rarr;</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-warning shadow">
            <div class="card-body">
                <h5 class="card-title fs-2"><?php echo $pendingRequests; ?></h5>
                <p class="card-text">คำขอแลกเปลี่ยนที่รอดำเนินการ</p>
                <a href="index.php?controller=dashboard&action=allRequests" class="text-white stretched-link">
                    ดูรายละเอียดทั้งหมด &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-xl-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">สถานะหนังสือ</h5>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="bookStatusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-xl-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">คำขอใหม่ (7 วันล่าสุด)</h5>
            </div>
            <div class="card-body">
                <canvas id="dailyRequestsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-xl-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">สถานะคำขอแลกเปลี่ยน</h5>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="exchangeStatusChart"></canvas>
            </div>
        </div>
    </div>

    
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. กราฟสถานะหนังสือ (Doughnut Chart)
    const bookStatusCtx = document.getElementById('bookStatusChart').getContext('2d');
    const bookStatusChart = new Chart(bookStatusCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($bookStatusLabels); ?>,
            datasets: [{
                label: 'จำนวนหนังสือ',
                data: <?php echo json_encode($bookStatusValues); ?>,
                backgroundColor: ['rgb(25, 135, 84)', 'rgb(108, 117, 125)'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });

    // 2. กราฟคำขอรายวัน (Bar Chart)
    const dailyRequestsCtx = document.getElementById('dailyRequestsChart').getContext('2d');
    const dailyRequestsChart = new Chart(dailyRequestsCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($dailyRequestsLabels); ?>,
            datasets: [{
                label: 'จำนวนคำขอ',
                data: <?php echo json_encode($dailyRequestsValues); ?>,
                backgroundColor: 'rgba(13, 110, 253, 0.7)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // 3. กราฟสถานะคำขอแลกเปลี่ยน (Pie Chart) - (เพิ่มใหม่)
    const exchangeStatusCtx = document.getElementById('exchangeStatusChart').getContext('2d');
    const exchangeStatusChart = new Chart(exchangeStatusCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($exchangeStatusLabels); ?>,
            datasets: [{
                label: 'จำนวนคำขอ',
                data: <?php echo json_encode($exchangeStatusValues); ?>,
                backgroundColor: [
                    'rgb(255, 193, 7)',  // สีเหลืองสำหรับ Pending
                    'rgb(25, 135, 84)',   // สีเขียวสำหรับ Accepted
                    'rgb(220, 53, 69)'    // สีแดงสำหรับ Rejected
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });

});
</script>



<?php include_once __DIR__ . '/../layouts/footer.php'; ?>