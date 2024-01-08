@extends('layouts.panel')

@section('content')
<div class="container-fluid">
    <div class="d-flex mb-2 align-items-center">
        <h4 class="m-0 text-uppercase fw-bold">Ujian</h4>
        <div class="btn-toolbar ms-auto">
            <div class="btn-group btn-group-sm me-2">
                <a href="{{ route('panel.examinations.index') }}" class="btn btn-outline-secondary">Daftar Ujian</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @include('panel.examinations.parts.info')
        </div>
        <div class="card-header">
            @include('panel.examinations.parts.navs')
        </div>
        <div class="card-body">
            <div class="d-flex mb-2 align-items-center">
                <h6 class="m-0 text-uppercase fw-bold">Periode Pelaksanaan</h6>
            </div>
            <table id="dt" class="table table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Cabang</th>
                        <th>Periode Mulai</th>
                        <th>Periode Akhir</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div id="modalStorePriode" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form class="form-ajax" method="POST" action="{{ route('panel.examinations.period.store',['examination'=>$examination]) }}" enctype="multipart/form-data" autocomplete="off">
                @method('POST')
            <div class="modal-header p-2">
                <h5 class="modal-title"><strong>Edit Priode Cabang</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2">
                <input type="hidden" name="examinations_id">
                <input type="hidden" name="branch_id">
                <div class="form-group mb-3">
                    <label for="branch">Cabang</label>
                    <input id="branch" type="text" class="form-control active" readonly>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group mb-3">
                    <label for="start_at">Periode Mulai</label>
                    <input id="start_at" type="text" class="form-control flatpickr flatpickr-input" name="start_at" readonly="readonly">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group mb-3">
                    <label for="end_at">Periode Akhir</label>
                    <input id="end_at" type="text" class="form-control flatpickr flatpickr-input" name="end_at" readonly="readonly">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batalkan</button>
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables/DataTables-1.12.1/css/dataTables.bootstrap5.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('vendor/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/DataTables-1.12.1/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/DataTables-1.12.1/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
const dt = $('#dt').DataTable({
    lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, 'All']
    ],
    processing: true,
    serverSide: true,
    search: {
        return: true
    },
    ajax: {
        url : `{{ route('panel.examinations.period.index',['examination'=>$examination]) }}`,
        data : (params)=>{
            return $.extend({},params,{

            });
        }
    },
    order: [[0,'asc']],
    columns: [{
        data: 'branch_name',
        name: 'branches.name',
    },{
        data: 'start_at',
        name: 'branch_exam.start_at',
    },{
        data: 'end_at',
        name: 'branch_exam.end_at',
    },{
        data: 'id',
        className: 'text-center',
        orderable: false,
        searchable: false,
        width: '10px',
        render: (data, type, row, meta) => {
            return `
            <div class="dropdown dropstart">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">&#x22EE;</button>
                <ul class="dropdown-menu">
                    <li><a data-bs-toggle="modal"
                    data-bs-examinations_id="${row['examinations_id']}"
                    data-bs-branch_id="${row['branch_id']}"
                    data-bs-branch_name="${row['branch_name']}"
                    data-bs-start_at="${row['start_at'] ?? ''}"
                    data-bs-end_at="${row['end_at'] ?? ''}"
                    data-bs-target="#modalStorePriode"
                    class="dropdown-item"
                    href="#">Ubah Priode</a></li>
                </ul>
            </div>
            `;
        }
    }]
});

$('#modalStorePriode').on({
    'show.bs.modal': function(e) {
        console.log(e.relatedTarget.getAttribute("cabang"));
        $('#modalStorePriode #branch').val(e.relatedTarget.getAttribute("data-bs-branch_name"));
        $('#modalStorePriode input[name=start_at]').val(e.relatedTarget.getAttribute("data-bs-start_at"));
        $('#modalStorePriode input[name=end_at]').val(e.relatedTarget.getAttribute("data-bs-end_at"));
        $('#modalStorePriode input[name=examinations_id]').val(e.relatedTarget.getAttribute("data-bs-examinations_id"));
        $('#modalStorePriode input[name=branch_id]').val(e.relatedTarget.getAttribute("data-bs-branch_id"));
    },
    'hide.bs.modal': function() {
        $('#btn-dt-row-delete').attr('url-target', '');
    }
});

$(".flatpickr").flatpickr({
    dateFormat: "Y-m-d H:i",
    enableTime: true,
    time_24hr: true
});

$('#modalStorePriode .form-ajax').submit((e)=>{
    e.preventDefault();
    let form    = e.target,
        data    = new FormData(form),
        btn     = $(form).find('[type="submit"]'),
        btnHTML = btn.html();

    $.ajax({
        method  : $(form).attr('method'),
        url     : $(form).attr('action'),
        data    : data,
        contentType: false,
        processData: false,
        beforeSend:()=>{
            btn.prop('disabled',true).addClass('disabled').html('Loading ...');
        },
        error:(xhr)=>{
            btn.prop('disabled', false).removeClass('disabled').html(btnHTML);
            let errorFields = xhr.responseJSON.errors;
            let toast = new bootstrap.Toast(document.getElementById('toast-error'));
            toast.show();
            $.each(errorFields, (key, value)=>{
                $(`[name="${key}"]`).addClass('is-invalid');
                $(`[name="${key}"]`).closest('.form-group').find('.invalid-feedback').text(value[0]);
            });
        },
        success: (response)=>{
            btn.prop('disabled', false).removeClass('disabled').html(btnHTML);
            let toast = new bootstrap.Toast(document.getElementById('toast-success'));
            toast.show();
            setTimeout(()=>{
                window.location.href = `{{ route('panel.examinations.index') }}/${response.data.id}/period`;
            }, 1000);
        }
    });
});

</script>
@endpush
