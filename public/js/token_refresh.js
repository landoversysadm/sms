let csrfToken = $('[name="csrf_token"]').attr('content');

function refreshToken() {
  $.get('refresh-csrf')
    .done((data) => {
      csrfToken = data; // the new token
    })
    .catch((e) => {
      //
    });
}

setInterval(refreshToken, 36000);
