$(document).ready(function() {
  $('#board_name').parent().parent().parent().append('<div class="help-inline" id="board-name-alert" style="display:none;">please use only letters, numbers, - and _</div>');
  $('#board_name').focus();
  $('#board_name').change(function(event) { checkBoardName(); });
  $('#board_name').keyup(function(event) { checkBoardName(); });
  $('#board_name').keypress(function(event) { if(event.keyCode == 13 && !checkBoardName()) { event.preventDefault(); }});
  checkBoardName();
});

function checkBoardName() {
  var boardName = $('#board_name').val();
  var readyForSubmit = false;

  if(boardName=="") {
    $('#send').removeAttr('disabled');
    $('#board-name-alert').hide();
  } else if(isValidBoardName(boardName)) {
    $('#send').removeAttr('disabled');
    $('#board-name-alert').hide();
    readyForSubmit = true;
  } else {
    $('#send').attr('disabled','disabled');
    $('.help-inline').hide();
    $('#board-name-alert').show();
  }

  return readyForSubmit;
}

function isValidBoardName(boardName) {
  var boardNameRegexp = /^([a-zA-Z0-9-_\.])*$/;
  return (boardName!="") && boardNameRegexp.test(boardName);
}
