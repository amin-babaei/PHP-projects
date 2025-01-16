<?php
include 'db_connect.php';
include_once 'jdf.php';

$sortOrder = isset($_POST['sort']) ? $_POST['sort'] : 'desc';

$sql = "SELECT * FROM posts ORDER BY created_at " . ($sortOrder === 'asc' ? 'ASC' : 'DESC');
$result = $conn->query($sql);

?>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <article class="d-flex gap-3 my-3">
            <img src="./card.jpg" alt="پست" width="150px" height="150px">
            <div class="d-flex flex-column justify-content-around">
                <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                <?php
                $timestamp = strtotime($row['created_at']);
                $createdAt = jdate('Y/m/d', $timestamp);
                ?>
                <span class="text-warning">تاریخ: <?php echo $createdAt; ?></span>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
            </div>
        </article>
    <?php endwhile; ?>
<?php else: ?>
    <p>هیچ پستی موجود نیست.</p>
<?php endif; ?>

<?php
$conn->close();
?>