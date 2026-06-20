<?php
require '../app/helper/protect.php';
require '../app/confing/database.php';

$msg = $_GET['msg'] ?? "";

// Uzmima aktivnu anketu 
$poll = $conn->query("SELECT * FROM polls WHERE is_active = 1 ORDER BY id_polls DESC LIMIT 1")->fetch_assoc();
?>

<div class="container mt-5 mb-5">
    <?php if($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if ($poll): ?>
        <?php
        // Uzmima odgovore
        $answers = $conn->query("SELECT * FROM polls_answers WHERE id_polls = {$poll['id_polls']}");
        ?>
        <form action="vote.php" method="POST">
            <h4><?= htmlspecialchars($poll['question']) ?></h4>
            <?php while($ans = $answers->fetch_assoc()): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" value="<?= $ans['id_answers'] ?>" required>
                    <label class="form-check-label"><?= htmlspecialchars($ans['answer_text']) ?></label>
                </div>
            <?php endwhile; ?>
            <input type="hidden" name="id_polls" value="<?= $poll['id_polls'] ?>">
            <button type="submit" class="btn btn-primary mt-2">Glasaj</button>
        </form>

        <?php
        $answers = $conn->query("SELECT * FROM polls_answers WHERE id_polls = {$poll['id_polls']}");
        $total_votes = $conn->query("SELECT COUNT(*) FROM poll_votes WHERE id_polls = {$poll['id_polls']}")->fetch_row()[0];

        echo "<h5 class='mt-3'>Rezultati ankete:</h5>";

        while($ans = $answers->fetch_assoc()) {
            $votes = $conn->query("SELECT COUNT(*) FROM poll_votes WHERE id_answers = {$ans['id_answers']}")->fetch_row()[0];
            $percent = ($total_votes > 0) ? round($votes / $total_votes * 100) : 0;
            echo "<p>{$ans['answer_text']}: $votes glasova ($percent%)</p>";
        }
        ?>
    <?php else: ?>
        <p>Trenutno nema aktivnih anketa.</p>
    <?php endif; ?>
</div>