
$(document).ready(function() {
  const usernameInput = $("#username");
  const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

  usernameInput.on("input", function() {
    const inputValue = $(this).val();

    if (emailRegex.test(inputValue)) {
      // The entered value is an email address
      // You can now proceed to check if the email address exists in the database
      // No need to change the type attribute
      console.log(inputValue + " is an email address");
    } else {
      // The entered value is not an email address
      // You can now proceed to check if the username exists in the database
      // No need to change the type attribute
      console.log(inputValue + " is a username");
    }
  });
});

$('.small').click(function() {
  var icon = $(this);
  var password = $('#pwd');

  password.attr('type', password.attr('type') === 'password' ? 'text' : 'password');

  if (icon.attr("name") == "eye-off-outline") {
      icon.attr("name", "eye-outline");
  } else {
      icon.attr("name", "eye-off-outline");
  }

});

$(document).ready(function() {
  const password1 = $("#pwd");
  const username = $("#username"); // Assuming the id of the username field is 'username'
  const submitBtn = $("#submit-btn");

  // Enable the button initially
  submitBtn.prop("disabled", false);

  // Add event listeners to both input fields
  password1.on("input", checkInputs);
  username.on("input", checkInputs);

  function checkInputs() {
      // Check if both fields are not empty
      if ((password1.val() !== "" && username.val() !== "") || (password1.val() === "" && username.val() === ""))  {
          // If they are not empty, enable the button
          submitBtn.prop("disabled", false);
      } else {
          // If any of them is empty, disable the button
          submitBtn.prop("disabled", true);
      }
  }

});

$("#username, #pwd").on("input", function() {
  $("#error-message").text('').hide();
});


$("#submit-btn").click(function(e) {
  e.preventDefault();
  $("#success-message").text('').hide()
  $("#error-message").text('').hide()

  if ($("#username").val() && $("#pwd").val()){
    $.ajax({
      url: "resources/php/login.php",
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
                          url: "resources/php/login.php", // replace with the URL of your verification script
                          type: "POST",
                          data: { otp: $("#otp").val() },
                          success: function(response) {
                              if (response === "OTP is valid.") {
                                  $("#success-message").text(response).show();
                                  $(".contact-form")[0].reset();
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
      $("#error-message").text("Error: All fields are required.").show();
  }
});