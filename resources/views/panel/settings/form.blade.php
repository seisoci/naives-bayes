@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-5">
            <form id="formUpdate" method="POST" action="{{ route('panel.settings.general.update') }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="d-flex mb-2 align-items-center">
                    <h4 class="m-0 text-uppercase fw-bold">Umum</h4>
                    <div class="btn-toolbar ms-auto">
                        <div class="btn-group btn-group-sm">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                      <div id="errorEdit" style="display:none;">
                        <div class="alert alert-danger" role="alert">
                          <div class="alert-text">
                          </div>
                        </div>
                      </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="favicon">Favicon</label>
                                    <div class="d-flex justify-content-center">
                                        <img id="favicon-cropper" style="height:150px;" src="{{ asset('storage/base/favicon.png') }}">
                                    </div>
                                    <input id="favicon-cropped-result" class="d-none" type="file" name="favicon">
                                    <input id="favicon" type="file" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="logo">Logo Terang</label>
                                    <div class="d-flex justify-content-center">
                                        <img id="logo-cropper" style="height:150px;" src="{{ asset('storage/base/logo.png') }}">
                                    </div>
                                    <input id="logo-cropped-result" class="d-none" type="file" name="logo">
                                    <input id="logo" type="file" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
{{--                            <div class="col-lg-6">--}}
{{--                                <div class="form-group mb-3">--}}
{{--                                    <label for="logo">Logo Gelap</label>--}}
{{--                                    <div class="d-flex justify-content-center">--}}
{{--                                        <img id="logo-dark-cropper" style="height:150px;" src="{{ asset('storage/base/logo-dark.png') }}">--}}
{{--                                    </div>--}}
{{--                                    <input id="logo-dark-cropped-result" class="d-none" type="file" name="logodark">--}}
{{--                                    <input id="logodark" type="file" class="form-control">--}}
{{--                                    <div class="invalid-feedback"></div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                        <div class="form-group mb-3">
                            <label for="title" class="mb-1">Nama Aplikasi</label>
                            <input id="title" type="text" class="form-control" name="title" value="{{ $data['title'] ?? '' }}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description" class="mb-1">Deskripsi Aplikasi</label>
                            <textarea id="description" type="text" class="form-control" name="description">{{ $data['description'] ?? 'description' }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropper/4.1.0/cropper.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css"/>
@endpush

@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
$(function(){
    'use strict'

    let favicon     = document.querySelector('#favicon-cropper'),
        logo        = document.querySelector('#logo-cropper'),
        logodark    = document.querySelector('#logo-dark-cropper');

    let faviconCropper = new Cropper(favicon, {
            viewMode : 1,
            movable: false,
            zoomable: false,
            rotatable: false,
            scalable: false,
            aspectRatio: 1 / 1,
            cropBoxResizable: false,
            autoCropArea: 1,
            responsive: true
        }),
        logoCropper = new Cropper(logo, {
            viewMode : 1,
            movable: false,
            zoomable: false,
            rotatable: false,
            scalable: false,
            cropBoxResizable: false,
            autoCropArea: 1,
            responsive: true
        });
        // logoDarkCropper = new Cropper(logodark, {
        //     viewMode : 1,
        //     movable: false,
        //     zoomable: false,
        //     rotatable: false,
        //     scalable: false,
        //     cropBoxResizable: false,
        //     autoCropArea: 1,
        //     responsive: true
        // });

    favicon.addEventListener("ready",function(){
        if(this.cropper === faviconCropper){
            faviconCropper.getCroppedCanvas({
                width: 72,
                height: 72,
                imageSmoothingEnabled   : true,
                imageSmoothingQuality   : 'high'
            }).toBlob((blob) => {
                let file        = new File([blob], 'favicon.png');
                let container   = new DataTransfer();
                container.items.add(file);
                $('#favicon-cropped-result').get(0).files = container.files;
            });
        }
    });

    $('#favicon').change(function(e){
        e.preventDefault();
        let files = e.target.files;
        if(files && files.length > 0){
            faviconCropper.replace(URL.createObjectURL(files[0]));
        }
    });

    logo.addEventListener("ready",function(){
        if(this.cropper === logoCropper){
            logoCropper.getCroppedCanvas({
                height: 200,
                imageSmoothingEnabled   : true,
                imageSmoothingQuality   : 'high'
            }).toBlob((blob) => {
                let file        = new File([blob], 'logo.png');
                let container   = new DataTransfer();
                container.items.add(file);
                $('#logo-cropped-result').get(0).files = container.files;
            });
        }
    });

    $('#logo').change(function(e){
        e.preventDefault();
        let files = e.target.files;
        if(files && files.length > 0){
            logoCropper.replace(URL.createObjectURL(files[0]));
        }
    });

    // logodark.addEventListener("ready",function(){
    //     if(this.cropper === logoDarkCropper){
    //         logoDarkCropper.getCroppedCanvas({
    //             height: 200,
    //             imageSmoothingEnabled   : true,
    //             imageSmoothingQuality   : 'high'
    //         }).toBlob((blob) => {
    //             let file        = new File([blob], 'logo.png');
    //             let container   = new DataTransfer();
    //             container.items.add(file);
    //             $('#logo-dark-cropped-result').get(0).files = container.files;
    //         });
    //     }
    // });

    // $('#logodark').change(function(e){
    //     e.preventDefault();
    //     let files = e.target.files;
    //     if(files && files.length > 0){
    //         logoDarkCropper.replace(URL.createObjectURL(files[0]));
    //     }
    // });

  $("#formUpdate").submit((e) => {
    e.preventDefault();
    const form = $(e.target);
    const btnSubmit = form.find("[type='submit']");
    const btnSubmitHtml = btnSubmit.html();
    const url = form.attr("action");
    const data = new FormData(e.target);

    $.ajax({
      beforeSend: () => {
        btnSubmit.addClass("disabled").html("<i class='spinner-border spinner-border-sm font-size-16 align-middle me-2'></i> Loading ...").prop("disabled", "disabled");
      },
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      cache: false,
      processData: false,
      contentType: false,
      type: "POST",
      url: url,
      data: data,
      success: (response) => {
        const errorEdit = $('#errorEdit');
        errorEdit.css('display', 'none');
        errorEdit.find('.alert-text').html('');
        btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
        if (response.status === "success") {
          toastr.success(response.message, 'Success !');
          location.reload();
        } else {
          toastr.error((response.message ? response.message : "Gagal mengubah data"), 'Failed !');
          if (response.error !== undefined) {
            errorEdit.removeAttr('style');
            $.each(response.error, (key, value) => {
              errorEdit.find('.alert-text').append(`<span style="display: block">${value}</span>`);
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

})
</script>
@endpush
