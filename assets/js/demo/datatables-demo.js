// Call the dataTables jQuery plugin
$(document).ready(function () {
  $("#dataTable").DataTable();
  $(".dataTableHasil").DataTable({
    dom:
      "<'row'<'col-md-12'B>>" +
      "<'row'<'col-md-6'l><'col-md-6'f>>" +
      "<'row'<'col-md-12'tr>>" +
      "<'row'<'col-md-5'i><'col-md-7'p>>",
    order: [2, "asc"],
    buttons: [
      {
        extend: "pdf",
        text: "Cetak Data",
        className: "btn btn-primary mb-3 d-flex justify-content-end",
        title:
          "Data Hasil Akhir - Sistem Penunjang Keputusan Metode AHP TOPSIS",
        customize: function (doc) {
          doc.content[1].table.widths = Array(
            doc.content[1].table.body[0].length + 1
          )
            .join("*")
            .split("");
        },
      },
    ],
  });
});
