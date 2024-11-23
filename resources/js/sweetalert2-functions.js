$(function () {
  window.alertYesNo = (
    title,
    text,
    width = 400,
    icon = 'question',
    confirmText = 'Aceptar',
    cancelText = 'Cancelar',
    confirmColor = '#003F77',
    cancelColor = '#696969',
  ) => {
    return new Promise(resolve => {
      Swal.fire({
        title: title,
        html: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: cancelColor,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        width: width,
        customClass: {
          actions: 'actions-swalalert2',
        },
      }).then(result => {
        if (result.isConfirmed) {
          resolve(true);
        } else {
          resolve(false);
        }
      });
    });
  };

  window.statusAlert = (title, icon = 'success', timer = 800, position = 'top-end') => {
    Swal.fire({
      position: position,
      icon: icon,
      title: title,
      showConfirmButton: false,
      timer: timer,
    });
  };

  window.simpleAlert = (title, text, icon = 'success') => {
    Swal.fire({
      title: title,
      html: text,
      icon: icon,
      confirmButtonText: 'Aceptar',
      confirmButtonColor: '#003F77',
    });
  };

  window.loginSweetAlert = function (
    title,
    nameInputEmail,
    nameInputPassword,
    confirmButtonText,
    cancelButtonText,
    allowOutsideClick,
    showLoaderOnConfirm,
    ajaxType,
    urlAjax,
    dataAjax = [],
  ) {
    return new Promise((resolve, reject) => {
      let alert;

      Swal.fire({
        title: title,
        html:
          `<input id="${nameInputEmail}" type="email" class="swal2-input" placeholder="Correo electrónico">` +
          `<input id="${nameInputPassword}" class="swal2-input" placeholder="Contraseña" type="password">`,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        allowOutsideClick: allowOutsideClick,
        showLoaderOnConfirm: showLoaderOnConfirm,
        preConfirm: () => {
          const email = $(`#${nameInputEmail}`).val().trim();
          const password = $(`#${nameInputPassword}`).val();

          if (!email || !password) {
            let message = '';
            if (!email && !password) {
              message = 'Debe ingresar el correo y la contraseña';
            } else if (!email) {
              message = 'Debe ingresar el correo electrónico';
            } else {
              message = 'Debe ingresar la contraseña';
            }
            Swal.showValidationMessage(message);
            return false;
          }

          return [email, password];
        },
      })
        .then(result => {
          if (result.isConfirmed) {
            const email = result.value[0];
            const password = result.value[1];
            alert = result;
            // Ejecutar la solicitud AJAX
            $.ajax({
              type: `${ajaxType}`,
              url: $('meta[name="app-url"]').attr('content') + `${urlAjax}`,
              dataType: 'json',
              data: { email: email, password: password, params: dataAjax },
              success: function (response) {
                if (response.result === true) {
                  // Mostrar SweetAlert de éxito con el mensaje proporcionado por el servidor
                  Swal.fire('Éxito', response.message, 'success').then(() => {
                    // Recargar la página actual
                    location.reload();
                  });
                } else {
                  // Si la respuesta del servidor indica un error
                  // Mostrar SweetAlert de error con el mensaje proporcionado por el servidor
                  Swal.fire('Error', response.message, 'error').then(() => {
                    // Recargar la página actual
                    mostrarSweetAlert(
                      nameInputEmail,
                      nameInputPassword,
                      confirmButtonText,
                      cancelButtonText,
                      allowOutsideClick,
                      showLoaderOnConfirm,
                      ajaxType,
                      urlAjax,
                      dataAjax,
                    );
                  });
                }
              },
              error: function (xhr, status, error) {
                // Mostrar SweetAlert de error
                // Si ocurre un error en la solicitud AJAX
                // Mostrar SweetAlert de error genérico
                Swal.fire('Error', 'Hubo un error al procesar la solicitud', 'error');
              },
            });
          } else {
            reject(alert);
          }
        })
        .finally(() => {
          // Limpiar los campos
          $(`#${nameInputEmail}`).val('');
          $(`#${nameInputPassword}`).val('');
        });
    });
  };
});
