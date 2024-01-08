class Layout {
    content() {
        return `<div class="card shadow-sm border border-0 sticky-lg-top">
            <div class="card-header p-0">
                <div class="d-flex align-items-stretch overflow-hidden rounded-top">
                    <div class="col text-bg-dark p-2">
                        <span id="as__script_title" class="d-flex fw-bolder align-items-center h-100"></span>
                    </div>
                    <div class="col text-bg-primary p-2">
                        <div hidden class="row g-0 justify-content-between align-items-center countdown-container">
                            <div class="col-12 col-md-6 text-center fw-bolder">Sisa Waktu Ujian</div>
                            <div class="col-12 col-md-6 text-center">
                                <div id="as__mainCountdown" class="countdown">
                                    <span class="hours">--</span> : <span class="minutes">--</span> : <span class="seconds">--</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body ">
                <div id="as__question"></div>
                <div id="as__instruction" class="mb-4"></div>
                <div class="d-flex justify-content-center">
                    <button id="as__btnStart" type="button" class="btn btn-primary">Lihat Jawaban</button>
<!--                    <button id="as__btnNext" type="button" class="btn btn-primary d-none">Selanjutnya</button>-->
                </div>
            </div>
        </div>`;
    }

    navigator() {
        return `
        <!-- collapse -->
        <div class="d-block d-lg-none">
            <button class="btn rounded-0 rounded-top w-100 text-bg-dark p-2 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                Nomor Soal
            </button>

            <div class="collapse" id="collapseExample">
                <div class="card rounded-0 rounded-bottom">
    <!--                <div class="card-header p-0">-->
    <!--                    <div class="text-bg-dark p-2 text-center overflow-hidden rounded-top">-->
    <!--                        <span class="fw-bolder">Nomor Soal</span>-->
    <!--                    </div>-->
    <!--                </div>-->
                <div class="card-body border-bottom">
                    <div class="fw-bold mb-1">Keterangan :</div>
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center mb-1">
                                <div class="rounded-1 border border-success bg-success p-2 me-2"></div>
                                <small class="lh-1">Jawaban Benar</small>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="d-flex align-items-center mb-1">
                                <div class="rounded-1 border border-danger bg-danger p-2 me-2"></div>
                                <small class="lh-1">Jawaban Salah</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="as__btnControlContainerMobile" class="card-body p-4"></div>
                <div class="card-footer p-0">
                    <button id="as__btnModalFinish" class="btn btn-primary w-100 p-2 border border-0 rounded-0 rounded-bottom" data-bs-toggle="modal" data-bs-target="#as__modalFinish">Selesai</button>
                </div>
                </div>
            </div>
        </div>
        <!-- end collapse -->

        <div class="card shadow-sm border border-0 position-relative d-none d-lg-block">
            <div class="card-header p-0">
                <div class="text-bg-dark p-2 text-center overflow-hidden rounded-top">
                    <span class="fw-bolder">Nomor Soal</span>
                </div>
            </div>
            <div class="card-body border-bottom">
                <div class="fw-bold mb-1">Keterangan :</div>
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="d-flex align-items-center mb-1">
                            <div class="rounded-1 border border-success bg-success p-2 me-2"></div>
                            <small class="lh-1">Jawaban Benar</small>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex align-items-center mb-1">
                            <div class="rounded-1 border border-danger bg-danger p-2 me-2"></div>
                            <small class="lh-1">Jawaban Salah</small>
                        </div>
                    </div>
                </div>
            </div>
            <div id="as__btnControlContainer" class="card-body p-4"></div>
            <div class="card-footer p-0">
                <button hidden id="as__btnModalFinish" class="btn btn-primary w-100 p-2 border border-0 rounded-0 rounded-bottom" data-bs-toggle="modal" data-bs-target="#as__modalFinish">Selesai</button>
            </div>
        </div>
        `;
    }

    render() {
        return `
        <div class="container-xxl py-3 py-lg-5">
            <div class="row">
                <div class="col-lg-6 col-xl-8 mb-3 mb-lg-5">${this.content()}</div>
<!--                btn-control-container-->
                <div class="col-lg-6 col-xl-4 ">
                    ${this.navigator()}
                    <a hidden id="as__linkOtherScripts" href="" class="btn btn-outline-secondary w-100 mt-3">Pilih Naskah Lainnya</a>
                </div>
            </div>
        </div>
        <div class="modal fade" id="as__modalMainCountdown" tabindex="-1" aria-labelledby="as__modalMainCountdownLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="fw-bolder">Peringatan !</h5>
                        Waktu anda telah habis.<br/>data akan disimpan secara otomatis.
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="as__modalFinish" tabindex="-1" aria-labelledby="as__modalFinishLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="fw-bolder">Peringatan !</h5>
                        <span id="count-answered" class="text-center"></span>
                        <br/>Lembar jawaban yang dikirim tidak dapat diubah kembali, pastikan anda sudah yakin dengan jawaban anda.
                        <br/>Apakah anda yakin ingin melanjutkan ?
                    </div>
                    <div class="modal-footer">
                        <button id="as__btnModalFinishDismiss" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button id="as__btnFinish" type="button" class="btn btn-primary">Kirim !</button>
                    </div>
                </div>
            </div>
        </div>
        `;
    }

    generateNavigation(answers) {
        let $html = ``;
        if (answers.length > 0) {
            $html = $(`<div class="row row-cols-3 row-cols-md-5"></div>`);
            answers.forEach(function (row) {
                let choiced = row.choice_id != null,
                    btnClass = choiced
                        ? `btn-success`
                        : `btn-outline-secondary`,
                    $btn = $(`
                    <div class="col mb-3">
                        <button
                            onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                            class="as__btnControl btn
                            ${btnClass}
                            w-100"
                            as--has-downloaded="0"
                            as--has-answered="${choiced ? 1 : 0}"
                            as--answer-number="${row.number}"
                        >${row.number}</button>
                    </div>`);
                $btn.children(".as__btnControl").data(row);
                $html.append($btn);
            });
        }
        return $html;
    }

    generateQuestion(answer) {
        let choicesHtml = ``;
        console.log(Math.max(...answer.question.choices.map(o => o.score)));
        let max  = Math.max(...answer.question.choices.map(o => o.score));
        answer.question.choices.forEach((choice) => {
            choicesHtml += `
            <div class="mb-2 d-flex choice" style="top: 50%;outline: 2px solid ${choice.score == max ? 'green': choice.id == answer.choice_id ? "red" : "white" } ;padding-top: 8px; padding-left: 8px;">
                <div class="key">
                    <input disabled
                        id="as__choice_${choice.id}"
                        class="btn-check as__choice_item"
                        type="radio"
                        name="choice_id"
                        value="${choice.id}"
                        ${choice.id == answer.choice_id ? "checked" : ""}
                    >
                    <label
                        for="as__choice_${choice.id}"
                        class="btn btn-sm btn-outline-primary text-uppercase"
                    >${choice.key}</label>
                </div>
                <div class="content">${choice.content} </div>
                ${choice.score == max ? '<div class="content" style="margin-left: 8px; "><i class="fa-solid fa-circle-check" style="color: #12f202;"></i> </div>' : ''}
                
                
                
            </div>
            `;
        });

        let group = ``,
            heading = ``;
        if (answer.question.group != null && answer.question.group != "") {
            group = `
            <div class="text-bg-dark p-2 rounded-1 me-3">
                <span class="d-flex fw-bolder align-items-center h-100">${answer.question.group}</span>
            </div>`;
        }
        if (answer.question.heading != null && answer.question.heading != "") {
            heading = `<div class="text-bg-primary p-2 rounded-1 ms-auto">
                <span class="d-flex fw-bolder align-items-center h-100">${answer.question.heading}</span>
            </div>`;
        }

        let $html = $(`
            <div class="row mb-3">
                <div class="d-flex col-md-6 mb-3">
                    <div class="text-bg-dark px-4 py-2 rounded-1 me-3">
                        <span class="d-flex fw-bolder align-items-center h-100">No. ${answer.number}</span>
                    </div>
                    ${group}
                </div>
                <div class="col">
                    ${heading}
                </div>

            </div>
            <hr>
            <div id="as__questionSentence" class="mb-3">${answer.question.sentence}</div>
            <div id="as__questionChoices" class="mb-3">${choicesHtml}</div>
            <hr class="d-none d-md-block">
<!--            <div style="position: absolute; right: 50px;">-->
<!--                Copyright © 2022 <a target="_blank" href="https://tarunarepublic.com">Taruna Republic</a>-->
<!--            </div>-->
            <div class="text-center text-md-end d-none d-lg-block">
                Copyright © 2022 <a target="_blank" href="https://tarunarepublic.com">Taruna Republic</a>
            </div>
        `);

        return $html;
    }
}

class AnswerSheet {
    constructor(rootElement, options) {
        let defaultOptions = {
                elements: {
                    root: rootElement,
                    scriptTitle: "#as__script_title",
                    instruction: "#as__instruction",
                    btnControlContainer: "#as__btnControlContainer",
                    btnControlContainerMobile: "#as__btnControlContainerMobile",
                    btnControl: ".as__btnControl",
                    btnStart: "#as__btnStart",
                    btnNext: "#as__btnNext",
                    question: "#as__question",
                    mainCountdown: "#as__mainCountdown",
                    btnFinish: "#as__btnFinish",
                },
            },
            properties = $.extend({}, defaultOptions, options);

        Object.entries(properties).forEach(([key, value]) => {
            this[key] = value;
        });
        this.Layout = new Layout();
        this.init();
    }

    init() {
        let as = this;
        as.build();
        console.log(as.next());
        $(as.elements.root).attr({
            "as--has-running": 0,
            "as--current-number": 0,
            "as--duration-used": 0,
        });
        $(as.elements.root).bind({
            layoutRendered: function () {
                as.requestDataRoot();
            },
            ready: function () {
                let data = $(this).data();

                $(as.elements.root).attr(
                    "as--duration-used",
                    data.participant.duration_used
                );

                if (data.section.control_mode == 0) {
                    $(as.elements.btnControl)
                        .prop("disabled", true)
                        .attr("disabled", "disabled")
                        .addClass("disabled");
                }

                $(this)
                    .find(as.elements.btnStart)
                    .on({
                        click: function () {
                            $(this).addClass("d-none");
                            as.next();
                        },
                    });

                $(this)
                    .find(as.elements.btnNext)
                    .on({
                        click: function () {
                            as.next();
                        },
                    });

                $(this)
                    .find(as.elements.btnControl)
                    .on({
                        click: function (event) {
                            console.log(as);
                            as.question(event);
                        },
                    });

                $(this)
                    .find(as.elements.question)
                    .on({
                        contentLoaded: function (event) {
                            $(event.target)
                                .find(".as__choice_item")
                                .closest(".key")
                                .next(".content")
                                .click(function () {
                                    $(this)
                                        .prev(".key")
                                        .children("label")
                                        .trigger("click");
                                });
                            $(event.target)
                                .find(".as__choice_item")
                                .change(function (event) {
                                    let btnControl = $(
                                        as.elements.btnControl +
                                            `[as--answer-number=${$(
                                                as.elements.root
                                            ).attr("as--current-number")}]`
                                    );
                                    btnControl.attr("as--has-answered", 1);
                                    btnControl.data("choice_id", $(this).val());
                                    if (data.section.auto_next == 1) {
                                        as.next();
                                    }
                                });
                        },
                    });
                $(this)
                    .find(as.elements.btnFinish)
                    .click(function (event) {
                        window.removeEventListener(
                            "beforeunload",
                            function (e) {
                                e.preventDefault();
                                e.returnValue =
                                    "Are you sure you want to exit?";
                                return e.returnValue;
                            },
                            false
                        );
                        $(event.target).html(
                            `<i class="fas fa-spinner fa-spin"></i>`
                        );
                        $(as.elements.root).attr("as--has-finished", 1);
                        $(as.elements.root).trigger("change");

                        if (as.finish()) {
                            // window.location.href = `/examinations/${data.participant.id}`;
                            // window.location.href = `/cat-taruna-republic/public/examinations/${data.participant.id}`;
                        }
                    });
            },
        });
    }

    build() {
        let as = this;
        $(as.elements.root).html(as.Layout.render());
        setTimeout(() => {
            $(as.elements.root).trigger("layoutRendered");
        }, 1);
    }

    requestDataRoot() {
        
        let as = this;

        $.get(
            window.location.href,
            function (response) {
                let data = response.data;
                if (data != undefined || data != null) {
                    $(as.elements.root).data(data);
                    if (data.examination) {
                        $(as.elements.root)
                            .find(as.elements.instruction)
                            .html(data.examination.instruction);
                    }
                    if (data.section && data.section.script) {
                        $(as.elements.root)
                            .find(as.elements.scriptTitle)
                            .html(data.section.script.title);
                    }

                    if (data.answers) {
                        let htmlControls = as.Layout.generateNavigation(
                            data.answers
                        );
                        $(as.elements.root)
                            .find(as.elements.btnControlContainer)
                            .html(htmlControls);
                    }

                    if (data.answers) {
                        let htmlControls = as.Layout.generateNavigation(
                            data.answers
                        );
                        $(as.elements.root)
                            .find(as.elements.btnControlContainerMobile)
                            .html(htmlControls);
                    }

                    $(as.elements.root).trigger("ready");
                }
            },
            "json"
        );
    }

    next() {
        let as = this;
        let currentNumber = $(as.elements.root).attr("as--current-number"),
            nextNumber = parseInt(currentNumber) + 1,
            nextControl = $(
                as.elements.btnControl + `[as--answer-number="${nextNumber}"]`
            ).first();

        nextControl.trigger("click");
    }

    question(event) {
        let as = this,
            btn = $(event.target);

        $(as.elements.btnControl)
            .removeClass("btn-primary")
            .addClass("btn-outline-secondary");
        $(as.elements.btnControl + `[as--has-answered="1"]`)
            .removeClass("btn-outline-secondary")
            .addClass("btn-success");
        btn.removeClass("btn-outline-secondary").addClass("btn-primary");

        if (btn.attr("as--has-downloaded") != 1) {
            btn.html(`<i class="fas fa-spinner fa-spin"></i>`);
            $.get(
                window.location.href + `/${btn.data("id")}`,
                function (response) {
                    let data = response.data;
                    btn.attr("as--has-downloaded", 1);
                    btn.html(data.number);
                    btn.data(data);
                    $(as.elements.question).html(
                        as.Layout.generateQuestion(btn.data())
                    );
                    $(as.elements.question).trigger("contentLoaded");
                },
                "json"
            );
        } else {
            $(as.elements.question).html(
                as.Layout.generateQuestion(btn.data())
            );
            $(as.elements.question).trigger("contentLoaded");
        }
        $(as.elements.instruction).remove();
        $(as.elements.root).attr("as--current-number", btn.data("number"));
        if ($(as.elements.root).attr("as--has-running") == 0) {
            as.runCountdown();
        }
    }

    runCountdown() {
        let as = this,
            data = $(as.elements.root).data(),
            timeRemaining =
                parseInt(data.examination.duration) -
                parseInt(data.participant.duration_used);

        let time = moment()
            .add(timeRemaining, "s")
            .format("YYYY/MM/DD HH:mm:ss");

        let durationUsed = parseInt(data.participant.duration_used);
        $(as.elements.mainCountdown)
            .countdown(time)
            .on({
                "update.countdown": function (event) {
                    durationUsed++;
                    $(this)
                        .find(".hours")
                        .html(event.strftime(event.strftime("%H")));
                    $(this)
                        .find(".minutes")
                        .html(event.strftime(event.strftime("%M")));
                    $(this)
                        .find(".seconds")
                        .html(event.strftime(event.strftime("%S")));
                    data.participant.duration_used = durationUsed;
                    $(as.elements.root).data(data);
                    $(as.elements.root).attr("as--duration-used", durationUsed);
                },
                "finish.countdown": function (event) {
                    // $("#as__btnModalCountdown").trigger("click");
                    // setTimeout(function () {
                        // console.log('finish');
                        // $(as.elements.btnFinish).trigger("click");
                    // }, 3000);
                },
            });

        // $.post(window.location.href, {
        //     _method: "PUT",
        //     participant: {
        //         duration_used: $(as.elements.root).attr("as--duration-used"),
        //     },
        //     participant_section: {
        //         status: "On Going",
        //     },
        // });

        $(as.elements.instruction).remove();
        $(as.elements.root).attr("as--has-running", 1);
        $(as.elements.btnStart).remove();
        if (data.section.auto_next == 0) {
            $(as.elements.btnNext).removeClass("d-none");
        }

        window.addEventListener(
            "beforeunload",
            function (e) {
                e.preventDefault();
                as.leave();
                e.returnValue = "Are you sure you want to exit?";
                return e.returnValue;
            },
            false
        );
    }
    
    finish(status = null) {
        // let as = this;
        // let answeredData = {
        //     _method: "PUT",
        //     answers: [],
        // };

        // $(as.elements.btnControl).each(function () {
        //     let data = $(this).data();
        //     answeredData.answers.push({
        //         id: data.id,
        //         choice_id: data.choice_id,
        //     });
        // });
        // $.post(window.location.href + `/answers`, answeredData);
        // $.post(window.location.href, {
        //     _method: "PUT",
        //     participant: {
        //         duration_used: $(as.elements.root).attr("as--duration-used"),
        //     },
        //     participant_section: {
        //         status: "Finish",
        //     },
        // });
        // return true;
    }
    leave() {
        let as = this;
        $(as.elements.mainCountdown).countdown("stop");
        let answeredData = {
            _method: "PUT",
            answers: [],
        };

        $(as.elements.btnControl).each(function () {
            let data = $(this).data();
            answeredData.answers.push({
                id: data.id,
                choice_id: data.choice_id,
            });
        });
        // $.post(window.location.href + `/answers`, answeredData);
        // $.post(window.location.href, {
        //     _method: "PUT",
        //     participant: {
        //         duration_used: $(as.elements.root).attr("as--duration-used"),
        //     },
        // });
    }
}
