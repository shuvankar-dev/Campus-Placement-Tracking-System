<form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Select Department:</label>
            <select name="dept_id" class="form-select" required>
                <option value="">-- Choose Department --</option>
                <?php
                $dept_rs = mysqli_query($connection, "SELECT * FROM department ORDER BY dept_name");
                while ($dept = mysqli_fetch_assoc($dept_rs)) {
                    echo "<option value='{$dept['dept_id']}'>{$dept['dept_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Select CSV File:</label>
            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Upload</button>
    </form>



<?php
if (isset($_POST['submit'])) {
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

            $dateObj = DateTime::createFromFormat('d-m-Y', $sdob);
            
            if ($dateObj) {
                $formattedDob = $dateObj->format('Y-m-d');
            } else {
                $formattedDob = '0000-00-00'; // fallback or log this error
            }


            $query = "INSERT INTO student 
                (dept_id, spassword, sname, semail, sdob, sphone, sgender, scgpa, saddress)
                VALUES 
                ('$dept_id', '$hashed', '$sname', '$semail', '$formattedDob', '$sphone', '$sgender', '$scgpa', '$saddress')";

            if (mysqli_query($connection, $query)) {
                // Send email
                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = '';  // Your Gmail
                    $mail->Password   = '';     // Gmail app password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('', 'Campus Admin');
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
}
?>