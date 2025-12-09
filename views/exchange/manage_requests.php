<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<h1 class="mb-4">จัดการคำขอแลกเปลี่ยน</h1>

<div class="card mb-5">
    <div class="card-header">
        <h3 class="fs-4">คำขอที่ได้รับ</h3>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>หนังสือ</th>
                    <th>ผู้ขอ</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $incomingStmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['requester_name']); ?></td>
                        <td>
                            <?php if ($row['status'] == 'pending'): ?>
                                <span class="badge bg-warning">รอดำเนินการ</span>
                            <?php elseif ($row['status'] == 'accepted'): ?>
                                <span class="badge bg-success">ยอมรับแล้ว</span>
                            <?php else: ?>
                                <span class="badge bg-danger">ปฏิเสธแล้ว</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status'] == 'pending'): ?>
                                <a href="index.php?controller=exchange&action=acceptRequest&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">ยอมรับ</a>
                                <a href="index.php?controller=exchange&action=rejectRequest&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">ปฏิเสธ</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <h3 class="fs-4">คำขอที่ฉันส่งไป</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>หนังสือ</th>
                    <th>เจ้าของ</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                 <?php while ($row = $outgoingStmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['owner_name']); ?></td>
                        <td>
                           <?php if ($row['status'] == 'pending'): ?>
                                <span class="badge bg-warning">รอดำเนินการ</span>
                            <?php elseif ($row['status'] == 'accepted'): ?>
                                <span class="badge bg-success">ยอมรับแล้ว</span>
                            <?php else: ?>
                                <span class="badge bg-danger">ปฏิเสธแล้ว</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>