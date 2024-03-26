// Handler for first input field
$('#icon1').click(function() {
    var password = $('#pwd1');
    password.attr('type', password.attr('type') === 'password' ? 'text' : 'password');
    this.name = this.name == "eye-off-outline" ? "eye-outline" : "eye-off-outline";
});

// Handler for second input field
$('#icon2').click(function() {
    var password = $('#pwd3');
    password.attr('type', password.attr('type') === 'password' ? 'text' : 'password');
    this.name = this.name == "eye-off-outline" ? "eye-outline" : "eye-off-outline";
});

$(document).ready(function() {
    const password1 = $("#pwd1");
    const password2 = $("#pwd2");
    const password3 = $("#pwd3");
    const submitBtn = $("#submit-btn");
  
    // Enable the button initially
    submitBtn.prop("disabled", false);
  
    // Add event listeners to both password fields
    password1.on("input", checkPasswords);
    password2.on("input", checkPasswords);
  
    function checkPasswords() {
        // Check if the passwords match
        if ((password2.val() === password3.val()) && (password2.val().length >= 8 && password2.val().length <= 16)) {
            // If they match, enable the button
            submitBtn.prop("disabled", false);
        } else if ((password2.val().length == 0) && (password3.val().length == 0)){
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

    if ($("#pwd1").val() && $("#pwd2").val() && $("#pwd3").val()) {
        $.ajax({
            url: "resources/php/change.php",
            type: "POST",
            data: $(".contact-form").serialize(),
            success: function(response) {
                if (response === "Password updated successfully") {
                    $("#success-message").text(response).show();
                    $(".contact-form")[0].reset();
                    window.location.href = "login.html"
                } else {
                    $("#error-message").text(response).show();
                    $(".contact-form")[0].reset();
                }
            }
        });
    }else {
        $("#error-message").text("Error: All fields are required.").show();
    }
});