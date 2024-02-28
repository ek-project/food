$('.small').click(function() {
    var icon = $(this);
    var password = $('#pwd2');

    password.attr('type', password.attr('type') === 'password' ? 'text' : 'password');

    if (icon.attr("name") == "eye-off-outline") {
        icon.attr("name", "eye-outline");
    } else {
        icon.attr("name", "eye-off-outline");
    }

});

$(document).ready(function() {
  const password1 = $("#pwd1");
  const password2 = $("#pwd2");
  const submitBtn = $("#submit-btn");

  // Enable the button initially
  submitBtn.prop("disabled", false);

  // Add event listeners to both password fields
  password1.on("input", checkPasswords);
  password2.on("input", checkPasswords);

  function checkPasswords() {
      // Check if the passwords match
      if ((password1.val() === password2.val()) && (password1.val().length >= 8 && password1.val().length <= 16)) {
          // If they match, enable the button
          submitBtn.prop("disabled", false);
      } else if ((password1.val().length == 0) && (password2.val().length == 0)){
          submitBtn.prop("disabled", false);
      } else {
          // If they don't match, disable the button
          submitBtn.prop("disabled", true);
      }
  }

});
$("#submit-btn").click(function(e) {
    e.preventDefault();
    $("#success-message").text('').hide()
    $("#error-message").text('').hide()
    if ($("#name").val() && $("#email").val() && $("#pwd1").val() && $("#pwd2").val()) {
        if ($("#pwd1").val() === $("#pwd2").val()) {
            $.ajax({
                url: "resources/php/signup.php",
                type: "POST",
                data: $(".contact-form").serialize(),
                success: function(response) {
                    if (response === "Record added successfully!") {
                        $("#success-message").text(response).show();
                        $(".contact-form")[0].reset();
                    } else {
                        $("#error-message").text(response).show();
                        $(".contact-form")[0].reset();
                    }
                }
            });
        } else {
            $("#error-message").text("Error: Passwords do not match.");
        }
    } else {
        $("#error-message").text("Error: All fields are required.");
    }
});