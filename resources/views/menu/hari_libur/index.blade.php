@extends('layout.main')
@section('content')
    <main class="page-content">
        <style>
            .has-event {
                cursor: pointer;
            }
        </style>

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"
            style="height: 37px; overflow: hidden; display: flex; align-items: center;">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route(session()->get('role') . '.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Mesin</span>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fadeIn animated bx bx-plus"></i>Tambah
                </a>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Edit --}}
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Hari Libur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editForm">
                        <div class="modal-body">
                            <input type="hidden" id="hari_libur_id">
                            <div class="mb-3">
                                <label for="edit_tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_keterangan" class="form-label">Keterangan</label>
                                <input type="text" class="form-control" id="edit_keterangan" name="keterangan" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-danger" id="deleteButton">Hapus</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Tambah --}}
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Tambah Hari Libur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="add_tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="add_tanggal" name="tanggal" required>
                            </div>
                            <div class="mb-3">
                                <label for="add_keterangan" class="form-label">Keterangan</label>
                                <input type="text" class="form-control" id="add_keterangan" name="keterangan" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('style')
@endsection

@section('script')
    <script src="<?= url('assets/onedash') ?>/plugins/fullcalendar/js/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script>
        const base_url = '{{ url('/') }}';
        moment.locale('id');
        document.addEventListener('DOMContentLoaded', function() {
            function formatDate(date) {
                var year = date.getFullYear();
                var month = ('0' + (date.getMonth() + 1)).slice(-2);
                var day = ('0' + date.getDate()).slice(-2);
                return year + '-' + month + '-' + day;
            }

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek'
                },
                buttonText: {
                    today: 'Hari Ini',
                    dayGridMonth: 'Bulan',
                    listWeek: 'List Per Minggu',
                    day: 'Hari',
                    prev: 'Sebelumnya',
                    next: 'Selanjutnya'
                },
                initialView: 'dayGridMonth',
                initialDate: new Date(),
                navLinks: true,
                selectable: true,
                nowIndicator: true,
                dayMaxEvents: true,
                displayEventTime: false,
                editable: false,
                selectable: true,
                businessHours: true,
                events: function(info, successCallback, failureCallback) {
                    $.ajax({
                        url: `${base_url}/get-hari-libur`,
                        method: 'GET',
                        success: function(hariLibur) {
                            var events = hariLibur.map(function(item) {
                                return {
                                    id: item.id,
                                    title: item.title,
                                    start: item.start,
                                    backgroundColor: 'red',
                                    extendedProps: {
                                        keterangan: item.title
                                    }
                                };
                            });

                            // Add Sunday events
                            var startDate = new Date(info.start);
                            var endDate = new Date(info.end);
                            var currentDate = new Date(startDate);

                            while (currentDate < endDate) {
                                if (currentDate.getDay() === 0) {
                                    events.push({
                                        id: 'sunday-' + formatDate(currentDate),
                                        title: 'Hari Minggu',
                                        start: formatDate(currentDate),
                                        backgroundColor: 'red',
                                        editable: false
                                    });
                                }
                                currentDate.setDate(currentDate.getDate() + 1);
                            }
                            successCallback(events);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching events:', error);
                            failureCallback(error);
                        }
                    });
                },
                eventClassNames: function(arg) {
                    return ['has-event'];
                },
                eventClick: function(info) {
                    var event = info.event;

                    // Check if Sunday
                    if (event.title === 'Hari Minggu' || event.id.startsWith('sunday-')) {
                        return;
                    }

                    // Set form values
                    $('#hari_libur_id').val(event.id);
                    $('#edit_tanggal').val(moment(event.start).format('YYYY-MM-DD'));
                    $('#edit_keterangan').val(event.extendedProps.keterangan || event.title);

                    $('#editModal').modal('show');
                },
                eventDidMount: function(info) {
                    var event = info.event;
                    var element = info.el;

                    tippy(element, {
                        content: event.title,
                        theme: 'light'
                    });
                }
                // eventMouseEnter: function(info) {
                //     var event = info.event;
                //     var tooltip = document.createElement('div');
                //     tooltip.className = 'event-tooltip';
                //     tooltip.innerHTML = event.title;
                //     tooltip.style.position = 'absolute';
                //     tooltip.style.zIndex = '999';
                //     tooltip.style.backgroundColor = 'white';
                //     tooltip.style.padding = '5px';
                //     tooltip.style.border = '1px solid #ccc';

                //     var eventEl = info.el;
                //     eventEl.appendChild(tooltip);
                // },
                // eventMouseLeave: function(info) {
                //     var tooltip = info.el.querySelector('.event-tooltip');
                //     if (tooltip) {
                //         tooltip.remove();
                //     }
                // }
            });

            // Render calendar
            calendar.render();

            // Handle add form submission
            $('#addForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: `${base_url}/store-hari-libur`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        tanggal: $('#add_tanggal').val(),
                        keterangan: $('#add_keterangan').val()
                    },
                    success: function(response) {

                        if (response.success) {
                            // Create new event object
                            var newEvent = {
                                id: response.id,
                                title: response.keterangan,
                                start: response.tanggal,
                                backgroundColor: 'red',
                                extendedProps: {
                                    keterangan: response.keterangan
                                }
                            };
                            calendar.addEvent(newEvent);

                            $('#addModal').modal('hide');
                            $('#addForm')[0].reset();

                            Lobibox.notify('success', {
                                title: 'Berhasil',
                                pauseDelayOnHover: true,
                                continueDelayOnInactiveTab: false,
                                position: 'top right',
                                icon: 'bx bx-check-circle',
                                msg: 'Data berhasil ditambahkan!'
                            });

                            // Refresh calendar to ensure sync
                            calendar.refetchEvents();
                        } else {
                            console.error('Failed to add event:', response);
                            alert('Gagal menambah data');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error adding event:', xhr.responseText);
                        alert('Terjadi kesalahan saat menambah data: ' + xhr.responseText);
                    }
                });
            });

            // Handle edit form submission
            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#hari_libur_id').val();

                if (!id) {
                    console.error('No ID found for edit');
                    alert('ID hari libur tidak ditemukan!');
                    return;
                }

                $.ajax({
                    url: `${base_url}/update-hari-libur/` + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        tanggal: $('#edit_tanggal').val(),
                        keterangan: $('#edit_keterangan').val()
                    },
                    success: function(response) {

                        $('#editModal').modal('hide');
                        calendar.refetchEvents();

                        Lobibox.notify('success', {
                            title: 'Berhasil',
                            pauseDelayOnHover: true,
                            continueDelayOnInactiveTab: false,
                            position: 'top right',
                            icon: 'bx bx-check-circle',
                            msg: 'Data berhasil diupdate!'
                        });
                    },
                    error: function(xhr) {
                        console.error('Error updating:', xhr.responseText);
                        alert('Terjadi kesalahan saat mengupdate data: ' + xhr.responseText);
                    }
                });
            });

            // Handle delete button click
            $('#deleteButton').on('click', function() {
                var id = $('#hari_libur_id').val();

                if (!id) {
                    console.error('No ID found for delete');
                    alert('ID hari libur tidak ditemukan!');
                    return;
                }

                if (confirm('Apakah Anda yakin ingin menghapus hari libur ini?')) {
                    $.ajax({
                        url: `${base_url}/delete-hari-libur/` + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'DELETE',
                        success: function(response) {
                            console.log('Delete response:', response); // Debug log

                            $('#editModal').modal('hide');
                            calendar.refetchEvents();

                            Lobibox.notify('success', {
                                title: 'Berhasil',
                                pauseDelayOnHover: true,
                                continueDelayOnInactiveTab: false,
                                position: 'top right',
                                icon: 'bx bx-check-circle',
                                msg: 'Data berhasil dihapus!'
                            });
                        },
                        error: function(xhr) {
                            console.error('Error deleting:', xhr.responseText);
                            alert('Terjadi kesalahan saat menghapus data: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endsection
