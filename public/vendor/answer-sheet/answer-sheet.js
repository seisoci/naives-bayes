class AnswerSheet {
    constructor(rootElement, options) {
        let defaultOptions = {
                element: {
                    root: rootElement,
                    btnControlContainer: "#as__btnControlContainer",
                    btnControl: ".as__btnControl",
                    btnNext: "#as__btnNext",
                },
                data: {},
                status: {},
                ajaxOptions: {},
                components: {
                    container: function (components) {
                        return `
                        <div class="container-xxl py-3 py-lg-5">
                            <div class="row">${components.left}${components.right}</div>
                        </div>
                    
                        <div class="modal fade" id="modalEnd" tabindex="-1" aria-labelledby="modalEndLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h5 class="fw-bolder">Peringatan !</h5>
                                        Waktu anda telah habis.<br/>data akan disimpan secara otomatis.
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Modal -->
                        <div class="modal fade" id="modalStore" tabindex="-1" aria-labelledby="modalStoreLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h5 class="fw-bolder">Peringatan !</h5>
                                        <span id="count-answered" class="text-center"></span>
                                        <br/>Lembar jawaban yang dikirim tidak dapat diubah kembali, pastikan anda sudah yakin dengan jawaban anda.
                                        <br/>Apakah anda yakin ingin melanjutkan ?
                                    </div>
                                    <div class="modal-footer">
                                        <button id="btn-cancel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button id="btn-store" type="button" class="btn btn-primary">Kirim !</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                    },
                    left: function (element, data) {
                        return `
                        <div class="col-lg-8 mb-5">
                            <div class="card shadow-sm border border-0">
                                <div class="card-header p-0">
                                    <div class="d-flex align-items-stretch overflow-hidden rounded-top">
                                        <div class="col text-bg-dark p-2">
                                            <span class="d-flex fw-bolder align-items-center h-100">Judul Naskah</span>
                                        </div>
                                        <div class="col text-bg-primary p-2">
                                            <div class="row g-0 justify-content-between align-items-center countdown-container">
                                                <div id="countdown-info" class="col-12 col-md-6 text-center fw-bolder">
                                                    Sisa Waktu Ujian
                                                </div>
                                                <div class="col-12 col-md-6 text-center">
                                                    <div id="section-countdown" class="countdown">
                                                        <span class="hours">--</span> : 
                                                        <span class="minutes">--</span> : 
                                                        <span class="seconds">--</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-md-4 p-lg-5">
                                    <div id="as__answer"></div>
                                    <div id="as__instruction" class="mb-4">${data.examination.instruction}</div>
                                    <div class="d-flex justify-content-center">
                                        <button id="btn-start" type="button" class="btn btn-primary">Tombol Mulai</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                    },
                    right: function (element, data) {
                        return `
                        <div class="col-lg-4 btn-control-container">
                            <div class="card shadow-sm border border-0 position-relative">
                                <div class="card-header p-0">
                                    <div class="text-bg-dark p-2 text-center overflow-hidden rounded-top">
                                        <span class="fw-bolder">Nomor Soal</span>
                                    </div>
                                </div>
                                <div class="card-body border-bottom">
                                    <div class="fw-bold mb-1">Keterangan :</div>
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center mb-1">
                                                <div class="rounded-1 border border-success bg-success p-2 me-2"></div>
                                                <small class="lh-1">Sudah dijawab</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center mb-1">
                                                <div class="rounded-1 border border-secondary p-2 me-2"></div>
                                                <small class="lh-1">Belum dijawab</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center mb-1">
                                                <div class="rounded-1 border border-primary bg-primary p-2 me-2"></div>
                                                <small class="lh-1">Saat ini</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-4"><div id="as__btnAnswerContainer" class="row row-cols-5"></div></div>
                                <div class="card-footer p-0" v-if="config.section.status">
                                    <button id="btn-modal-finish" class="btn btn-primary w-100 p-2 border border-0 rounded-0 rounded-bottom" data-bs-toggle="modal" data-bs-target="#modalStore" >
                                        Selesai
                                    </button>
                                </div>
                            </div>
                            <a href="" class="btn btn-outline-secondary w-100 mt-3">Pilih Naskah Lainnya</a>
                        </div>
                        `;
                    },
                },
            },
            properties = $.extend({}, defaultOptions, options);

        Object.entries(properties).forEach(([key, value]) => {
            this[key] = value;
        });

        setTimeout(() => {
            this.requestData();
        }, 1);
    }

    requestData() {
        let as = this;
        $.get(window.location.href).done(function (response) {
            as.data = response.data;
            $(as.element.root).data(as.data);
            as.renderRootElement(as.data);
        });
    }

    renderRootElement(data) {
        let as = this;

        $(as.element.root).html(
            as.components.container({
                left: as.components.left(as.element, data),
                right: as.components.right(as.element, data),
            })
        );

        // render number and button controll
        data.answers.forEach(function (row) {
            let notChoiced =
                    row.choice_id != undefined ||
                    row.choice_id != null ||
                    row.choice_id != "",
                $btn = $(`<div class="col mb-3">
                    <button class="${as.element.btnControl} btn ${
                    notChoiced ? `btn-outline-secondary` : `btn-success`
                } w-100">${row.number}</button>
                </div>
                `).data(row);

            $(as.element.btnControlContainer).append($btn);
        });
    }
}
