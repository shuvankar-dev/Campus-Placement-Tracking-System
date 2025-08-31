<?php
session_start();
if (!isset($_SESSION['tpo_id'])) {
    header("Location: index.php");
    exit();
}

// Include database and email configuration
include('../config.php');
include('../email_config.php');

// Include PHPMailer
require_once '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit']) || (isset($_POST['dept_id']) && isset($_FILES['csv_file']))) {
    $dept_id = $_POST['dept_id'];
    $success_count = 0;
    $error_count = 0;
    $errors = [];

    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['name']) {
        $filename = $_FILES['csv_file']['tmp_name'];
        
        // Check if file was uploaded successfully
        if ($_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "File upload failed. Error code: " . $_FILES['csv_file']['error'];
            header("Location: uplodeSTD.php");
            exit();
        }
        
        // Check if it's a CSV file
        $file_extension = pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION);
        if (strtolower($file_extension) !== 'csv') {
            $_SESSION['error'] = "Please upload a valid CSV file.";
            header("Location: uplodeSTD.php");
            exit();
        }
        
        $file = fopen($filename, "r");
        if (!$file) {
            $_SESSION['error'] = "Could not open the CSV file.";
            header("Location: uplodeSTD.php");
            exit();
        }
        
        $row_number = 0;
        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
            $row_number++;
            
            // Skip empty rows
            if (empty(array_filter($data))) {
                continue;
            }
            
            // Check if we have the right number of columns (now expecting 6 columns without password)
            if (count($data) < 6) {
                $errors[] = "Row $row_number: Insufficient data columns (expected 6, got " . count($data) . ")";
                $error_count++;
                continue;
            }
            
            list($sname, $semail, $sdob, $sphone, $sgender, $scgpa) = $data;
            
            // Auto-generate password for each student
            $spassword = rand(10000000, 99999999);
            $hashed = password_hash($spassword, PASSWORD_DEFAULT);
            
            // Trim whitespace from all fields
            $sname = trim($sname);
            $semail = trim($semail);
            $sdob = trim($sdob);
            $sphone = trim($sphone);
            $sgender = trim($sgender);
            $scgpa = trim($scgpa);
            
            // Validate required fields
            if (empty($sname) || empty($semail) || empty($sdob) || empty($sphone) || empty($sgender) || empty($scgpa)) {
                $errors[] = "Row $row_number ($sname): Missing required fields";
                $error_count++;
                continue;
            }
            
            // Validate email format
            if (!filter_var($semail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row $row_number ($sname): Invalid email format";
                $error_count++;
                continue;
            }
            
            // Check if email already exists
            $email_check = "SELECT std_id FROM student WHERE semail = '" . mysqli_real_escape_string($conn, $semail) . "'";
            $email_result = mysqli_query($conn, $email_check);
            if (mysqli_num_rows($email_result) > 0) {
                $errors[] = "Row $row_number ($sname): Email already exists in database";
                $error_count++;
                continue;
            }
            
            // Parse date of birth
            $formats = ['Y-m-d', 'd-m-Y', 'd/m/Y', 'm/d/Y', 'Y/m/d'];
            $dateObj = false;
            
            foreach ($formats as $format) {
                $dateObj = DateTime::createFromFormat($format, $sdob);
                if ($dateObj !== false) break;
            }
            
            if ($dateObj) {
                $formattedDob = $dateObj->format('Y-m-d');
            } else {
                $errors[] = "Row $row_number ($sname): Invalid date format ($sdob)";
                $error_count++;
                continue;
            }
            
            // Validate CGPA
            if (!is_numeric($scgpa) || $scgpa < 0 || $scgpa > 10) {
                $errors[] = "Row $row_number ($sname): Invalid CGPA ($scgpa)";
                $error_count++;
                continue;
            }
            
            // Prepare SQL with proper escaping
            $sname_escaped = mysqli_real_escape_string($conn, $sname);
            $semail_escaped = mysqli_real_escape_string($conn, $semail);
            $sphone_escaped = mysqli_real_escape_string($conn, $sphone);
            $sgender_escaped = mysqli_real_escape_string($conn, $sgender);
            $saddress_escaped = mysqli_real_escape_string($conn, ''); // Default empty address
            
            $query = "INSERT INTO student 
                (dept_id, spassword, sname, semail, sdob, sphone, sgender, scgpa, saddress)
                VALUES 
                ('$dept_id', '$hashed', '$sname_escaped', '$semail_escaped', '$formattedDob', '$sphone_escaped', '$sgender_escaped', '$scgpa', '$saddress_escaped')";
            
            if (mysqli_query($conn, $query)) {
                $success_count++;
                
                // Send email (optional - can be skipped if causing issues)
                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host       = $email_config['smtp_host'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $email_config['smtp_username'];
                    $mail->Password   = $email_config['smtp_password'];
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = $email_config['smtp_port'];

                    $mail->setFrom($email_config['from_email'], $email_config['from_name']);
                    $mail->addAddress($semail, $sname);

                    $mail->isHTML(false);
                    $mail->Subject = 'Your Campus Portal Login Credentials';
                    $mail->Body    = "Hello $sname,\n\nYour student account has been created.\n\nEmail: $semail\nPassword: $spassword\n\nPlease log in and change your password.\n\nRegards,\nCampus Placement Team";

                    $mail->send();
                } catch (Exception $e) {
                    $errors[] = "Row $row_number ($sname): Email sent failed - {$mail->ErrorInfo}";
                }
            } else {
                $errors[] = "Row $row_number ($sname): Database insert error - " . mysqli_error($conn);
                $error_count++;
            }
        }
        fclose($file);
        
        // Set session messages
        if ($success_count > 0) {
            $_SESSION['message'] = "Upload completed! $success_count students added successfully.";
        }
        
        if ($error_count > 0) {
            $_SESSION['error'] = "Errors occurred: $error_count rows failed. " . implode('<br>', array_slice($errors, 0, 10));
            if (count($errors) > 10) {
                $_SESSION['error'] .= "<br>... and " . (count($errors) - 10) . " more errors.";
            }
        }
        
    } else {
        $_SESSION['error'] = "No file was uploaded.";
    }
    
    header("Location: uplodeSTD.php");
    exit();
}
?>