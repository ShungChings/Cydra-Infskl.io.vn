$(".input_text").focus(function(){
    $(this).prev('.fa').addclass('glowIcon')
})
$(".input_text").focusout(function(){
    $(this).prev('.fa').removeclass('glowIcon')
})


function login() {
  alert("Hiện Tính Năng Này Không Hỗ Trợ Với Bạn.");
}