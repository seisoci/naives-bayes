class Layout {
  content() {
    return `<div class="card shadow-sm border border-0 sticky-lg-top">
            <div class="card-header p-0">
                <div class="d-flex align-items-stretch overflow-hidden rounded-top">
                    <div class="col text-bg-dark p-2">
                        <span id="as__script_title" class="d-flex fw-bolder align-items-center h-100"></span>
                    </div>
                    <div class="col text-bg-primary p-2">
                        <div class="row g-0 justify-content-between align-items-center countdown-container">
                            <div class="col-12 col-md-6 text-center fw-bolder">Waktu Pengerjaan</div>
                            <div class="col-12 col-md-6 text-center">
                                <div id="as__mainCountdown" class="countdown">
                                    <span class="countup">-- : -- : -- </span> /  <span class="total"> </span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="as__introduction"></div>
                <div id="as__demo"></div>
                <div id="as__question"></div>
                <div id="as__instruction" class="mb-4 d-none"></div>
                <div class="d-flex justify-content-center">
                    <button id="as__btnStart" type="button" class="btn btn-primary d-none">Mulai Ujian</button>
                </div>
            </div>
        </div>`;
  }

  footer() {
    return `
     <hr class="d-none d-md-block">
      <div class="text-center text-md-end d-none d-lg-block">
          Copyright © 2023 <a target="_blank" href="/">CAT</a>
      </div>
    `;
  }

  section() {
    return `
        <!-- collapse -->
        <div class="d-block d-lg-none">
            <button class="btn rounded-0 rounded-top w-100 text-bg-dark p-2 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSubTest" aria-expanded="false" aria-controls="collapseSubTest">
                Sub Test Soal
            </button>

            <div class="collapse" id="collapseSubTest">
                <div class="card rounded-0 rounded-bottom">

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
                    <span class="fw-bolder">Sub Test Soal</span>
                </div>
            </div>
            <div class="card-body border-bottom">
                <div class="fw-bold mb-1">Keterangan :</div>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-1">
                            <div class="rounded-1 border border-success bg-success p-2 me-2"></div>
                            <small class="lh-1">Selesai</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-1">
                            <div class="rounded-1 border border-secondary p-2 me-2"></div>
                            <small class="lh-1">Belum</small>
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
        </div>
        `;
  }

  navigator() {
    return `
        <div class="card shadow-sm border border-0 position-relative d-none d-lg-block">
            <div class="card-header p-0">
                <div class="text-bg-dark p-2 text-center overflow-hidden rounded-top">
                    <span class="fw-bolder">RMIB</span>
                </div>
            </div>
            <div id="as__btnControlContainer" class="card-body p-4"></div>
            <div class="card-footer p-0">
                <button id="as__btnModalFinish" class="btn btn-primary w-100 p-2 border border-0 rounded-0 rounded-bottom" data-bs-toggle="modal" data-bs-target="#as__modalFinish">Selesai</button>
            </div>
        </div>
    `;
  }

  render() {
    return `
        <div class="container-xxl py-3 py-lg-5">
            <div class="row">
                <div class="col-lg-6 col-xl-8 mb-3 mb-lg-5">${this.content()}</div>
                <div id="as__navigator" class="col-lg-6 col-xl-4 d-none">
                    ${this.navigator()}
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

  generateIntroduction(examples) {
    let $html = ``;
    $html += `
       <div id="as__mainCountdownInstruction" class="countdown">
          <h1 class="text-center"><span class="hours">--</span> : <span class="minutes">--</span> : <span class="seconds">--</span></h1>
       </div>
      <div class="my-2">${examples.description ?? ''}</div>
    `;

    return $html;
  }

  generateDemo(data) {
    let as = this;
    let num = 1;
    let $html = `<ul id="sortableDemo" class="list-group col">`;
    data.questions.forEach((item) => {
      $html += `
          <li data-question_id="${item.id}" class="list-group-item text-bg-light">
            <label class="pe-3 mb-0">${num++}</label><i class="fas fa-arrows-alt-v pe-2"></i>
            ${this.stripTagsUsingDOM(item.sentence)}</li>
      `;
    });
    $html += `</div>`;
    $html += `
            <div class="d-flex justify-content-center mt-3">
              <button id="as__btnDemo" data-type="Single" type="button" class="btn btn-primary">Lanjut Mengerjakan Soal</button>
            </div>`;

    return $html;
  }

  generateQuestion(data) {
    let as = this;
    let num = 1;
    let $html = `<div class="mb-4">${data.description ?? ''}</div>`;
    $html += `<ul id="sortable" class="list-group col">`;
    data.questions.forEach((item) => {
      $html += `
          <li data-question_id="${item.id}" class="list-group-item text-bg-light">
            <label class="pe-3 mb-0">${num++}</label><i class="fas fa-arrows-alt-v pe-2"></i>
            ${this.stripTagsUsingDOM(item.sentence)}</li>
      `;
    });
    $html += `</div></div>`;
    $html += as.footer();
    return $html;
  }

  stripTagsUsingDOM = (input) => {
    const tempElement = document.createElement("div");
    tempElement.innerHTML = input;
    return tempElement.textContent || tempElement.innerText;
  };
}

class AnswerSheet {
  constructor(rootElement, options) {
    let defaultOptions = {
        elements: {
          root: rootElement,
          sortable: "sortable",
          sortableDemo: "sortableDemo",
          scriptTitle: "#as__script_title",
          demo: "#as__demo",
          navigator: "#as__navigator",
          introduction: "#as__introduction",
          instruction: "#as__instruction",
          btnControlContainer: "#as__btnControlContainer",
          btnControlContainerSubTest: "#as__btnControlContainerSubTest",
          btnControlContainerMobile: "#as__btnControlContainerMobile",
          btnControl: ".as__btnControl",
          btnControlDemo: ".as__btnControlDemo",
          btnStart: "#as__btnStart",
          btnDemo: "#as__btnDemo",
          btnNext: "#as__btnNext",
          question: "#as__question",
          mainCountdown: "#as__mainCountdown",
          mainCountdownInstruction: "#as__mainCountdownInstruction",
          btnFinish: "#as__btnFinish",
          btnOtherScripts: "#as__linkOtherScripts",
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
              $(this).parents().find(as.elements.navigator).removeClass("d-none");
              $(as.elements.instruction).remove();
              $(as.elements.question).removeClass('d-none');
              as.vStatus();
              as.intervalAnswers();
              as.runCountdown();
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
              window.location.href = `/examinations/${data.participant.id}`;
            }
          });

        $(this)
          .find(as.elements.btnOtherScripts).attr("href", `/examinations/${data.participant.id}`)

        /* Demo */
        $(this)
          .find(as.elements.demo)
          .find(".as__choice_demo_item")
          .off('change')
          .change(function (event) {
            let btnControl = $(
              as.elements.btnControl +
              `[as--answer-number=${$(
                as.elements.root
              ).attr("as--current-number")}]`
            );
            btnControl.attr("as--has-answered", 1);
            btnControl.data("choice_id", $(this).val());
          });

        $(this)
          .find(as.elements.btnDemo)
          .on({
            click: function () {
              $(as.elements.demo).remove();
              $(as.elements.instruction).removeClass('d-none');
              $(as.elements.btnStart).removeClass('d-none');
              as.vDemo();
            },
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
        let data = response.data.answers;

        if (data != undefined || data != nul) {
          $(as.elements.root).data(data);

          if (data.section.script.example && data.v_demo == 0) {
            let htmlControls = as.Layout.generateDemo(data.section.script.example)
            $(as.elements.root)
              .find(as.elements.demo)
              .html(htmlControls);
          }

          if (!data.section.script.example) {
            $(as.elements.instruction).removeClass('d-none');
            $(as.elements.btnStart).removeClass('d-none');
          }

          if (data.v_demo == 1) {
            $(as.elements.instruction).removeClass('d-none');
            $(as.elements.btnStart).removeClass('d-none');
          }

          if (data.examination) {
            $(as.elements.root)
              .find(as.elements.instruction)
              .html(data.section.script.description);
          }

          if (data.section && data.section.script) {
            $(as.elements.question)
              .html(as.Layout.generateQuestion(data.section.script));

            $(as.elements.question).addClass('d-none');

            new Sortable(document.getElementById(as.elements.sortable), {
              animation: 150,
              ghostClass: 'blue-background-class',
              onChange: function(item) {
                let num = 1;
                $('#sortable li').each(function(){
                  $(this).find('label').text(num);
                  num++;
                });
              }
            });

            if (!data.v_demo && data.section.script.example) {
              new Sortable(document.getElementById(as.elements.sortableDemo), {
                animation: 150,
                ghostClass: 'blue-background-class',
                onChange: function(item) {
                  let num = 1;
                  $('#sortableDemo li').each(function(){
                    $(this).find('label').text(num);
                    num++;
                  });
                }
              });
            }

            $(as.elements.root)
              .find(as.elements.scriptTitle)
              .html(data.section.script.title);

            $(as.elements.mainCountdown)
              .find(".total")
              .html(Math.floor(data.section.duration / 60) + " Menit");

            if (data.duration_used > 0) {
              $(as.elements.mainCountdown)
                .find(".countup")
                .html(as.formatTime(data.duration_used));
            }
          }

          $(as.elements.root).trigger("ready");
        }
      },
      "json"
    );
  }

  demo(event) {
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
        window.location.href + `/answers/${btn.data("id")}`,
        function (response) {
          let data = response.data;
          btn.attr("as--has-downloaded", 1);
          btn.html(data.number);
          btn.data(data);
          $(as.elements.question).html(
            as.Layout.generateDemo(btn.data())
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
  }

  runCountdown() {
    this.ajax();
    let as = this,
      data = $(as.elements.root).data();

    let timeRemaining = 0 + parseInt(data.duration_used ?? 0);

    const updateTimer = () => {
      timeRemaining++;
      $(as.elements.mainCountdown)
        .find(".countup")
        .html(this.formatTime(timeRemaining));
      $(as.elements.root).attr("as--duration-used", timeRemaining);
    };

    setInterval(updateTimer, 1000);

    $.post(window.location.href, {
      _method: "PUT",
      participant: {
        duration_used: $(as.elements.root).attr("as--duration-used"),
        status: 'Running'
      },
      participant_section: {
        status: "On Going",
      },
    });

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
    let as = this;
    let answeredData = {
      _method: "PUT",
      type: 'RMIB',
      answers: []
    };

    $("#sortable li").map(function () {
      answeredData.answers.push({
        'question_id': $(this).data('question_id')
      });
    });


    $.post(window.location.href + `/update-answers`, answeredData);
    $.post(window.location.href, {
      _method: "PUT",
      participant: {
        duration_used: $(as.elements.root).attr("as--duration-used"),
        status: "Finish",
      },
    });
    return true;
  }

  intervalAnswers() {
    let as = this;
    as.ajax();

    let updateAnswers = () => {
      let answeredData = {
        _method: "PUT",
        answers: [],
      };

      $(as.elements.btnControl).each(function () {
        let data = $(this).data();
        answeredData.answers.push({
          id: data.id,
          choice_id: data.choice_id,
          answer: data.answer,
        });
      });
      $.post(window.location.href + `/update-answers`, answeredData);
    }

    setInterval(updateAnswers, 120000);
  }

  leave() {
    this.ajax();
    let as = this;
    let answeredData = {
      _method: "PUT",
      type: 'RMIB',
      answers: [],
    };

    $("#sortable li").map(function () {
      answeredData.answers.push({
        'question_id': $(this).data('question_id')
      });
    });

    $.post(window.location.href + `/update-answers`, answeredData);
    $.post(window.location.href, {
      _method: "PUT",
      participant: {
        duration_used: $(as.elements.root).attr("as--duration-used"),
      },
    });
  }

  vDemo() {
    this.ajax();
    $.post(window.location.href + `/update-status`, {
      _method: "PUT",
      participant: {
        v_demo: 1
      },
    });
  }

  vStatus() {
    this.ajax();
    $.post(window.location.href + `/update-status`, {
      _method: "PUT",
      participant: {
        v_introduction: 1,
        v_demo: 1
      },
    });
  }

  ajax() {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    });
  }

  formatTime = (time) => {
    const hours = String(Math.floor(time / 3600)).padStart(2, '0');
    const minutes = String(Math.floor((time % 3600) / 60)).padStart(2, '0');
    const seconds = String(time % 60).padStart(2, '0');

    return `${hours}:${minutes}:${seconds}`;
  };

}
