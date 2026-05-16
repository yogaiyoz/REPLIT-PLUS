<?php
// uploader.php

$uploadDir = "uploads/";

// Buat folder uploads otomatis
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$uploadedLink = "";
$status = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['file'])) {

        $fileName = $_FILES['file']['name'];
        $tmpName  = $_FILES['file']['tmp_name'];

        // Ambil ekstensi file
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // File yang diizinkan
        $allowed = ['html', 'php'];

        if (in_array($ext, $allowed)) {

            // Nama random biar aman
            $newName = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '', $fileName);

            $savePath = $uploadDir . $newName;

            if (move_uploaded_file($tmpName, $savePath)) {

                // Auto generate link akses
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
                    ? "https://"
                    : "http://";

                $host = $_SERVER['HTTP_HOST'];

                $folder = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

                $uploadedLink = $protocol . $host . $folder . "/" . $savePath;

                $status = "success";

            } else {
                $status = "Upload gagal!";
            }

        } else {
            $status = "Hanya file HTML & PHP yang diperbolehkan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>HTML PHP Uploader</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#0f0f0f;
    color:white;
    font-family:Arial,sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:20px;
}

.box{
    width:100%;
    max-width:420px;
    background:#1a1a1a;
    padding:25px;
    border-radius:18px;
    box-shadow:0 0 20px rgba(0,0,0,.5);
}

h1{
    text-align:center;
    margin-bottom:20px;
    font-size:24px;
}

input[type=file]{
    width:100%;
    padding:12px;
    border:none;
    border-radius:12px;
    background:#2a2a2a;
    color:white;
}

button{
    width:100%;
    padding:13px;
    margin-top:15px;
    border:none;
    border-radius:12px;
    background:#00b894;
    color:white;
    font-size:16px;
    cursor:pointer;
    transition:.2s;
}

button:hover{
    transform:scale(1.02);
}

.result{
    margin-top:20px;
    background:#222;
    padding:15px;
    border-radius:12px;
    word-break:break-all;
}

.result a{
    color:#00cec9;
    text-decoration:none;
}

.success{
    color:#00ff9d;
    margin-bottom:10px;
}
</style>
</head>
<body>

<div class="box">

    <h1>📤 File Uploader</h1>

    <form method="POST" enctype="multipart/form-data">

        <input type="file" name="file" required>

        <button type="submit">
            Upload File
        </button>

    </form>

    <?php if($uploadedLink): ?>

        <div class="result">

            <div class="success">
                ✅ Upload berhasil!
            </div>

            <b>🔗 Link Akses:</b><br><br>

            <a href="<?= $uploadedLink ?>" target="_blank">
                <?= $uploadedLink ?>
            </a>

        </div>

    <?php elseif($status): ?>

        <div class="result">
            <?= $status ?>
        </div>

    <?php endif; ?>

</div>

</body>
</html>