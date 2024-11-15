<?php
session_start();
include 'connect.php';

if (isset($_POST['book_id']) && isset($_SESSION['Email'])) {
    $book_id = $_POST['book_id'];
    $user_email = $_SESSION['Email'];
    $borrow_date = date('Y-m-d');

    // Update sa libro nabilin
    $updateBook = "UPDATE books SET is_available = 0 WHERE id = $book_id";
    
    // mag sulod ug nahulam na libro
    $borrowBook = "INSERT INTO borrowed_books (book_id, user_email, borrow_date) VALUES ($book_id, '$user_email', '$borrow_date')";

    if ($conn->query($updateBook) && $conn->query($borrowBook)) {
        header("Location: homepage.php");
    } else {
        echo "Error borrowing book: " . $conn->error;
    }
}

$conn->close();
?>
