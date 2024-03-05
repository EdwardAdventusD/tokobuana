//  Halaman Login
$(document).ready(function () {
  if (document.getElementById("username").value == "") {
    $("#username").focus();
  } else {
    $("#password").focus();
  }
});

// Halaman Transaksi
$(document).ready(function () {
  $("#view_status").change(function () {
    var status = $(this).val();
    if (status == "Belum Lunas") {
      $("#view_tanggal_bayar").val("");
      $("#view_tanggal_bayar").prop("required", false);
    } else {
      $("#view_tanggal_bayar").prop("required", true);
    }
  });
});

$(document).on("submit", "#SaveTransaksi", function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  formData.append("insert_transaksi", true);

  $.ajax({
    type: "POST",
    url: "backend/function.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.status == 1) {
        $("#errorMessage").removeClass("d-none");
        $("#errorMessage").text(response.message);
      } else if (response.status == 200) {
        $("#errorMessage").addClass("d-none");
        $("#TransaksiAddModal").modal("hide");
        $("#SaveTransaksi")[0].reset();

        location.reload();
      }
    },
  });
  return false;
});

$(document).on("click", ".EditTransaksiBtn", function () {
  var id_transaksi = $(this).val();
  // alert(id_transaksi);
  $.ajax({
    type: "GET",
    url: "backend/transaksi/get_data.php?id_transaksi=" + id_transaksi,
    dataType: "json",
    success: function (response) {
      if (response.status == 1) {
        alert(response.message);
      } else if (response.status == 200) {
        var data = response.data;
        $("#id_transaksi").val(data.id_transaksi);
        $("#view_id_member").val(data.id_member);
        $("#view_tanggal_transaksi").val(data.tanggal_transaksi);
        $("#view_total_harga").val(data.total_harga);
        $("#view_tanggal_bayar").val(data.tanggal_bayar);
        $("#view_keterangan").val(data.keterangan);
        $("#view_status").val(data.status);

        $("#TransaksiEditModal").modal("show");
      }
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    },
  });
});

$(document).on("submit", "#UpdateTransaksi", function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  formData.append("edit_transaksi", true);

  $.ajax({
    type: "POST",
    url: "backend/function.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.status == 1) {
        $("#errorMessageUpdate").removeClass("d-none");
        $("#errorMessageUpdate").text(response.message);
      } else if (response.status == 200) {
        $("#errorMessageUpdate").addClass("d-none");
        $("#TransaksiEditModal").modal("hide");
        $("#UpdateTransaksi")[0].reset();

        location.reload();
      }
    },
  });
});

$(document).on("click", ".DeleteTransaksiBtn", function (e) {
  e.preventDefault();
  if (confirm("Apakah Anda Yakin Ingin Menghapus Transaksi Ini?")) {
    var id_transaksi = $(this).val();
    $.ajax({
      type: "POST",
      url: "backend/function.php",
      data: {
        delete_transaksi: true,
        id_transaksi: id_transaksi,
      },
      success: function (response) {
        if (response.status == 1) {
          alert(response.message);
        } else {
          alert(response.message);

          location.reload();
        }
      },
    });
  }
});

// Halaman Pengguna
// $(document).ready(function () {
//   $("#myInput").on("keyup", function () {
//     var value = $(this).val().toLowerCase();
//     $(".cari").filter(function () {
//       $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
//     });
//   });
// });

$(document).on("submit", "#SavePengguna", function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  formData.append("insert_pengguna", true);

  $.ajax({
    type: "POST",
    url: "backend/function.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.status == 1) {
        $("#errorMessage").removeClass("d-none");
        $("#errorMessage").text(response.message);
      } else if (response.status == 200) {
        $("#errorMessage").addClass("d-none");
        $("#PenggunaAddModal").modal("hide");
        $("#SavePengguna")[0].reset();

        location.reload();
      }
    },
  });
});

$(document).on("click", ".EditPenggunaBtn", function () {
  var id_user = $(this).val();
  // alert(id);
  $.ajax({
    type: "GET",
    url: "backend/pengguna/get_data.php?id_user=" + id_user,
    dataType: "json",
    success: function (response) {
      if (response.status == 1) {
        alert(response.message);
      } else if (response.status == 200) {
        var data = response.data;
        $("#id_user").val(data.id_user);
        $("#view_username").val(data.username);
        $("#view_role").val(data.role);
        $("#view_member").val(data.id_member);

        $("#PenggunaEditModal").modal("show");
      }
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    },
  });
});

$(document).on("submit", "#UpdatePengguna", function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  formData.append("edit_pengguna", true);

  $.ajax({
    type: "POST",
    url: "backend/function.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.status == 1) {
        $("#errorMessageUpdate").removeClass("d-none");
        $("#errorMessageUpdate").text(response.message);
      } else if (response.status == 200) {
        $("#errorMessageUpdate").addClass("d-none");
        $("#PenggunaEditModal").modal("hide");
        $("#UpdatePengguna")[0].reset();

        location.reload();
      }
    },
  });
});

$(document).on("click", ".DeletePenggunaBtn", function (e) {
  e.preventDefault();
  if (confirm("Apakah Anda Yakin Ingin Menghapus Pengguna Ini?")) {
    var id_user = $(this).val();
    $.ajax({
      type: "POST",
      url: "backend/function.php",
      data: {
        delete_pengguna: true,
        id_user: id_user,
      },
      success: function (response) {
        if (response.status == 1) {
          alert(response.message);
        } else {
          alert(response.message);

          location.reload();
        }
      },
    });
  }
});

// Halaman Pelanggan
// $(document).ready(function () {
//   $("#myInput").on("keyup", function () {
//     var value = $(this).val().toLowerCase();
//     $(".cari").filter(function () {
//       $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
//     });
//   });
// });

$(document).on("submit", "#SavePelanggan", function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  formData.append("insert_member", true);

  $.ajax({
    type: "POST",
    url: "backend/function.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.status == 1) {
        $("#errorMessage").removeClass("d-none");
        $("#errorMessage").text(response.message);
      } else if (response.status == 200) {
        $("#errorMessage").addClass("d-none");
        $("#PelangganAddModal").modal("hide");
        $("#SavePelanggan")[0].reset();

        location.reload();
      }
    },
  });
});

$(document).on("click", ".EditPelangganBtn", function () {
  var id_member = $(this).val();
  // alert(id);
  $.ajax({
    type: "GET",
    url: "backend/pelanggan/get_data.php?id_member=" + id_member,
    dataType: "json",
    success: function (response) {
      if (response.status == 1) {
        alert(response.message);
      } else if (response.status == 200) {
        var data = response.data;
        $("#id_member").val(data.id_member);
        $("#view_nama_member").val(data.nama_member);
        $("#view_alamat_member").val(data.alamat_member);
        $("#view_telepon_member").val(data.telepon_member);
        $("#view_email_member").val(data.email_member);

        $("#PelangganEditModal").modal("show");
      }
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    },
  });
});

$(document).on("submit", "#UpdatePelanggan", function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  formData.append("edit_member", true);

  $.ajax({
    type: "POST",
    url: "backend/function.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.status == 1) {
        $("#errorMessageUpdate").removeClass("d-none");
        $("#errorMessageUpdate").text(response.message);
      } else if (response.status == 200) {
        $("#errorMessageUpdate").addClass("d-none");
        $("#PelangganEditModal").modal("hide");
        $("#UpdatePelanggan")[0].reset();

        location.reload();
      }
    },
  });
});

$(document).on("click", ".DeletePelangganBtn", function (e) {
  e.preventDefault();
  if (confirm("Apakah Anda Yakin Ingin Menghapus Pelanggan Ini?")) {
    var id_member = $(this).val();
    $.ajax({
      type: "POST",
      url: "backend/function.php",
      data: {
        delete_member: true,
        id_member: id_member,
      },
      success: function (response) {
        if (response.status == 1) {
          alert(response.message);
        } else {
          alert(response.message);

          location.reload();
        }
      },
    });
  }
});

// Halaman Toko
// $(document).ready(function () {
//   $("#myInput").on("keyup", function () {
//     var value = $(this).val().toLowerCase();
//     $(".cari").filter(function () {
//       $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
//     });
//   });
// });

$(document).on("submit", "#SaveToko", function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  formData.append("insert_toko", true);

  $.ajax({
    type: "POST",
    url: "backend/function.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.status == 1) {
        $("#errorMessage").removeClass("d-none");
        $("#errorMessage").text(response.message);
      } else if (response.status == 200) {
        $("#errorMessage").addClass("d-none");
        $("#TokoAddModal").modal("hide");
        $("#SaveToko")[0].reset();

        location.reload();
      }
    },
  });
});

$(document).on("click", ".EditTokoBtn", function () {
  var id_toko = $(this).val();
  //   alert(id_toko);
  $.ajax({
    type: "GET",
    url: "backend/toko/get_data.php?id_toko=" + id_toko,
    dataType: "json",
    success: function (response) {
      if (response.status == 1) {
        alert(response.message);
      } else if (response.status == 200) {
        var data = response.data;
        $("#id_toko").val(data.id_toko);
        $("#view_nama_toko").val(data.nama_toko);
        $("#view_alamat_toko").val(data.alamat_toko);
        $("#view_telepon_toko").val(data.telepon_toko);
        $("#view_nama_pemilik").val(data.nama_pemilik);

        $("#TokoEditModal").modal("show");
      }
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    },
  });
});

$(document).on("submit", "#UpdateToko", function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  formData.append("edit_toko", true);

  $.ajax({
    type: "POST",
    url: "backend/function.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.status == 1) {
        $("#errorMessageUpdate").removeClass("d-none");
        $("#errorMessageUpdate").text(response.message);
      } else if (response.status == 200) {
        $("#errorMessageUpdate").addClass("d-none");
        $("#TokoEditModal").modal("hide");
        $("#UpdateToko")[0].reset();

        location.reload();
      }
    },
  });
});

$(document).on("click", ".DeleteTokoBtn", function (e) {
  e.preventDefault();
  if (confirm("Apakah Anda Yakin Ingin Menghapus Toko Ini?")) {
    var id_toko = $(this).val();
    $.ajax({
      type: "POST",
      url: "backend/function.php",
      data: {
        delete_toko: true,
        id_toko: id_toko,
      },
      success: function (response) {
        if (response.status == 1) {
          alert(response.message);
        } else {
          alert(response.message);

          location.reload();
        }
      },
    });
  }
});

// Halaman Laporan
$(document).ready(function () {
  var table = $("#export").DataTable({
    scrollX: true,
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excel",
        text: '<i class="bi bi-filetype-xlsx"></i>',
        className: "btn btn-success",
        title: function () {
          return "Laporan Transaksi - " + formatFilterTitle($("#filter").val());
        },
      },
      {
        extend: "pdf",
        text: '<i class="bi bi-filetype-pdf"></i>',
        className: "btn btn-danger",
        title: function () {
          return "Laporan Transaksi - " + formatFilterTitle($("#filter").val());
        },
      },
      {
        extend: "print",
        text: '<i class="bi bi-printer"></i>',
        className: "btn btn-secondary",
        title: function () {
          return "Laporan Transaksi - " + formatFilterTitle($("#filter").val());
        },
      },
    ],
    ajax: {
      url: "backend/laporan/get_data.php",
      type: "GET",
      dataType: "json",
      dataSrc: "",
      data: function (d) {
        var filterValue = $("#filter").val();
        if (filterValue) {
          d.filter = filterValue;
        }
      },
    },
    columns: [{ data: "no" }, { data: "tanggal_transaksi" }, { data: "nama_member" }, { data: "total_harga" }, { data: "status" }],
    initComplete: function () {
      $("#loading").hide();
    },
  });

  $("#filter").on("change", function () {
    $("#loading").show();
    table.ajax.reload(function () {
      $("#loading").hide();
    });
  });

  function formatFilterTitle(filterValue) {
    var selectedDate = new Date(filterValue + "-01");
    var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    return monthNames[selectedDate.getMonth()] + " " + selectedDate.getFullYear();
  }
});
