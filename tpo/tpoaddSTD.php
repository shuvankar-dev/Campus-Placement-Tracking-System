<?php
// Include database and email configuration
include('../config.php');
include('../email_config.php');

// Include PHPMailer
require_once '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

<!-- <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Select Department:</label>
            <select name="dept_id" class="form-select" required>
                <option value="">-- Choose Department --</option>
                
            </select>
        </div>
        <div class="mb-3">
            <label>Select CSV File:</label>
            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Upload</button>
    </form> -->



<?php
// if (isset($_POST['submit'])) {
    $dept_id = $_POST['dept_id'];

    if ($_FILES['csv_file']['name']) {
        $filename = $_FILES['csv_file']['tmp_name'];
        $file = fopen($filename, "r");
        fgetcsv($file); // skip header

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
            list($sname, $semail, $sdob, $sphone, $sgender, $scgpa, $saddress) = $data;

            // Generate a unique password for each student
            $spassword = rand(10000000, 99999999);
            $hashed = password_hash($spassword, PASSWORD_DEFAULT);

            // $dateObj = DateTime::createFromFormat('d-m-Y', $sdob);

            // echo $dateObj."<br>";
            // if($dateObj) {
            //     $formattedDob = $dateObj->format('Y-m-d');
            // } else {
            //     $formattedDob = '0000-00-00'; // fallback or log this error
            // }
            // echo $formattedDob."<br>";

            $sdob = trim($sdob); // remove spaces

            // Try multiple possible formats
            $formats = ['d-m-Y', 'Y-m-d', 'd/m/Y', 'm/d/Y'];
            $dateObj = false;

            foreach ($formats as $format) {
                $dateObj = DateTime::createFromFormat($format, $sdob);
                if ($dateObj !== false) break;
            }

            if ($dateObj) {
                $formattedDob = $dateObj->format('Y-m-d');
            } else {
                $formattedDob = '0000-00-00'; // fallback
                echo "Invalid DOB format for $sname ($sdob)<br>";
            }

            echo $formattedDob."<br>";

            $query = "INSERT INTO student 
                (dept_id, spassword, sname, semail, sdob, sphone, sgender, scgpa, saddress)
                VALUES 
                ('$dept_id', '$hashed', '$sname', '$semail', '$formattedDob', '$sphone', '$sgender', '$scgpa', '$saddress')";
                // echo $query;

            if (mysqli_query($conn, $query)) {
                // Send email
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
                    echo "Mailer Error for $semail: {$mail->ErrorInfo}<br>";
                }
            } else {
                echo "Database insert error for $semail: " . mysqli_error($connection) . "<br>";
            }
        }
        fclose($file);
        echo "Upload complete";
    }
// }
?>