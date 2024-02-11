<?php
// Include your database connection code here
include('../config.php'); // Replace 'db_connection.php' with your database connection script
session_start();

// Maximum file size in bytes (150MB)
$maxFileSize = 150 * 1024 * 1024; // 150MB

// Array of allowed file extensions
$allowedExtensions = array("pdf", "docx", "doc", "pptx", "ppt", "xlsx", "xls", "odt");

// Check if the form was submitted and if the required fields are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'], $_POST['folder'], $_POST['desc'])) {
    // Get form data
    $user = $_SESSION['user_id'];
    $redirect = $_SERVER['HTTP_REFERER'];
    $documentName = $_POST['name'];
    $folderID = $_POST['folder'];
    $documentDesc = $_POST['desc'];

    // Check if a file was uploaded
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        // Verify if the uploaded file is a PDF or allowed extensions
        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileType, $allowedExtensions)) {
            // Handle case where uploaded file has an invalid extension
            $_SESSION['file_type_error'] = "error";
            header("location: $redirect");
            exit();
        }

        // Check if the file size is within the allowed limit
        if ($file['size'] > $maxFileSize) {
            // Handle case where uploaded file exceeds the maximum allowed size
            $_SESSION['file_size_error'] = "error";
            header("location: $redirect");
            exit();
        }

        // Proceed with uploading the file
        $foldername = ''; // Initialize folder name
        $uploadDir = ''; // Initialize upload directory
        $uploadFile = ''; // Initialize upload file path

        // Fetch folder name from the database
        $sql1 = "SELECT folder_name FROM folders WHERE folder_id = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("i", $folderID);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($result1->num_rows > 0) {
            $row1 = $result1->fetch_assoc();
            $foldername = $row1['folder_name'];
            $uploadDir = '../folders_list/' . $foldername . '/';
            $uploadFile = $uploadDir . basename($file['name']);

            // Check if the file already exists in the folder
            if (file_exists($uploadFile)) {
                $_SESSION['file_exists'] = "success";
                header("location: $redirect");
                exit();
            }

            // Move uploaded file to the destination directory
            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                // File uploaded successfully, insert data into the database
                $fileSize = $file['size']; // Get the file size
                $sql = "INSERT INTO documents (doc_user, doc_name, doc_folder, doc_desc, doc_path, doc_size) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isissi", $user, $documentName, $folderID, $documentDesc, $uploadFile, $fileSize);

                if ($stmt->execute()) {
                    // Insertion successful
                    $_SESSION['doc_upload'] = "success";
                    header("location: $redirect");
                    exit();
                } else {
                    // Insertion failed
                    echo "Error: " . $stmt->error;
                }
            } else {
                // File upload failed
                echo "File upload failed!";
            }
        } else {
            echo "Error in fetching folder name from database";
        }
    } else {
        // Handle case where no file was uploaded
        $_SESSION['no_file_error'] = "error";
        header("location: $redirect");
        exit();
    }
} else {
    // Handle case where form data is missing
    echo "Form data is missing!";
}
?>
