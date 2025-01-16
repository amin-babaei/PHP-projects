<?php
include 'db_connect.php';
include_once 'jdf.php';

$sql = "SELECT * FROM comments ORDER BY created_at DESC";
$result = $conn->query($sql);


?>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="border mt-3 p-3">
                <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                <?php
                $timestamp = strtotime($row['created_at']);
                $createdAt = jdate('Y/m/d', $timestamp);
                ?>
                <span class="small"><?php echo $createdAt; ?></span>
                <p class="mt-3">
                    <?php echo htmlspecialchars($row['description']); ?>
                </p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>هیچ کامنتی موجود نیست.</p>
    <?php endif; ?>

<?php
$conn->close();
?>