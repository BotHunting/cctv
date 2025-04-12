<?php

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data
    $name = $_POST['nama'];
    $email = $_POST['email'];
    $subject = $_POST['subjek'];
    $message = $_POST['pesan'];

    // Validate data (optional)
    // You can add checks for empty fields, email format, etc.

    // Prepare email content (optional)
    $body = "Nama: $name\n";
    $body .= "Email: $email\n";
    $body .= "Subjek: $subject\n\n";
    $body .= "Pesan:\n$message";

    // Send email notification (optional)
    // You need to configure your email server settings for this to work.
    $to = "dishubfakfak.lalulintas@gmail.com"; // Replace with your email address
    $from = "[Sender Email Address]"; // Replace with a valid email address
    $subject = "Pesan dari Kontak ATCS";
    $headers = "From: $from \r\n";
    $headers .= "Reply-To: $email \r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8";

    if (mail($to, $subject, $body, $headers)) {
        $message = "Terima kasih atas pesan Anda. Kami akan segera menghubungi Anda.";
    } else {
        $message = "Maaf, terjadi kesalahan saat mengirim pesan. Silahkan coba lagi nanti.";
    }

    // Redirect back to contact page with a message
    header("Location: kontak.php?message=" . urlencode($message));
    exit();
} else {
    // Access denied - should not be here if form is submitted properly
    header("Location: kontak.php?message=Akses Ditolak");
    exit();
}
