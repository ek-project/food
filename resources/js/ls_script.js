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
  email.on("input", checkPasswords);
  password1.on("input", checkPasswords);
  password2.on("input", checkPasswords);

  function checkPasswords() {
      // Check if the passwords match
      if ((password1.val() === password2.val()) && (password1.val().length >= 8 && password1.val().length <= 16) && (emailRegex.test(email.val()))) {
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

$("#name, #email, #pwd1, #pwd2").on("input", function() {
    $("#error-message").text('').hide();
});

$("#submit-btn").click(function(e) {
    e.preventDefault();
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
                                    if (response === "Record added successfully!") {
                                        $("#success-message").text(response).show();
                                        $(".contact-form")[0].reset();
                                        window.location.href = "login.html"

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
        $("#error-message").text("Error: All fields are required. Ensure you check th terms & conditions.");
    }
});

