"use strict";

var audio = document.getElementById("myAudio");
function playAudio() {
    audio.play();
}
function pauseAudio() {
    audio.pause();
}
toastr.options = {
    closeButton: false,
    debug: false,
    newestOnTop: false,
    progressBar: false,
    positionClass: "toast-bottom-left",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};
$(document).on("ready", function () {
    $(".view--more").each(function () {
        const viewItem = $(this);
        const initialHeight = $(this).height();
        if (viewItem.height() > 130) {
            viewItem.addClass("view-more-collapsable");
            const btn = viewItem.find(".expandable-btn");
            btn.removeClass("d-none");
            btn.on("click", function () {
                if (btn.find(".more").hasClass("d-none")) {
                    viewItem.css("height", "130px");
                    btn.find(".more").removeClass("d-none");
                    btn.find(".less").addClass("d-none");
                } else {
                    viewItem.css("height", initialHeight + 40);
                    btn.find(".less").removeClass("d-none");
                    btn.find(".more").addClass("d-none");
                }
            });
        }
    });

    $("img.svg").each(function () {
        let $img = jQuery(this);
        let imgID = $img.attr("id");
        let imgClass = $img.attr("class");
        let imgURL = $img.attr("src");

        jQuery.get(
            imgURL,
            function (data) {
                let $svg = jQuery(data).find("svg");
                if (typeof imgID !== "undefined") {
                    $svg = $svg.attr("id", imgID);
                }
                if (typeof imgClass !== "undefined") {
                    $svg = $svg.attr("class", imgClass + " replaced-svg");
                }

                $svg = $svg.removeAttr("xmlns:a");
                if (
                    !$svg.attr("viewBox") &&
                    $svg.attr("height") &&
                    $svg.attr("width")
                ) {
                    $svg.attr(
                        "viewBox",
                        "0 0 " + $svg.attr("height") + " " + $svg.attr("width")
                    );
                }

                $img.replaceWith($svg);
            },
            "xml"
        );
    });

    if (window.localStorage.getItem("hs-builder-popover") === null) {
        $("#builderPopover")
            .popover("show")
            .on("shown.bs.popover", function () {
                $(".popover").last().addClass("popover-dark");
            });

        $(document).on("click", "#closeBuilderPopover", function () {
            window.localStorage.setItem("hs-builder-popover", true);
            $("#builderPopover").popover("dispose");
        });
    } else {
        $("#builderPopover").on("show.bs.popover", function () {
            return false;
        });
    }
    $(".js-navbar-vertical-aside-toggle-invoker").click(function () {
        $(".js-navbar-vertical-aside-toggle-invoker i").tooltip("hide");
    });
    let sidebar = $(".js-navbar-vertical-aside").hsSideNav();

    $(".js-nav-tooltip-link").tooltip({ boundary: "window" });

    $(".js-nav-tooltip-link").on("show.bs.tooltip", function () {
        if (!$("body").hasClass("navbar-vertical-aside-mini-mode")) {
            return false;
        }
    });
    $(".js-hs-unfold-invoker").each(function () {
        let unfold = new HSUnfold($(this)).init();
    });

    $(".js-form-search").each(function () {
        new HSFormSearch($(this)).init();
    });

    $(".js-select2-custom").each(function () {
        let select2 = $.HSCore.components.HSSelect2.init($(this));
    });

    $(".js-daterangepicker").daterangepicker();

    $(".js-daterangepicker-times").daterangepicker({
        timePicker: true,
        startDate: moment().startOf("hour"),
        endDate: moment().startOf("hour").add(32, "hour"),
        locale: {
            format: "M/DD hh:mm A",
        },
    });
    let start = moment();
    let end = moment();
    function cb(start, end) {
        $(
            "#js-daterangepicker-predefined .js-daterangepicker-predefined-preview"
        ).html(start.format("MMM D") + " - " + end.format("MMM D, YYYY"));
    }
    $("#js-daterangepicker-predefined").daterangepicker(
        {
            startDate: start,
            endDate: end,
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [
                    moment().subtract(1, "days"),
                    moment().subtract(1, "days"),
                ],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [
                    moment().startOf("month"),
                    moment().endOf("month"),
                ],
                "Last Month": [
                    moment().subtract(1, "month").startOf("month"),
                    moment().subtract(1, "month").endOf("month"),
                ],
            },
        },
        cb
    );

    cb(start, end);
    $(".js-clipboard").each(function () {
        let clipboard = $.HSCore.components.HSClipboard.init(this);
    });
});

function getRndInteger() {
    return Math.floor(Math.random() * 90000) + 100000;
}
let errorMessages = {
    valueMissing: $(".please_fill_out_this_field").data("text"),
};
$("input").each(function () {
    let $el = $(this);

    $el.on("invalid", function (event) {
        let target = event.target,
            validity = target.validity;
        target.setCustomValidity("");
        if (!validity.valid) {
            if (validity.valueMissing) {
                target.setCustomValidity(
                    $el.data("errorRequired") || errorMessages.valueMissing
                );
            }
        }
    });
});
