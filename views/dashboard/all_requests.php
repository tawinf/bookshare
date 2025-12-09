<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<h1 class="mb-4">ประวัติคำขอแลกเปลี่ยนทั้งหมด</h1>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">หนังสือ</th>
                        <th scope="col">เจ้าของ</th>
                        <th scope="col">ผู้ขอ</th>
                        <th scope="col">สถานะ</th>
                        <th scope="col">วันที่ส่งคำขอ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['owner_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['requester_name']); ?></td>
                            <td>
                                <?php 
                                    $status = $row['status'];
                                    $badgeClass = 'bg-secondary';
                                    if ($status == 'pending') {
                                        $badgeClass = 'bg-warning text-dark';
                                    } elseif ($status == 'accepted') {
                                        $badgeClass = 'bg-success';
                                    } elseif ($status == 'rejected') {
                                        $badgeClass = 'bg-danger';
                                    }
                                    echo "<span class=\"badge $badgeClass\">" . ucfirst($status) . "</span>";
                                ?>
                            </td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>