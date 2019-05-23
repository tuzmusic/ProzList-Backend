$(function () {
    $(".mainchk").click(function () {
        var ch = $(this).prop("checked");
        if(ch == true) {
            $(".innerallchk").prop("checked","checked");
        } else {
            $(".innerallchk").prop("checked","");
        }
    });
});
function chkmain() {
    var len = $(".innerallchk:unchecked").length;
    if(len == 0) {
        $(".mainchk").prop("checked","checked");
    } else {
        $(".mainchk").prop("checked","");
    }
}
function scrolltop() {
    $('html, body').animate({ scrollTop: 0 }, 'slow', function () {});
}
function load() {
    $("body").addClass("loading");
}
function unload() {
    $("body").removeClass("loading");
}
function hidemsg(after,speed) {
    setTimeout(function () {
        $("#msg").fadeOut(speed);
    },after);
}