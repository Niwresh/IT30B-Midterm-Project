<?php
session_start();
if (!isset($_SESSION['Email'])) {
    header("Location: index.php");
    exit();
}

include 'connect.php';

// query statement of books
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System - Dashboard</title>
    <link rel="stylesheet" href="style/homepage.css">
</head>
<body>
    <header>
        <div class="rec1">
        <h1 class="h1">Welcome to the Library Management System</h1>
        <p class="p1">Logged in as: <?php echo $_SESSION['Email']; ?></p>
        <a href="logout.php" class="btn1">Logout</a>
        </div>
    </header>

    <main>
<section class="books">
    <h2 class="h2">Available Books</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['title'] . "</td>";
                echo "<td>" . $row['author'] . "</td>";
                echo "<td>" . ($row['is_available'] ? "Available" : "Checked Out") . "</td>";
                echo "<td>";
                
                // button sa available na libro
                if ($row['is_available']) {
                    echo '<form method="post" action="borrow.php">
                            <input type="hidden" name="book_id" value="' . $row['id'] . '">
                            <button type="submit">Borrow</button>
                          </form>';
                } else {
                    echo '<form method="post" action="return.php">
                            <input type="hidden" name="book_id" value="' . $row['id'] . '">
                            <button type="submit">Return</button>
                          </form>';
                }
                
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No books available.</td></tr>";
        }
        ?>
    </table>
</section>

            </table>
        </section>

        <section class="account">
            <!-- <h2>Your Account</h2>
            <p>Manage your borrowed books and account settings.</p>  -->
            <a href="join_operation.php" class="btn2">JOIN</a>
            <a href="borrowed_books.php" class="btn3">Books Borrowed</a>
        </section>
    </main>
</body>
</html>

<?php $conn->close(); ?>
