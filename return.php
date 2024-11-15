<?php
session_start();
include 'connect.php';

if (isset($_POST['book_id']) && isset($_SESSION['Email'])) {
    $book_id = $_POST['book_id'];
    $user_email = $_SESSION['Email'];
    $return_date = date('Y-m-d');

    // mag update sa nabilin na libro
    $updateBook = "UPDATE books SET is_available = 1 WHERE id = $book_id";
    
    // mag update sa pikas table
    $returnBook = "UPDATE borrowed_books SET return_date = '$return_date' WHERE book_id = $book_id AND user_email = '$user_email' AND return_date IS NULL";

    if ($conn->query($updateBook) && $conn->query($returnBook)) {
        header("Location: homepage.php");
    } else {
        echo "Error returning book: " . $conn->error;
    }
}

$conn->close();
?>
