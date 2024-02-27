<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="//cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        //SweetAlert2 Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });

        new DataTable('#myTable', {
            processing: true,
            serverside: true,
            ajax: "{{ url('pegawaiajax') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            }, {
                data: 'nama',
                name: 'Nama'
            }, {
                data: 'email',
                name: 'Email'
            }, {
                data: 'action',
                name: 'Action'
            }, ]
        });
        //GLOBAL AJAX SETUP
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        // Proses simpan
        $('body').on('click', '.tombol-tambah', function(e) {
            e.preventDefault();
            $('#modalTambah').modal('show')

            $(".tombol-simpan").off("click");
            $('.tombol-simpan').click(function(e) {
                simpan()
            })
        })

        // Proses edit
        $('body').on('click', '.tombol-edit', function(e) {
            e.preventDefault();
            let id = $(this).data('id')
            $.ajax({
                url: `pegawaiajax/${id}/edit`,
                type: 'GET',
                success: function(response) {
                    if (response.result) {
                        $('#nama').val(response.result.nama)
                        $('#email').val(response.result.email)
                        $('#modalTambah').modal('show')
                        $(".tombol-simpan").off("click");
                        $('.tombol-simpan').click(function(e) {
                            simpan(id)
                        })
                    }
                }
            })
        })

        $('body').on('click', '.tombol-delete', function(e) {
            e.preventDefault();
            if (confirm('yakin ingin menghapus data ini?')) {
                let id = $(this).data('id')
                $.ajax({
                    url: `pegawaiajax/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        Toast.fire({
                            type: 'success',
                            icon: "success",
                            title: response.success
                        });
                        $('#myTable').DataTable().ajax.reload()
                    }
                })
            }
        })

        // Fungsi simpan dan update
        function simpan(id = '') {
            let url = ''
            let type = ''
            if (id == '') {
                url = 'pegawaiajax'
                type = 'POST'
            } else {
                url = `pegawaiajax/${id}`
                type = 'PUT'
            }
            $.ajax({
                url: url,
                type: type,
                data: {
                    nama: $('#nama').val(),
                    email: $('#email').val()
                },
                success: function(response) {
                    $('.spinner-border').addClass('d-none')
                    $('.tombol-simpan').attr('disabled', false)
                    if (response.errors) {
                        response.errors.nama ? $('#error-message-nama').text(response.errors
                            .nama) : $('#error-message-nama').text('')
                        response.errors.email ? $('#error-message-email').text(response
                            .errors.email) : $('#error-message-email').text('')
                    } else {
                        $('#modalTambah').modal('hide')
                        $('#nama').val('')
                        $('#email').val('')
                        Toast.fire({
                            type: 'success',
                            icon: "success",
                            title: response.success
                        });

                        $('#myTable').DataTable().ajax.reload()
                    }
                },
                beforeSend() {
                    $('.spinner-border').removeClass('d-none')
                    $('.tombol-simpan').attr('disabled', true)
                },
            })
        }
    });
</script>
