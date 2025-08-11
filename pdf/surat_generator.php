<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../vendor/autoload.php'; // Assuming Dompdf is installed via Composer
require_once __DIR__ . '/../includes/functions.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function generateOfferLetter($registration_id) {
    global $pdo;

    // Fetch registration details
    $stmt = $pdo->prepare("SELECT * FROM registrations WHERE id = ?");
    $stmt->execute([$registration_id]);
    $registration = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$registration) {
        return false; // Registration not found
    }

<<<<<<< HEAD


=======
>>>>>>> b59aeccbb9c6b54627c5973b262aaef2a148b3bc
    // Setup Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

<<<<<<< HEAD
    // Generate a random user password
    $user_password = substr(md5(uniqid(rand(), true)), 0, 8);
    $owner_password = $user_password;

=======
>>>>>>> b59aeccbb9c6b54627c5973b262aaef2a148b3bc
    // Path absolute ke gambar 
    $logoPath = imageToBase64(__DIR__ . '/../includes/sabily.png'); 
    $signaturePath = imageToBase64(__DIR__ . '/../includes/sain.png'); 
    
    // HTML content 
    $html = '<!DOCTYPE html> 
    <html> 
    <head> 
        <meta charset="UTF-8"> 
        <title>Offer Letter</title> 
        <style> 
            body { font-family: Arial, sans-serif; margin: 40px; } 
            .header { text-align: center; margin-bottom: 30px; } 
            .content { margin-bottom: 30px; line-height: 1.6; } 
            .footer { text-align: center; margin-top: 50px; font-size: 0.9em; color: #555; } 
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; } 
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } 
            .signature { margin-top: 50px; } 
            .signature div { border-top: 1px solid #000; width: 200px; padding-top: 5px; } 
        </style> 
    </head> 
    <body> 
        <div class="header"> 
            <img src="' . $logoPath . '" alt="Sabily Logo" style="width: 35%; display: block; margin: 0 auto;"> 
            <p>Sabily Enterprise<br> 
            Lot G-06, Plaza Imbi<br> 
            Emel: admin@sabily.info<br> 
            Laman Web: <a href="https://sabily.info">https://sabily.info</a></p> 
        </div> 
    
        <div class="content"> 
            <p>Ruj. Kami: REF-' . sprintf('%06d', $registration_id) . '<br> 
            Tarikh: ' . date('d F Y') . '</p> 
    
            <p>' . htmlspecialchars($registration['name']) . '<br> 
            ' . htmlspecialchars($registration['address']) . '</p> 
    
            <p>Tuan/Puan,</p> 
            <p><strong>TAWARAN LATIHAN INDUSTRI DI SABILY ENTERPRISE</strong></p> 
    
            <p>Merujuk kepada permohonan Tuan/Puan bagi menjalani latihan industri di syarikat kami, adalah dimaklumkan bahawa pihak pengurusan telah bersetuju untuk menawarkan tempat kepada Tuan/Puan bagi menjalani latihan industri seperti berikut:</p> 
    
            <table> 
                <tr><td><strong>Tempoh Latihan</strong></td><td>: ' . htmlspecialchars(date('d F Y', strtotime($registration['internship_start']))) . ' hingga ' . htmlspecialchars(date('d F Y', strtotime($registration['internship_end']))) . ' (' . htmlspecialchars($registration['internship_duration']) . ' hari)</td></tr> 
                <tr><td><strong>Bahagian/Jabatan</strong></td><td>: Teknikal dan Jualan</td></tr> 
                <tr><td><strong>Waktu Bekerja</strong></td><td>: Isnin - Jumaat (10.00 pagi - 6.00 petang)<br/>: Sabtu (12.00 pagi - 6.00 petang)</td></tr> 
<<<<<<< HEAD
                <tr><td><strong>Elaun</strong></td><td>: RM 300.00/sebulan (' . htmlspecialchars($registration['bank_name']) . ' - ' . htmlspecialchars($registration['bank_account_number']) . ')</td></tr> 
=======
                <tr><td><strong>Elaun</strong></td><td>: RM 300.00/sebulan<br/>(Kreditkan ke : ' . htmlspecialchars($registration['bank_name']) . ' - ' . htmlspecialchars($registration['bank_account_number']) . ')</td></tr> 
>>>>>>> b59aeccbb9c6b54627c5973b262aaef2a148b3bc
            </table> 
    
            <p>Sehubungan dengan itu, Tuan/Puan dikehendaki mengesahkan penerimaan tawaran ini dalam tempoh 7 hari bekerja dari tarikh surat ini dengan melengkapkan Borang Latihan Industri pihak Tuan/Puan yang dilampirkan dan mengembalikannya melalui emel kepada: admin@sabily.info</p> 
            <p>Sebarang pertanyaan lanjut boleh dikemukakan kepada:<br> 
            Staff: Salaudin Ahmad<br> 
            Jawatan: Sales Manager<br> 
<<<<<<< HEAD
            Emel: salaudin@sabily.info<br> 
=======
            Emel: ahmad@sabily.info<br> 
>>>>>>> b59aeccbb9c6b54627c5973b262aaef2a148b3bc
            No. Telefon: 0167001035</p> 
    
            <p>Kami mengucapkan tahniah dan selamat menjalani latihan industri di Sabily Enterprise</p> 
            <p>Sekian, terima kasih.</p> 
            <p>Yang menjalankan tugas,</p> 
            <div class="signature"> 
                <img src="' . $signaturePath . '" alt="Digital Signature" style="width: 150px;"> 
                <p>Nur Asmawi bin Subri<br> 
                Pengurus<br> 
                Sabily Enterprise<br> 
                [Tandatangan, jika versi bercetak]</p> 
            </div> 
        </div> 
    
        <div class="footer"> 
            <p>This is an automatically generated letter. No signature is required.</p> 
<<<<<<< HEAD
        </div>
=======
            <p>Your PDF password is: <strong>' . $user_password . '</strong></p>
        </div> 
    </body> 
    </html>';

    $dompdf->loadHtml($html);

    // Setup Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    // Generate a random user password
    $user_password = substr(md5(uniqid(rand(), true)), 0, 8);
    $owner_password = 'owner_' . $registration_id;

    // Path absolute ke gambar 
    $logoPath = imageToBase64(__DIR__ . '/../includes/sabily.png'); 
    $signaturePath = imageToBase64(__DIR__ . '/../includes/sain.png'); 
    
    // HTML content 
    $html = '<!DOCTYPE html> 
    <html> 
    <head> 
        <meta charset="UTF-8"> 
        <title>Offer Letter</title> 
        <style> 
            body { font-family: Arial, sans-serif; margin: 40px; } 
            .header { text-align: center; margin-bottom: 30px; } 
            .content { margin-bottom: 30px; line-height: 1.6; } 
            .footer { text-align: center; margin-top: 50px; font-size: 0.9em; color: #555; } 
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; } 
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } 
            .signature { margin-top: 50px; } 
            .signature div { border-top: 1px solid #000; width: 200px; padding-top: 5px; } 
        </style> 
    </head> 
    <body> 
        <div class="header"> 
            <img src="' . $logoPath . '" alt="Sabily Logo" style="width: 35%; display: block; margin: 0 auto;"> 
            <p>Sabily Enterprise<br> 
            Lot G-06, Plaza Imbi<br> 
            Emel: admin@sabily.info<br> 
            Laman Web: <a href="https://sabily.info">https://sabily.info</a></p> 
        </div> 
    
        <div class="content"> 
            <p>Ruj. Kami: REF-' . sprintf('%06d', $registration_id) . '<br> 
            Tarikh: ' . date('d F Y') . '</p> 
    
            <p>' . htmlspecialchars($registration['name']) . '<br> 
            ' . htmlspecialchars($registration['address']) . '</p> 
    
            <p>Tuan/Puan,</p> 
            <p><strong>TAWARAN LATIHAN INDUSTRI DI SABILY ENTERPRISE</strong></p> 
    
            <p>Merujuk kepada permohonan Tuan/Puan bagi menjalani latihan industri di syarikat kami, adalah dimaklumkan bahawa pihak pengurusan telah bersetuju untuk menawarkan tempat kepada Tuan/Puan bagi menjalani latihan industri seperti berikut:</p> 
    
            <table> 
                <tr><td><strong>Tempoh Latihan</strong></td><td>: ' . htmlspecialchars(date('d F Y', strtotime($registration['internship_start']))) . ' hingga ' . htmlspecialchars(date('d F Y', strtotime($registration['internship_end']))) . ' (' . htmlspecialchars($registration['internship_duration']) . ' hari)</td></tr> 
                <tr><td><strong>Bahagian/Jabatan</strong></td><td>: Teknikal dan Jualan</td></tr> 
                <tr><td><strong>Waktu Bekerja</strong></td><td>: Isnin - Jumaat (10.00 pagi - 6.00 petang)<br/>: Sabtu (12.00 pagi - 6.00 petang)</td></tr> 
                <tr><td><strong>Elaun</strong></td><td>: RM 300.00/sebulan<br/>(Kreditkan ke : ' . htmlspecialchars($registration['bank_name']) . ' - ' . htmlspecialchars($registration['bank_account_number']) . ')</td></tr> 
            </table> 
    
            <p>Sehubungan dengan itu, Tuan/Puan dikehendaki mengesahkan penerimaan tawaran ini dalam tempoh 7 hari bekerja dari tarikh surat ini dengan melengkapkan Borang Latihan Industri pihak Tuan/Puan yang dilampirkan dan mengembalikannya melalui emel kepada: admin@sabily.info</p> 
            <p>Sebarang pertanyaan lanjut boleh dikemukakan kepada:<br> 
            Staff: Salaudin Ahmad<br> 
            Jawatan: Sales Manager<br> 
            Emel: ahmad@sabily.info<br> 
            No. Telefon: 0167001035</p> 
    
            <p>Kami mengucapkan tahniah dan selamat menjalani latihan industri di Sabily Enterprise</p> 
            <p>Sekian, terima kasih.</p> 
            <p>Yang menjalankan tugas,</p> 
            <div class="signature"> 
                <img src="' . $signaturePath . '" alt="Digital Signature" style="width: 150px;"> 
                <p>Nur Asmawi bin Subri<br> 
                Pengurus<br> 
                Sabily Enterprise<br> 
                [Tandatangan, jika versi bercetak]</p> 
            </div> 
        </div> 
    
        <div class="footer"> 
            <p>This is an automatically generated letter. No signature is required.</p> 
            <p>Your PDF password is: <strong>' . $user_password . '</strong></p>
>>>>>>> b59aeccbb9c6b54627c5973b262aaef2a148b3bc
        </div> 
    </body> 
    </html>';

    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

<<<<<<< HEAD


    // Set PDF password protection
     $canvas = $dompdf->getCanvas();
     $canvas->get_cpdf()->setEncryption($user_password, $owner_password, array('print', 'copy', 'modify', 'annot-forms'));

     // Save the PDF to a file
=======
    // Set PDF password protection
    $dompdf->getCanvas()->setEncryption($user_password, $owner_password, array('print', 'copy', 'modify'));

    // Save the PDF to a file
>>>>>>> b59aeccbb9c6b54627c5973b262aaef2a148b3bc
    $output_dir = __DIR__ . '/../uploads/offers/';
    if (!is_dir($output_dir)) {
        mkdir($output_dir, 0777, true);
    }
    $filename = 'offer_letter_' . $registration_id . '.pdf';
    $file_path = $output_dir . $filename;
    file_put_contents($file_path, $dompdf->output());

<<<<<<< HEAD
    return ['file_path' => $file_path, 'user_password' => $user_password];
=======
    return $file_path;
>>>>>>> b59aeccbb9c6b54627c5973b262aaef2a148b3bc
}

?>