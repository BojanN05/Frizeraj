<?php
require 'admin.php';
require '../app/confing/database.php';
require '../app/layout/head.php';
require 'layout/header.php';

// Dohvatanje korisnika
$query = "
    SELECT 
        u.id_user,
        u.first_name,
        u.last_name,
        u.email,
        u.is_active,
        r.name_role AS role_name,
        u.id_role
    FROM users u
    JOIN roles r ON u.id_role = r.id_role
    ORDER BY u.created_at DESC
";

$result = $conn->query($query);
?>

<div class="container mt-5">
    <h2 class="mb-4">Korisnici</h2>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Ime</th>
                <th>Email</th>
                <th>Uloga</th>
                <th>Status</th>
                <th>Akcija</th>
            </tr>
        </thead>
        <tbody>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['id_user'] ?></td>

                    <td>
                        <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                    </td>

                    <td><?= htmlspecialchars($user['email']) ?></td>

                    <td>
                        <span class="badge bg-<?= $user['id_role'] == 1 ? 'warning' : 'secondary' ?>">
                            <?= htmlspecialchars($user['role_name']) ?>
                        </span>
                    </td>

                    <td>
                        <?php if ($user['is_active']): ?>
                            <span class="badge bg-success">Aktivan</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Neaktivan</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($user['id_role'] != 1): ?>
                            <a href="toggle_user.php?id=<?= $user['id_user'] ?>&status=<?= $user['is_active'] ?>"
                               class="btn btn-sm <?= $user['is_active'] ? 'btn-danger' : 'btn-success' ?>">
                                <?= $user['is_active'] ? 'Deaktiviraj' : 'Aktiviraj' ?>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Admin</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Nema korisnika</td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>

<?php require 'layout/footer.php'; ?>
