<?php
require 'admin.php';
require '../app/confing/database.php';
require '../app/layout/head.php';
require 'layout/header.php';

$query = "
    SELECT 
        id_book,
        fullName,
        email,
        phone,
        service,
        date,
        time,
        created_at
    FROM books
    ORDER BY date DESC, time ASC
";

$result = $conn->query($query);
?>

<div class="container mt-5">
    <h2 class="mb-4">Rezervacije</h2>
    <p class="text-muted">
    Prikazani su samo zauzeti termini. Svi termini koji nisu navedeni smatraju se slobodnim.
</p>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Ime</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Usluga</th>
                <th>Datum</th>
                <th>Vreme</th>
                <th>Status</th>
                <th>Akcija</th>
            </tr>
        </thead>
        <tbody>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_book'] ?></td>
                    <td><?= htmlspecialchars($row['fullName']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td>
                        <span class="badge bg-secondary">
                            <?= ucfirst($row['service']) ?>
                        </span>
                    </td>
                    <td><?= $row['date'] ?></td>
                    <td><?= substr($row['time'], 0, 5) ?></td>

                    <td>
                        <span class="badge bg-danger">Zauzeto</span>
                    </td>

                    <td>
                        <a href="delete_book.php?id=<?= $row['id_book'] ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Obrisati rezervaciju?')">
                           Obriši
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">Nema rezervacija</td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>

<?php require 'layout/footer.php'; ?>