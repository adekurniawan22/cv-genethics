$(function () {
    "use strict";

    $(document).ready(function () {
        $("#example").DataTable({
            oLanguage: {
                sLengthMenu: "Tampilkan _MENU_ data",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                sEmptyTable: "Tidak ada data",
                sZeroRecords: "Tidak ada data yang sesuai dengan pencarian",
            },
            lengthMenu: [
                [10, 25, 50, -1],
                ["10", "25", "50", "Semua"],
            ],
            language: {
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "<i class='fa fa-angle-right'></i>",
                    previous: "<i class='fa fa-angle-left'></i>",
                },
            },
        });

        $("#penjadwalan").DataTable({
            oLanguage: {
                sLengthMenu: "Tampilkan _MENU_ data",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                sEmptyTable: "Tidak ada data",
                sZeroRecords: "Tidak ada data yang sesuai dengan pencarian",
            },
            lengthMenu: [
                [10, 25, 50, -1],
                ["10", "25", "50", "Semua"],
            ],
            language: {
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "<i class='fa fa-angle-right'></i>",
                    previous: "<i class='fa fa-angle-left'></i>",
                },
            },
        });

        $("#id-sembunyi-table").DataTable({
            oLanguage: {
                sLengthMenu: "Tampilkan _MENU_ data",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                sEmptyTable: "Tidak ada data",
                sZeroRecords: "Tidak ada data yang sesuai dengan pencarian",
            },
            lengthMenu: [
                [10, 25, 50, -1],
                ["10", "25", "50", "Semua"],
            ],
            language: {
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "<i class='fa fa-angle-right'></i>",
                    previous: "<i class='fa fa-angle-left'></i>",
                },
            },
            order: [[0, "desc"]], // Mengurutkan kolom pertama secara descending
            columnDefs: [
                {
                    targets: 0, // Indeks kolom pertama
                    visible: false, // Menyembunyikan kolom pertama
                },
            ],
        });

        $("#filter-all").click(function () {
            var table = $("#id-sembunyi-table").DataTable();
            table.search("").columns().search("").draw();
        });

        $("#filter-proses").click(function () {
            var table = $("#id-sembunyi-table").DataTable();
            table.search("proses").draw();
        });

        $("#filter-selesai").click(function () {
            var table = $("#id-sembunyi-table").DataTable();
            table.search("selesai").draw();
        });
    });

    $(document).ready(function () {
        var table = $("#example2").DataTable({
            lengthChange: false,
            buttons: ["copy", "excel", "pdf", "print"],
        });

        table.buttons().container().appendTo("#example2_wrapper .col-md-6:eq(0)");
    });
});
