<!DOCTYPE html>
<html lang="id">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GUYUB - Gerbang Urusan dan Layanan Warga Bersama</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-light">
    @include('layouts.navigation')

    <div class="container-fluid mt-4">
        <div id="main-content">
            {{ $slot ?? '' }}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function loadContent(page) {
        // Gunakan URL relatif yang stabil
        let url = "/admin/load-page/" + page;

        $.ajax({
            url: url,
            method: 'GET',
            beforeSend: function() {
                $('#main-content').html('<div class="text-center p-5">Memuat...</div>');
            },
            success: function(res) {
                $('#main-content').html(res);
            },
            error: function(xhr) {
                console.error("URL gagal dipanggil: " + url);
                $('#main-content').html('<div class="alert alert-danger">Gagal memuat halaman!</div>');
            }
        });
    }
    </script>
</body>
</html>
