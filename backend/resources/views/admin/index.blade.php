<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GUYUB</title>

    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper">

    {{-- Navbar --}}
    @include('admin.partials.navbar')

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')



    <!-- Content Wrapper -->
    <div class="content-wrapper">

        <!-- Header -->
        <section class="content-header">

            <div class="container-fluid">

                <div class="row mb-2">

                    <div class="col-sm-6">
                        <h1>
                            {{ ucwords(str_replace('-', ' ', $page)) }}
                        </h1>
                    </div>

                </div>

            </div>

        </section>



        <!-- Main Content -->
        <section class="content">

            <div class="container-fluid">

                {{-- LOAD HALAMAN DINAMIS --}}
                @includeIf('admin.partials.' . $page)

            </div>

        </section>

    </div>



    {{-- Footer --}}
    @include('admin.partials.footer')

</div>



<!-- Scripts -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

</body>
</html>
