<?php
session_start();
if (!isset($_SESSION['Email'])) {
    header("Location: index.php");
    exit();
}

include 'connect.php';

$innerJoinQuery = "SELECT books.title, users.email, borrowed_books.borrow_date
                   FROM borrowed_books
                   INNER JOIN books ON borrowed_books.book_id = books.id
                   INNER JOIN users ON borrowed_books.user_email = users.email";  
$innerJoinResult = $conn->query($innerJoinQuery);


$leftJoinQuery = "SELECT books.title, borrowed_books.borrow_date
                  FROM books
                  LEFT JOIN borrowed_books ON books.id = borrowed_books.book_id"; 
$leftJoinResult = $conn->query($leftJoinQuery);


$rightJoinQuery = "SELECT users.email, books.title
                   FROM users
                   LEFT JOIN borrowed_books ON borrowed_books.user_email = users.email  
                   LEFT JOIN books ON borrowed_books.book_id = books.id";
$rightJoinResult = $conn->query($rightJoinQuery);


$fullOuterJoinQuery = "(SELECT books.title, borrowed_books.borrow_date 
                       FROM books 
                       LEFT JOIN borrowed_books ON books.id = borrowed_books.book_id)
                       UNION
                       (SELECT books.title, borrowed_books.borrow_date 
                       FROM borrowed_books 
                       RIGHT JOIN books ON borrowed_books.book_id = books.id)";

$fullOuterJoinResult = $conn->query($fullOuterJoinQuery);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Operations - Library Management System</title>
    <link rel="stylesheet" href="style/join_operations.css">
</head>
<body>
    <h1>Join Operations</h1>

    <h2>INNER JOIN: Borrowed Books with Borrower</h2>
    <a href="homepage.php" class="btn">Back to Dashboard</a>
    <table>
        <tr><th>Title</th><th>Borrower Email</th><th>Borrow Date</th></tr>
        <?php
        if ($innerJoinResult->num_rows > 0) {
            while ($row = $innerJoinResult->fetch_assoc()) {
                echo "<tr><td>{$row['title']}</td><td>{$row['email']}</td><td>{$row['borrow_date']}</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No data found.</td></tr>";
        }
        ?>
    </table>
    <p><strong>Explanation:</strong> This INNER JOIN retrieves only records where a book is borrowed by a user.</p>

    <h2>LEFT JOIN: All Books and Borrow Status</h2>
    <table>
        <tr><th>Title</th><th>Borrow Date (if any)</th></tr>
        <?php
        if ($leftJoinResult->num_rows > 0) {
            while ($row = $leftJoinResult->fetch_assoc()) {
                echo "<tr><td>{$row['title']}</td><td>" . ($row['borrow_date'] ?? "Not Borrowed") . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No data found.</td></tr>";
        }
        ?>
    </table>
    <p><strong>Explanation:</strong> This LEFT JOIN shows all books, displaying their borrow date if available.</p>

    <h2>RIGHT JOIN: All Users and Their Borrowed Books</h2>
    <table>
        <tr><th>User Email</th><th>Borrowed Book Title</th></tr>
        <?php
        if ($rightJoinResult->num_rows > 0) {
            while ($row = $rightJoinResult->fetch_assoc()) {
                echo "<tr><td>{$row['email']}</td><td>" . ($row['title'] ?? "No Book Borrowed") . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No data found.</td></tr>";
        }
        ?>
    </table>
    <p><strong>Explanation:</strong> This RIGHT JOIN shows all users and the books they've borrowed if any.</p>

    <h2>FULL OUTER JOIN (Using UNION): All Books and Borrow Logs</h2>
    <table>
        <tr><th>Title</th><th>Borrow Date</th></tr>
        <?php
        if ($fullOuterJoinResult->num_rows > 0) {
            while ($row = $fullOuterJoinResult->fetch_assoc()) {
                echo "<tr><td>{$row['title']}</td><td>" . ($row['borrow_date'] ?? "N/A") . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No data found.</td></tr>";
        }
        ?>
    </table>
    <p><strong>Explanation:</strong> This UNION simulates a FULL OUTER JOIN, showing all books and borrow records.</p>

</body>
</html>

<?php $conn->close(); ?>
