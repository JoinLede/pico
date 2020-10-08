jQuery(document).ready(function( $ ) {
  $('.open-modal').click(function() {
    $('#modal-container').fadeIn(200);
    $('#modal').fadeIn(200);
    $('#modal-container.modal-disconnect').css('display', 'block');
    $('#modal-container.modal-disconnect .modal#alert').css('display', 'block');
  });

  $('.close-alert').click(function() {
    $('#modal').fadeOut(200);
    $('#modal-container').fadeOut(200);
    $('#modal-container.modal-disconnect').css('display', 'none');
    $('#modal-container.modal-disconnect .modal#alert').css('display', 'none');
  });
  $('.close-modal').click(function() {
    $('#modal').fadeOut(200);
    $('#modal-container').fadeOut(200);
    $('#modal-container.modal-disconnect').css('display', 'none');
    $('#modal-container.modal-disconnect .modal#alert').css('display', 'none');
  });
});
