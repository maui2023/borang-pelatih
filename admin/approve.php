<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$registration_id = $_GET['id'] ?? null;

if (!$registration_id) {
    die('Registration ID is required.');
}

// Fetch registration details
$stmt = $pdo->prepare("SELECT * FROM registrations WHERE id = ?");
$stmt->execute([$registration_id]);
$registration = $stmt->fetch();

if (!$registration) {
    die('Registration not found.');
}

$message = '';

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $update_stmt = $pdo->prepare("UPDATE registrations SET status = 'Approved', offer_letter_path = ? WHERE id = ?");
        require_once __DIR__ . '/../pdf/surat_generator.php';
        $pdf_path = generateOfferLetter($registration_id);

        if ($pdf_path && $update_stmt->execute([$pdf_path, $registration_id])) {
            $message = "Application approved successfully. Offer letter generated.";

            // Send PDF via WhatsApp
            $whatsapp_number = '6' . preg_replace('/[^0-9]/', '', $registration['phone_number']);
            $offer_letter_url = "http://{$_SERVER['HTTP_HOST']}/uploads/offers/" . basename($pdf_path);
            $whatsapp_message = urlencode("Assalamualaikum {$registration['name']}, your offer letter is ready. You can download it here: {$offer_letter_url}");
            $whatsapp_link = "https://wa.me/{$whatsapp_number}?text={$whatsapp_message}";
            $message .= " <a href='{$whatsapp_link}' target='_blank' class='btn btn-sm btn-info'>Send Offer Letter via WhatsApp</a>";
        } else if (!$pdf_path) {
            $message = "Application approved, but failed to generate offer letter.";
        } else {
            $message = "Failed to approve application.";
        }
    } elseif (isset($_POST['reject'])) {
        $update_stmt = $pdo->prepare("UPDATE registrations SET status = 'Rejected' WHERE id = ?");
        if ($update_stmt->execute([$registration_id])) {
            $message = "Application rejected successfully.";
        } else {
            $message = "Failed to reject application.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Application Details for <?php echo htmlspecialchars($registration['name']); ?></h3>
            </div>
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php endif; ?>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($registration['name']); ?></p>
                <p><strong>IC Number:</strong> <?php echo htmlspecialchars($registration['ic_number']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($registration['phone_number']); ?></p>
                <p><strong>Home Address:</strong> <?php echo htmlspecialchars($registration['address']); ?></p>
                <p><strong>Institution Name:</strong> <?php echo htmlspecialchars($registration['institution_name']); ?></p>
                <p><strong>Course Name:</strong> <?php echo htmlspecialchars($registration['course_name']); ?></p>
                <p><strong>Bank Account Number:</strong> <?php echo htmlspecialchars($registration['bank_account_number']); ?></p>
                <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($registration['bank_name']); ?></p>
                <p><strong>Start Date:</strong> <?php echo htmlspecialchars($registration['internship_start']); ?></p>
                <p><strong>End Date:</strong> <?php echo htmlspecialchars($registration['internship_end']); ?></p>
                <p><strong>Internship Duration:</strong> <?php echo htmlspecialchars($registration['internship_duration']); ?> months</p>
                <p><strong>Resume:</strong> <a href="../uploads/<?php echo htmlspecialchars(basename($registration['resume_path'])); ?>" target="_blank">View Resume</a></p>
                <p><strong>Offer Letter:</strong> <a href="../uploads/<?php echo htmlspecialchars(basename($registration['offer_letter_path'])); ?>" target="_blank">View Offer Letter</a></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($registration['status']); ?></p>

                <?php if ($registration['status'] === 'Pending'): ?>
                    <form action="approve.php?id=<?php echo $registration['id']; ?>" method="POST">
                        <button type="submit" name="approve" class="btn btn-success">Approve</button>
                        <button type="submit" name="reject" class="btn btn-danger">Reject</button>
                    </form>
                <?php endif; ?>
                <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>