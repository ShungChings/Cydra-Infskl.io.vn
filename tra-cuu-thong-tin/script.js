document.getElementById('system_search').addEventListener('submit', function(e){
    e.preventDefault();

    var name = document.getElementById('name').value;
    var fax = document.getElementById('fax').value;
    if (fax === '8000014467' ) {
        window.location.href = "https://hoaian.fun";
      } else {
        showMessage(' Đã lưu lại địa chỉ IP của bạn, việc cố đăng nhập sẽ làm bạn gặp nguy hiểm', 'red');
      }
    
   
}); 

function showMessage(message, color) {
      var messageDiv = document.getElementById('respond');
      messageDiv.textContent = message;
      messageDiv.style.color = color;
    }