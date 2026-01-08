<?php
session_start();
require 'koneksi.php';

/* ===============================
   GUARD: HARUS LOGIN
================================ */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ===============================
   AMBIL DATA BOOKING USER
================================ */
$query = "
    SELECT 
        b.kode_booking,
        b.tanggal,
        b.status,
        b.created_at,
        k.nama AS nama_konselor,
        k.spesialisasi,
        k.harga
    FROM booking b
    JOIN konselor k ON b.konselor_id = k.id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | HealthySoul</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Dashboard Saya</h2>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>

    <!-- ALERT SUKSES -->
    <?php if (isset($_GET['booking']) && $_GET['booking'] === 'success'): ?>
        <div class="alert alert-success">
            ✅ Booking berhasil dibuat. Silakan tunggu konfirmasi.
        </div>
    <?php endif; ?>

    <!-- DATA BOOKING -->
    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">Riwayat Booking</h5>

            <?php if ($result->num_rows === 0): ?>
                <p class="text-muted">Belum ada booking.</p>
            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Konselor</th>
                                <th>Spesialisasi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['kode_booking']) ?></td>
                                <td><?= htmlspecialchars($row['nama_konselor']) ?></td>
                                <td><?= htmlspecialchars($row['spesialisasi']) ?></td>
                                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                <td>
                                    <?php
                                    $badge = match ($row['status']) {
                                        'menunggu' => 'warning',
                                        'dibayar'  => 'primary',
                                        'selesai'  => 'success',
                                        'batal'    => 'danger',
                                        default    => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                </td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="konselor.php" class="btn btn-success">
            + Booking Konselor Lagi
        </a>
    </div>

</div>

</body>
</html>