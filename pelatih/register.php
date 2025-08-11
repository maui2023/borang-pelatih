<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Check for token in the URL
if (!isset($_GET['token'])) {
    die('Token is required.');
}

$token = $_GET['token'];

// Validate the token
$stmt = $pdo->prepare("SELECT id, name, phone_number FROM tokens WHERE token = ? AND used = 0");
$stmt->execute([$token]);
$token_data = $stmt->fetch();

if (!$token_data) {
    die('Invalid or expired token.');
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract and sanitize form data
    $name = htmlspecialchars($_POST['name'] ?? '');
    $ic_number = htmlspecialchars($_POST['ic_number'] ?? '');
    $phone_number = htmlspecialchars($_POST['phone_number'] ?? '');
    $address = htmlspecialchars($_POST['address'] ?? '');
    $institution_name = htmlspecialchars($_POST['institution_name'] ?? '');
    $course_name = htmlspecialchars($_POST['course_name'] ?? '');
    $supervisor_name = htmlspecialchars($_POST['supervisor_name'] ?? '');
    $supervisor_phone = htmlspecialchars($_POST['supervisor_phone'] ?? '');
    $bank_account_number = htmlspecialchars($_POST['bank_account_number'] ?? '');
    $bank_name = htmlspecialchars($_POST['bank_name'] ?? '');
    $start_date = htmlspecialchars($_POST['start_date'] ?? '');
    $end_date = htmlspecialchars($_POST['end_date'] ?? '');

    // Calculate internship duration
    $date1 = new DateTime($start_date);
    $date2 = new DateTime($end_date);
    $interval = $date1->diff($date2);
    $internship_duration = $interval->days; // Duration in days

    // File upload handling
    $resume_path = '';
    $offer_letter_path = '';
    $upload_dir = __DIR__ . '/../uploads/';

    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $resume_path = $upload_dir . basename($_FILES['resume']['name']);
        move_uploaded_file($_FILES['resume']['tmp_name'], $resume_path);
    }

    if (isset($_FILES['offer_letter']) && $_FILES['offer_letter']['error'] == 0) {
        $offer_letter_path = $upload_dir . basename($_FILES['offer_letter']['name']);
        move_uploaded_file($_FILES['offer_letter']['tmp_name'], $offer_letter_path);
    }

    // Generate unique trainee ID and number (temporary placeholders)
    $trainee_id = uniqid('TRAINEE_');
    $trainee_number = 'TRN-' . date('YmdHis');

    // Insert data into the database
    $sql = "INSERT INTO registrations (token_id, name, ic_number, phone_number, trainee_id, trainee_number, address, institution_name, course_name, supervisor_name, supervisor_phone, bank_account_number, bank_name, internship_start, internship_end, internship_duration, offer_letter_path, resume_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$token_data['id'], $name, $ic_number, $phone_number, $trainee_id, $trainee_number, $address, $institution_name, $course_name, $supervisor_name, $supervisor_phone, $bank_account_number, $bank_name, $start_date, $end_date, $internship_duration, $offer_letter_path, $resume_path, 'Pending'])) {
        // Mark the token as used
        $update_stmt = $pdo->prepare("UPDATE tokens SET used = 1 WHERE id = ?");
        $update_stmt->execute([$token_data['id']]);
        header('Location: success.php');
        exit;
    } else {
        $error_message = 'Failed to submit registration. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainee Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Trainee Registration Form</h3>
            </div>
            <div class="card-body">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($token_data['name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ic_number" class="form-label">IC Number</label>
                            <input type="text" class="form-control" id="ic_number" name="ic_number" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($token_data['phone_number']); ?>" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="address" class="form-label">Home Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                    </div>
                    <hr>
                    <h5>Institution Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="institution_name" class="form-label">Institution Name</label>
                            <input type="text" class="form-control" id="institution_name" name="institution_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="course_name" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="course_name" name="course_name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="supervisor_name" class="form-label">Supervisor Name</label>
                            <input type="text" class="form-control" id="supervisor_name" name="supervisor_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="supervisor_phone" class="form-label">Supervisor Phone</label>
                            <input type="text" class="form-control" id="supervisor_phone" name="supervisor_phone" required>
                        </div>
                    </div>
                    <hr>
                    <h5>Bank Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bank_account_number" class="form-label">Account Bank Number</label>
                            <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bank_name" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" required>
                        </div>
                    </div>
                    <hr>
                    <h5>Internship Period</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>
                    <hr>
                    <h5>Document Upload</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="resume" class="form-label">Resume (PDF)</label>
                            <input type="file" class="form-control" id="resume" name="resume" accept=".pdf" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="offer_letter" class="form-label">Offer Letter from University (PDF)</label>
                            <input type="file" class="form-control" id="offer_letter" name="offer_letter" accept=".pdf" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Registration</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>