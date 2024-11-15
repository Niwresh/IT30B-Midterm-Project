<?php
session_start();
if (!isset($_SESSION['Email'])) {
    header("Location: index.php");
    exit();
}

include 'connect.php';

// mag atiman sa gihulam sa user na naka login
$userEmail = $_SESSION['Email'];
$sql = "SELECT books.title, books.author, borrowed_books.borrow_date, borrowed_books.return_date 
        FROM borrowed_books
        JOIN books ON borrowed_books.book_id = books.id
        WHERE borrowed_books.user_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Borrowed Books</title>
    <link rel="stylesheet" href="style/borrow.css">
</head>
<body>
    <header>
    <div class="rec1">
        <h1 class="h1">Your Borrowed Books</h1>
        <a href="homepage.php" class="btn">Back to Dashboard</a>
    </div>
    </header>

    <main>
        <section class="borrowed-books">
            <h2 class="h2">Books You've Borrowed</h2>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['title'] . "</td>";
                        echo "<td>" . $row['author'] . "</td>";
                        echo "<td>" . $row['borrow_date'] . "</td>";
                        echo "<td>" . ($row['return_date'] ? $row['return_date'] : "Not Returned") . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>You have not borrowed any books.</td></tr>";
                }
                ?>
            </table>
        </section>
    </main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
