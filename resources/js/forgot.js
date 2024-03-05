$("#email").on("input", function() {
    $("#error-message").text('').hide();
  });
  
  
  $("#submit-btn").click(function(e) {
    e.preventDefault();
    $("#success-message").text('').hide()
    $("#error-message").text('').hide()
  
    if ($("#email").val()){
      $.ajax({
        url: "resources/php/forgot.php",
        type: "POST",
        data: $(".contact-form").serialize(),
        success: function(response) {
            if (response === "Login Successful") {
                $("#success-message").text(response).show();
                $(".contact-form")[0].reset();
            } else {
                $("#error-message").text(response).show();
                $(".contact-form")[0].reset();
            }
        }
    });
    }else {
        $("#error-message").text("Error: All fields are required.");
    }
  });