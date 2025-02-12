<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History User</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <div id="content">

        <h2>Riwayat Aktivitas Pengguna</h2>

        <a href="<?= base_url('home/downloadHistoryPdf?' . $_SERVER['QUERY_STRING']) ?>">
            <button style="margin-bottom: 10px; padding: 8px 12px; background-color: #007BFF; color: white; border: none; cursor: pointer;">
                Download PDF
            </button>
        </a>

        <form method="GET" action="<?= base_url('home/historyUser') ?>">
            <label for="start_date">Dari Tanggal:</label>
            <input type="date" name="start_date" value="<?= esc($start_date ?? '') ?>">

            <label for="end_date">Sampai Tanggal:</label>
            <input type="date" name="end_date" value="<?= esc($end_date ?? '') ?>">

            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="Cari Username" value="<?= esc($username ?? '') ?>">

            <label for="activity">Jenis Aktivitas:</label>
            <select name="activity">
                <option value="">Semua</option>
                <option value="register" <?= (isset($activity) && $activity == 'register') ? 'selected' : '' ?>>Register</option>
                <option value="login" <?= (isset($activity) && $activity == 'login') ? 'selected' : '' ?>>Login</option>
            </select>

            <label for="keyword">Keyword:</label>
            <input type="text" name="keyword" placeholder="Cari..." value="<?= esc($keyword ?? '') ?>">

            <button type="submit">Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Jenis Aktivitas</th>
                    <th>Waktu</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($history)): ?>
                    <?php $no = 1;
                    foreach ($history as $h): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($h['username']) ?></td>
                            <td><?= esc(ucfirst($h['activity_type'])) ?></td>
                            <td><?= esc($h['activity_time']) ?></td>
                            <td><?= esc($h['user_agent']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

</body>

</html>