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
  const email = $('#email');
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  // Password policy: at least one uppercase letter, one lowercase letter, one special character, and one number
  var passwordPolicy = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?])[A-Za-z\d!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{8,16}$/;
  const submitBtn = $("#submit-btn");

  // Enable the button initially
  submitBtn.prop("disabled", false);

  // Add event listeners to both password fields
  email.on("focusout", checkPasswords);
  password1.on("focusout", checkPasswords);
  password2.on("input", checkPasswords);

  password1.on("paste", function(e) {
    e.preventDefault();
});
password2.on("paste", function(e) {
    e.preventDefault();
});

function checkPasswords() {

    if (!email.val()) {
        submitBtn.prop("disabled", true);
        $("#error-message").text('Email address is required').show();
        return;
    }

    if (!emailRegex.test(email.val())) {
        $("#pwd2").prop("disabled", true);
        $("#pwd1").prop("disabled", true);
        submitBtn.prop("disabled", true);
        $("#error-message").text('Invalid email address').show();
        return;
    }

    $("#pwd1").prop("disabled", false);

    if (!passwordPolicy.test(password1.val())) {
        $("#pwd2").prop("disabled", true);
        submitBtn.prop("disabled", true);
        $("#error-message").text('Password should be 8 to 16 characters, with at least one uppercase letter, one lowercase letter, one special character, and one number.').show();
        return;
    }

    $("#pwd2").prop("disabled", false);

    if (password1.val() === password2.val()) {
        submitBtn.prop("disabled", false);
        $("#error-message").text('').hide();
    } else if (password1.val().length == 0 && password2.val().length == 0) {
        $("#error-message").text('').hide();
        submitBtn.prop("disabled", false);
    } else {
        submitBtn.prop("disabled", true);
        $("#error-message").text('Passwords do not match').show();
    }
}

});

$("#email, #pwd1, #pwd2, #otp").on("input", function() {
    $("#error-message").text('').hide();
});



$("#name").on("input",function() {
    var input=$(this);
    var username = input.val();

    if (username === "") {
        $("#error1-message").text('').hide();
        $("#submit-btn").prop('disabled', false);
        return;
    }

    
    var re = /^[a-z]+$/i;
    var re1 = /^[a-z0-9]+$/i;
    var re2 = /^[0-9]+$/;
    var is_alphanumeric=re1.test(input.val());
    var is_alphabetic=re.test(input.val());
    var is_numeric=re2.test(input.val());
    if((is_alphabetic || is_alphanumeric) && !is_numeric){

        $.ajax({
            url: "resources/php/signup.php", // replace with the URL of your script that checks if a username is available
            type: "POST",
            data: { name: username },
            success: function(response) {
                if (response === "Username already taken. Please choose a different one.") {
                    $("#error1-message").text(response).show();
                    $("#submit-btn").prop('disabled', true);
                } else {
                    $("#error1-message").text('').hide();
                    $("#submit-btn").prop('disabled', false);
                }
            }
        });
    }else{
        $("#error1-message").text('Username should be Alphabetic or Alphanumeric only').show();
        $("#submit-btn").prop('disabled', true);
    }
});

$(document).ready(function() {
    $("#submit-btn").click(function(e) {
        e.preventDefault();
    
        // Disable the button
        $(this).prop('disabled', true);
        
        $("#success-message").text('').hide()
        $("#error-message").text('').hide()

        // Check if any error message is visible
        if ($("#error-message").is(":visible") || $("#error1-message").is(":visible")) {
            // If any error message is visible, alert the user and stop the form submission
            alert("Please correct the errors before submitting the form.");
            $(this).prop('disabled', false); // Enable the button again
            return;
        }
    
        if ($("#name").val() && $("#email").val() && $("#pwd1").val() && $("#pwd2").val() && ($("#agree").is(":checked"))) {
            $.ajax({
                url: "resources/php/signup.php",
                type: "POST",
                data: $(".contact-form").serialize(),
                success: function(response) {
                    if (response === "OTP has been sent to your email") {
                        $("#success-message").text(response).show();
                        $(".otpverify").show();
    
                        $("#otp").on("input", function() {
                            $("#error-message").text('').hide();
                        });
                        
                        var timeLeft = 120; // 2 minutes in seconds
                        var timerElement = $("#timer"); // replace with the ID of your timer element
    
                        timerElement.text("Time left: 2:00");
    
                        // Disable the "Send OTP" button
                        $("#submit-btn").prop("disabled", true);
    
                        var timerInterval = setInterval(function() {
                            timeLeft--;
                            var minutes = Math.floor(timeLeft / 60);
                            var seconds = timeLeft % 60;
    
                            if (seconds < 10) {
                                seconds = "0" + seconds;
                            }
    
                            timerElement.text("Time left: " + minutes + ":" + seconds);
    
                            if (timeLeft <= 0) {
                                clearInterval(timerInterval);
                                timerElement.text("Time's up!");
    
                                // Enable the "Send OTP" button
                                $("#submit-btn").prop("disabled", false);
                            }
                        }, 1000);
    
                        $("#verify-btn").click(function(e) {
                            e.preventDefault();
                            $("#success-message").text('').hide()
                            $("#error-message").text('').hide()
                        
                            if ($("#otp").val()) {
                                $.ajax({
                                    url: "resources/php/signup.php", // replace with the URL of your verification script
                                    type: "POST",
                                    data: { otp: $("#otp").val()},
                                    success: function(response) {
                                        if (response === "UserID has been sent to your email") {
                                            $("#success-message").text(response).show();
                                            setTimeout(function() {
                                                window.location.href = "login.html";
                                            }, 3000);
                                            $(".contact-form")[0].reset();
    
                                            // You can add code here to proceed after successful verification
                                        } else {
                                            $("#error-message").text(response).show();
                                            $("#otp").val('');
                                        }
                                    }
                                });
                            } else {
                                $("#error-message").text("Error: Please enter the OTP.").show();
                            }
                        });
                    } else {
                        $("#error-message").text(response).show();
                        $(".contact-form")[0].reset();
                    }
                }
            });
        }else {
            $("#error-message").text("Error: All fields are required. Ensure you check the terms & conditions.").show();
            $(this).prop('disabled', false);
        }
    });
});

window.onpageshow = function(event) {
    if (event.persisted) {
        // Reset the form
        $(".contact-form")[0].reset();
    }
};

