$("#email").on("input", function() {
    $("#error-message").text('').hide();
});

$(document).ready(function() {
  var email = $('#email');
  var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  var submitBtn = $("#submit-btn"); // Add this line to declare the submitBtn variable

  // Enable the button initially
  submitBtn.prop("disabled", false);

  // Add event listeners to email fields
  email.on("input", checkemail);

  function checkemail() {
      if (emailRegex.test(email.val())) { // Fix the usage of email variable
          // If they match, enable the button
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
    $(".otpverify").hide();

    if ($("#email").val()) {
        $.ajax({
            url: "resources/php/forgot.php",
            type: "POST",
            data: $(".contact-form").serialize(),
            success: function(response) {
                if (response === "OTP has been sent to your email") {
                    $("#success-message").text(response).show();
                    $(".otpverify").show();
                    $("#verify-btn").click(function(e) {
                        e.preventDefault();
                        $("#success-message").text('').hide()
                        $("#error-message").text('').hide()
                    
                        if ($("#otp").val()) {
                            $.ajax({
                                url: "resources/php/forgot.php", // replace with the URL of your verification script
                                type: "POST",
                                data: { otp: $("#otp").val() },
                                success: function(response) {
                                    if (response === "OTP is valid.") {
                                        $("#success-message").text(response).show();
                                        // You can add code here to proceed after successful verification
                                    } else {
                                        $("#error-message").text(response).show();
                                        $(".otpverify")[0].reset();
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
        $("#error-message").text("Error: All fields are required.").show();
    }
});