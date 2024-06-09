<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flight_id = $_POST['flight_id'];
    $hotel_id = $_POST['hotel_id'];
    $activity_id = $_POST['activity_id'];
    $booking_date = date("Y-m-d");

    $sql = "INSERT INTO bookings (user_id, flight_id, hotel_id, activity_id, booking_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiii", $user_id, $flight_id, $hotel_id, $activity_id, $booking_date);

    if ($stmt->execute()) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$sql_flights = "SELECT * FROM flights";
$result_flights = $conn->query($sql_flights);

$sql_hotels = "SELECT * FROM hotels";
$result_hotels = $conn->query($sql_hotels);

$sql_activities = "SELECT * FROM activities";
$result_activities = $conn->query($sql_activities);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to Your Dashboard</h1>
    <form action="dashboard.php" method="post">
        <h2>Book a Flight</h2>
        <select name="flight_id" required>
            <?php while ($row = $result_flights->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['flight_number'] ?> - <?= $row['departure'] ?> to <?= $row['arrival'] ?> ($<?= $row['price'] ?>)</option>
            <?php endwhile; ?>
        </select>
        <h2>Book a Hotel</h2>
        <select name="hotel_id" required>
            <?php while ($row = $result_hotels->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['hotel_name'] ?> - <?= $row['location'] ?> ($<?= $row['price_per_night'] ?> per night)</option>
            <?php endwhile; ?>
        </select>
        <h2>Book an Activity</h2>
        <select name="activity_id" required>
            <?php while ($row = $result_activities->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['activity_name'] ?> - <?= $row['location'] ?> ($<?= $row['price'] ?>)</option>
            <?php endwhile; ?>
        </select>
        <br><br>
        <input type="submit" value="Book Now">
    </form>
</body>
</html>
