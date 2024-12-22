<?php
// Database connection parameters
$servername = "localhost";
$username = "root";  // Default for XAMPP
$password = "";      // Default for XAMPP, but use your password if set
$dbname = "campus_chronicles";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content']; // Collect the content of the article
    $media = $_FILES['media']['name'];
    $mediaTmpName = $_FILES['media']['tmp_name'];
    
    // Handle media upload
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($media);
    $uploadOk = 1;
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($mediaTmpName, $targetFile)) {
            // Prepare and bind SQL statement
            $stmt = $conn->prepare("INSERT INTO articles (title, category, content, media) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $category, $content, $media);
            
            // Execute the statement
            if ($stmt->execute()) {
                echo "Article published successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="title">Title of the Article:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="campus_news">Campus News</option>
            <option value="sports">Sports</option>
            <option value="events">Events</option>
            <option value="academic">Academic</option>
            <option value="other">Other</option>
        </select><br><br>

        <label for="content">Content of the Article:</label>
        <textarea id="content" name="content" required></textarea><br><br>

        <label for="media">Media (Image/Video):</label>
        <input type="file" id="media" name="media" accept="image/*,video/*" required><br><br>

        <input type="submit" value="Publish">
    </form>
</body>
</html>