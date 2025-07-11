<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $ticket_id = $_POST['ticket_id'];
  $solution = $_POST['solution'];
  $actual_price = $_POST['actual_price'];
  $seller_price = $_POST['seller_price'];

  $stmt = $conn->prepare("INSERT INTO ticket_solutions (ticket_id, solution, actual_price, seller_price) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isss", $ticket_id, $solution, $actual_price, $seller_price);
  $stmt->execute();

  // Optional: update ticket status
  $conn->query("UPDATE tickets SET status='done' WHERE id=$ticket_id");

  echo "Ticket marked as solved.";
}
?>
