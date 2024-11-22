$(function () {
  $('.dataTable').on('click', '.delete-action-datatable', function (e) {
    e.preventDefault();

    const tableId = $(this).closest('table').attr('id');
    const url = $(this).attr('href');

    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
        cancelButton: 'btn btn-outline-secondary waves-effect',
      },
      buttonsStyling: false,
      showLoaderOnConfirm: true,
      preConfirm: async () => {
        return $.ajax({
          type: 'DELETE',
          url,
          success: function (response, statusJQ, xhr) {
            const { status } = response;

            if (status) {
              window.LaravelDataTables[tableId].ajax.reload();

              Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'Item has been deleted.',
                customClass: {
                  confirmButton: 'btn btn-success waves-effect',
                },
              });
            }
          },
          error: function (xhr, status, error) {
            if (xhr.status === 419) {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Your session has expired.',
                customClass: {
                  confirmButton: 'btn btn-primary waves-effect waves-light',
                },
                buttonsStyling: false,
              }).then(result => location.reload());
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Unexpected error.',
                customClass: {
                  confirmButton: 'btn btn-primary waves-effect waves-light',
                },
                buttonsStyling: false,
              });
            }
          },
        });
      },
      allowOutsideClick: () => !Swal.isLoading(),
    });
  });
});
