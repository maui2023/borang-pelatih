<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$generated_token = null;
$whatsapp_link = null;

// Handle token generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_token'])) {
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    
    // Generate a unique token
    $token = bin2hex(random_bytes(16));

    $stmt = $pdo->prepare("INSERT INTO tokens (name, phone_number, token) VALUES (?, ?, ?)");
    if ($stmt->execute([$name, $phone_number, $token])) {
        $generated_token = $token;
        $registration_link = "https://{$_SERVER['HTTP_HOST']}" . dirname($_SERVER['PHP_SELF'], 2) . "pelatih/register.php?token=$token";
        $whatsapp_number = '6' . preg_replace('/[^0-9]/', '', $phone_number);
        $whatsapp_link = "https://wa.me/{$whatsapp_number}?text=" . urlencode("Assalamualaikum {$name}, sila klik pautan ini untuk melengkapkan pendaftaran latihan industri anda di Sabily Enterprise: {$registration_link}");
        $success_message = "Token generated successfully.";
    } else {
        $error_message = "Failed to generate token.";
    }
}

// Fetch registrations for display
$pending_stmt = $pdo->query("SELECT * FROM registrations WHERE status = 'Pending' ORDER BY submitted_at DESC");
$pending_registrations = $pending_stmt->fetchAll();

$approved_stmt = $pdo->query("SELECT * FROM registrations WHERE status = 'Approved' ORDER BY submitted_at DESC");
$approved_registrations = $approved_stmt->fetchAll();

// Fetch generated tokens that have not been used for registration
$unregistered_tokens_stmt = $pdo->query("
    SELECT t.name, t.phone_number, t.token
    FROM tokens t
    LEFT JOIN registrations r ON t.id = r.token_id
    WHERE r.id IS NULL
    ORDER BY t.created_at DESC
");
$unregistered_tokens = $unregistered_tokens_stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
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
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Generate Token</h5>
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <?php if ($generated_token): ?>
                            <div class="mb-3">
                                <p><strong>Token:</strong> <?php echo $generated_token; ?></p>
                                <a href="<?php echo $whatsapp_link; ?>" target="_blank" class="btn btn-success">Send via WhatsApp</a>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <form action="dashboard.php" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Trainee Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                            </div>
                            <button type="submit" name="generate_token" class="btn btn-primary">Generate</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Generated Tokens (Not Yet Registered)</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($unregistered_tokens as $token_data): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($token_data['name']); ?></td>
                                    <td><?php echo htmlspecialchars($token_data['phone_number']); ?></td>
                                    <td>
                                        <?php
                                        $registration_link = "https://{$_SERVER['HTTP_HOST']}" . dirname($_SERVER['PHP_SELF'], 2) . "pelatih/register.php?token={$token_data['token']}";
                                        $whatsapp_number = '6' . preg_replace('/[^0-9]/', '', $token_data['phone_number']);
                                        $whatsapp_link = "https://wa.me/{$whatsapp_number}?text=" . urlencode("Assalamualaikum {$token_data['name']}, sila klik pautan ini untuk melengkapkan pendaftaran latihan industri anda di Sabily Enterprise: {$registration_link}");
                                        ?>
                                        <a href="<?php echo $whatsapp_link; ?>" target="_blank" class="btn btn-sm btn-success">Send (whatsapp)</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Pending Applications</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Institution</th>
                                    <th>Submitted At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pending_registrations as $reg): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reg['name']); ?></td>
                                    <td><?php echo htmlspecialchars($reg['institution_name']); ?></td>
                                    <td><?php echo $reg['submitted_at']; ?></td>
                                    <td><a href="approve.php?id=<?php echo $reg['id']; ?>" class="btn btn-sm btn-success">View/Approve</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Approved Applications</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Institution</th>
                                    <th>Submitted At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($approved_registrations as $reg): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reg['name']); ?></td>
                                    <td><?php echo htmlspecialchars($reg['institution_name']); ?></td>
                                    <td><?php echo $reg['submitted_at']; ?></td>
                                    <td>
                                        <?php
                                        $whatsapp_number = '6' . preg_replace('/[^0-9]/', '', $reg['phone_number']);
                                        $offer_letter_url = "https://{$_SERVER['HTTP_HOST']}/uploads/offers/" . basename($reg['offer_letter_path']);
                                        $whatsapp_message = urlencode("Assalamualaikum {$reg['name']}, your offer letter is ready. You can download it here: {$offer_letter_url}");
                                        $whatsapp_link = "https://wa.me/{$whatsapp_number}?text={$whatsapp_message}";
                                        ?>
                                        <a href='<?php echo $whatsapp_link; ?>' target='_blank' class='btn btn-sm btn-info'>Send Offer Letter via WhatsApp</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>