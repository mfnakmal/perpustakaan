<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Collections</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1000px;
            margin: auto;
        }
        .card-stat {
            background-color: #c0392b;
            color: white;
            border-radius: 10px;
            text-align: center;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .card-stat h2 {
            font-size: 2rem;
            font-weight: bold;
        }
        .card-stat p {
            font-size: 1.2rem;
        }
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">DATA CATALOGS TIDAK MEMPUNYAI COLLECTIONS</h2>

    <table id="datatable" class="display table table-striped table-bordered">
        <thead>
            <tr>
                <th>CATALOG ID</th>
                <th>JUDUL</th>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(document).ready(function() {
        let table = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "data.php",
            "columns": [
                { "data": "CATALOG_ID", "orderable": true },
                { "data": "Judul", "orderable": true }
            ],
            "order": [[0, "asc"]],
            "autoWidth": false,
            "columnDefs": [
                { "width": "20%", "targets": 0 },
                { "width": "80%", "targets": 1 }
            ],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            },
            "responsive": true
        });

        // Simulasi fetch jumlah data
        $.ajax({
            url: "data.php",
            type: "GET",
            success: function(response) {
                let total = response.recordsTotal;
                $("#statLeft").text(total.toLocaleString());
                $("#statCenter").text((total / 2).toLocaleString());
                $("#statRight").text((total / 4).toLocaleString());
            }
        });
    });
</script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
