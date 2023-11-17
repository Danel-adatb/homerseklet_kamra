function openConfirmationModal(userId, action) {
  if(action == 'delete') {
    if (confirm('Biztos benne, hogy törölni szeretné ezt a felhasználót?')) {
      $.ajax({
        url: 'modify_user.php',
        type:'POST',
        data:{action:action, id:userId},
        success: function(data){
             alert(data);
             location.reload();
         }
  
        });
    }
  } else if(action == 'update') {
    $.ajax({
      url: 'modify_user.php',
      type:'POST',
      data:{action:action, id:userId},
      success: function(data){
           alert(data);
           location.reload();
       }

      });
  }
}

var delButtons = document.querySelectorAll('.delete-button');
delButtons.forEach(function (button) {
  button.addEventListener('click', function () {
      var userId = this.id;
      var action = 'delete';
      openConfirmationModal(userId, action);
  });
});

var delButtons = document.querySelectorAll('.update-button');
delButtons.forEach(function (button) {
  button.addEventListener('click', function () {
      var userId = this.id;
      var action = 'update';
      openConfirmationModal(userId, action);
  });
});