<div class="row">
    <div class="col-lg-2">
        <img src="{{ asset('storage/' . $examination->poster) }}" alt="" class="img-fluid">
    </div>
    <div class="col-lg-5">
        <h4>{{ $examination->name }}</h4>
        <hr class="my-2">
        {!! $examination->description !!}
    </div>
    <div class="col-md-5">
        <dl class="row">
            <dt class="col-md-4">Harga Tiket</dt>
            <dd class="col-md-8">Rp. {{ number_format($examination->price,0) }}</dd>
            <dt class="col-md-4">Status</dt>
            <dd class="col-md-8">
            @php
            $status = '';
            switch($examination->status):
                case 'Plan' :
                    $status = 'Rencana';
                    break;
                case 'On Going' :
                    $status = 'Dibuka';
                    break;
                case 'Done' :
                default :
                    $status = 'Ditutup';
                    break;
            endswitch;
            @endphp
            {{ $status }}
            </dd>
            <dt class="col-md-4">Kategori</dt>
            <dd class="col-md-8">{{ $examination?->category?->name  }}</dd>
          <dt class="col-md-4">Durasi Ujian</dt>
          <dd class="col-md-8">{{ isset($examination->sections_sum_duration) ? $examination->sections_sum_duration : '-'  }} Menit</dd>
        </dl>
        <div class="btn-toolbar ms-auto">
            <div class="btn-group btn-group-sm me-2">
                <button id="btn-delete" type="button" class="btn btn-outline-danger">Hapus Ujian</button>
            </div>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('panel.examinations.edit',['examination'=>$examination]) }}" class="btn btn-outline-secondary">Edit Ujian</a>
            </div>
        </div>
    </div>
</div>
@push('script')
<script>
$(()=>{
    $('#btn-delete').click((e)=>{
        let url     = `{{ route('panel.examinations.destroy', ['examination'=>$examination]) }}`;
        console.log(url);

      Swal.fire({
        title   : "Yakin ingin melanjutkan ?",
        text    : "Data yang telah terhapus tidak dapat dikembalikan.",
        icon    : "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Tidak, Batalkan",
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: url,
            type: "DELETE",
            data: {
              _token  : '{{ csrf_token() }}',
              _method : 'DELETE'
            },
            error   : function(response){
              toastr.error(response, 'Failed !');
            },
            success : function(response) {
              if (response.status === "success") {
                toastr.success(response.message, 'Success !');
                api.draw();
              } else {
                toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
              }
            }
          });
        }
      });

    });
});
</script>
@endpush
