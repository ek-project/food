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
  const submitBtn = $("#submit-btn");

  // Enable the button initially
  submitBtn.prop("disabled", false);

  // Add event listeners to both password fields
  email.on("focusout", checkPasswords);
  password1.on("focusout", checkPasswords);
  password2.on("input", checkPasswords);

  function checkPasswords() {
    // Check if the passwords match
    if (emailRegex.test(email.val())) {
        // If they match, enable the button
        if ((password1.val().length >= 8 && password1.val().length <= 16)) {
            $("#pwd2").prop("disabled", false);
            if (password1.val() === password2.val()) {
                submitBtn.prop("disabled", false);
                $("#error-message").text('').hide();
            } else if ((password1.val().length == 0) && (password2.val().length == 0)){
                $("#error-message").text('').hide();
                submitBtn.prop("disabled", false);
            } else {
                // If they don't match, disable the button
                submitBtn.prop("disabled", true);
                $("#error-message").text('Passwords do not match').show();
            }
        } else {
            // If they don't match, disable the button
            $("#pwd2").prop("disabled", true);
            $("#pwd2").val('');
            submitBtn.prop("disabled", true);
            $("#error-message").text('Password should be around 8 to 16 characters.').show();
        }
    } else if (!email.val()) {
        submitBtn.prop("disabled", true);
        $("#error-message").text('Email address is required').show();
    } else {
        submitBtn.prop("disabled", true);
        $("#error-message").text('Invalid email address').show();
    }
}

});

$("#name, #email, #pwd1, #pwd2, #otp").on("input", function() {
    $("#error-message").text('').hide();
    $("#error1-message").text('').hide();
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

