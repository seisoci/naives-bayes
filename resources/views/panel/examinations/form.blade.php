@extends('layouts.master')

@section('content')
  <div>
    <form id="formStore" action="{{ $config['form']->action }}" method="POST">
      @method($config['form']->method)
      @csrf
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header justify-content-between">
              <div class="header-title">
                <div class="row">
                  <div class="col-sm-6 col-lg-6">
                    <h4 class="card-title">{{ $config['title'] }}</h4>
                  </div>
                  <div class="col-sm-6 col-lg-6">
                    <div class="btn-group float-end" role="group" aria-label="Basic outlined example">
                      <a onclick="history.back()" class="btn btn-sm btn-outline-primary"><i
                          class="fa-solid fa-rotate-left"></i> Kembali</a>
                      <button type="submit" class="btn btn-sm btn-primary">Simpan <i
                          class="fa-solid fa-floppy-disk"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <div id="errorCreate" class="mb-3" style="display:none;">
                  <div class="alert alert-danger" role="alert">
                    <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
                    <div class="alert-text">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-8">
                    <div class="form-group mb-3">
                      <label for="name" class="mb-1">Nama Ujian</label>
                      <input id="name" type="text" class="form-control" name="name"
                             value="{{ $examination['name'] ?? '' }}">
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="price" class="mb-1">Kategori</label>
                          <select id="select2Category" name="category_id">
                            @if(isset($examination->category_id))
                              <option value="{{ $examination->category_id }}">{{ $examination->category->name }}</option>
                            @endif
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="price" class="mb-1">Harga</label>
                          <input id="price" type="text" class="form-control text-end" name="price"
                                 value="{{ $examination['price'] ?? 0 }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="status" class="mb-1">Status</label>
                          <select id="select2Status" class="form-select" name="status">
                            <option
                              value="Plan" @selected(isset($examination->status) && $examination->status == 'Plan')>
                              Rencana
                            </option>
                            <option
                              value="On Going" @selected(isset($examination->status) && $examination->status == 'On Going')>
                              Dibuka
                            </option>
                            <option
                              value="Done" @selected(isset($examination->status) && $examination->status == 'Done')>
                              Ditutup
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group mb-3">
                      <label for="description" class="mb-1">Deskripsi</label>
                      <textarea id="description" class="form-control summernote"
                                name="description">{{ $examination['description'] ?? '' }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                      <label for="instruction" class="mb-1">Petunjuk</label>
                      <textarea id="instruction" class="form-control summernote"
                                name="instruction">{{ $examination['instruction'] ?? '' }}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group mb-3">
                      <label for="poster" class="mb-1">Poster</label>
                      <div class="w-100">
                        <img id="poster-cropper" class="img-fluid" src="{{ isset($examination->poster) ? asset('storage/' . $examination->poster) : asset('assets/images/no-content.svg') }}"
                             onerror="javascript:this.src = '{{ asset('assets/images/no-content.svg') }}';">
                      </div>
                      <input id="poster-cropped-result" class="d-none" type="file" name="poster">
                      <input id="poster" type="file" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@push('style')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropper/4.1.0/cropper.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css"/>
@endpush

@push('script')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.9.0/autoNumeric.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#select2Status').select2({
        width: '100%'
      });

      $('#select2Category').select2({
        dropdownParent: $('#select2Category').parent(),
        placeholder: "Cari Kategori",
        allowClear: true,
        ajax: {
          url: "{{ route('panel.categories.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      });

      let price = new AutoNumeric('#price', {
        currencySymbol: `Rp. `,
        decimalPlaces: 0,
        decimalCharacter: ',',
        digitGroupSeparator: '.',
        unformatOnSubmit: true
      });

      let image = document.querySelector('#poster-cropper'),
        cropper = new Cropper(image, {
          viewMode: 3,
          movable: false,
          zoomable: false,
          rotatable: false,
          scalable: false,
          aspectRatio: 4 / 3,
          cropBoxResizable: false,
          autoCropArea: 0,
          autoCropArea: 1,
        });

      image.addEventListener('ready', function () {
        if (this.cropper === cropper) {
          cropper.getCroppedCanvas({
            width: 1024,
            height: 768,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
          }).toBlob((blob) => {
            let file = new File([blob], 'poster.png');
            let container = new DataTransfer();
            container.items.add(file);
            $('#poster-cropped-result').get(0).files = container.files;
          });
        }
      });

      $('#poster').change(function (e) {
        e.preventDefault();
        let files = e.target.files;
        if (files && files.length > 0) {
          cropper.replace(URL.createObjectURL(files[0]));
        }
      });

      $('.summernote').summernote({
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['fontname', ['fontname']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']],
        ],
      });

      $("#formStore").submit((e) => {
        e.preventDefault();
        const form = $(e.target);
        const btnSubmit = form.find("[type='submit']");
        const btnSubmitHtml = btnSubmit.html();
        const url = form.attr("action");
        const data = new FormData(e.target);

        $.ajax({
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url: url,
          data: data,
          beforeSend: () => {
            btnSubmit.addClass("disabled").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...').prop("disabled", "disabled");
          },
          success: (response) => {
            const errorCreate = $('#errorCreate');
            errorCreate.css('display', 'none');
            errorCreate.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");

            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              setTimeout(function () {
                if (response.redirect === "" || response.redirect === "reload") {
                  location.reload();
                } else {
                  location.href = response.redirect;
                }
              }, 1000);
            } else {
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
              if (response.error !== undefined) {
                errorCreate.removeAttr('style');
                $.each(response.error, (key, value) => {
                  errorCreate.find('.alert-text').append(`<span style="display: block">${value}</span>`);
                });
              }
            }
          },
          error: (response) => {
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            toastr.error(response.responseJSON.message, 'Failed !');
          }
        });
      });


    });
  </script>
@endpush
