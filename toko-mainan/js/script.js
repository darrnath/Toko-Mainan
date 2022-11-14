var keyword_alamat = document.getElementById("alamatInput");
var table_alamat = document.querySelector("#table-address table");

// AJAX code
keyword_alamat.addEventListener("keyup", function () {
  //Membuat object AJAX
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      table_alamat.innerHTML = xhr.responseText;
    }
  };
  //Excecute data
  xhr.open(
    "GET",
    "../toko-mainan/ajax/alamat.php?a=" + keyword_alamat.value,
    true
  );
  xhr.send();
});
