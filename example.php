<?php
// Database connection parameters
$servername = "localhost";
$username = "root";  // Default for XAMPP
$password = "";      // Default for XAMPP, but use your password if set
$dbname = "user_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $nationality = $_POST['nationality'];

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, age, nationality) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $email, $age, $nationality);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}
// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data Entry</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required><br><br>

        <label for="nationality">Nationality:</label>
        <input type="text" id="nationality" name="nationality" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>