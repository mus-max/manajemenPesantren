$(document).ready(function() {
    // DataTables initialization
    if ($.fn.DataTable) {
        $('.data-table').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            pageLength: 10,
            order: [],
            responsive: true
        });
    }

    // Sidebar toggle for mobile
    $('.navbar-toggler').on('click', function() {
        $('#sidebar').toggleClass('show');
    });

    // Close sidebar when clicking outside on mobile
    $(document).on('click', function(e) {
        if ($(window).width() < 992) {
            if (!$(e.target).closest('.sidebar, .navbar-toggler').length) {
                $('#sidebar').removeClass('show');
            }
        }
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert-custom').fadeOut('slow');
    }, 5000);

    // Delete confirmation
    $(document).on('click', '.btn-delete', function(e) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            e.preventDefault();
        }
    });
});