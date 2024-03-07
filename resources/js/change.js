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