$("#otp").on("input", function() {
    $("#error-message").text('').hide();
});

$('.free-button').click(function(e) {
    e.preventDefault();

    // Disable the button
    $(this).prop('disabled', true);

    var paymentOption;
    var paymentData;

    // Loop through all visible buttons with the .btn-light class
    $(".btn-light:visible").each(function() {
        if ($(this).hasClass('collapsed')) {
            // Skip collapsed buttons
            return true;
        } else {
            // Store the data-payment attribute of the visible, expanded button
            paymentOption = $(this).data("payment");
            return false; // Exit the loop once a visible, expanded button is found
        }
    });

    if (!paymentOption) {
        alert("Please select a payment option.");
        return;
    }

    switch(paymentOption) {
        case 'upi':
            paymentData = $("#upi").val();
            if (!paymentData) {
                alert("Please enter your UPI ID.");
                $(this).prop('disabled', false);
                return;
            }
            break;
        case 'ccn':
            paymentData = $("#ccn").val();
            pd1 = $("#ccn1").val();
            pd2 = $("#ccn2").val();
            if (!paymentData || !pd1 || !pd2) {
                alert("Please fill card details.");
                $(this).prop('disabled', false);
                return;
            }
            break;
        case 'paypal':
            paymentData = $("#paypal").val();
            if (!paymentData) {
                alert("Please enter your paypal ID.");
                $(this).prop('disabled', false);
                return;
            }
            break;
        default:
            alert("Invalid payment option.");
            $(this).prop('disabled', false);
            return;
    }

    console.log(paymentData);

    var fullName = $("#fn").val();
    var phoneNumber = $("#ph").val();
    var streetAddress = $("#sa").val();
    var city = $("#ct").val();
    var state = $("#st").val();
    var pinCode = $("#pin").val();

    if (!fullName || !phoneNumber || !streetAddress || !city || !state || !pinCode) {
        alert("Please fill in all fields.");
        $(this).prop('disabled', false);
        return;
    }

    $.ajax({
        url: 'resources/php/billing.php',
        type: 'post',
        data: { 
            paymentData: paymentData, 
            fullName: fullName, 
            phoneNumber: phoneNumber, 
            streetAddress: streetAddress, 
            city: city, 
            state: state, 
            pinCode: pinCode
        },
        success: function(response) {
            if (response === "OTP has been sent to your email") {
                $("#success-message").text(response).show();
                $('#otp-card').show();

                var timeLeft = 120; // 2 minutes in seconds
                var timerElement = $("#timer"); // replace with the ID of your timer element

                timerElement.text("Time left: 2:00");

                // Disable the "Send OTP" button
                $(".free-button").prop("disabled", true);

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
                        $(".free-button").prop("disabled", false);
                    }
                }, 1000);

                $("#submit-btn").click(function(e) {
                    e.preventDefault();
                
                    var otp = $("#otp").val();
                
                    console.log(otp);
                
                
                
                    // Call your server-side script to verify the OTP
                    if ($("#otp").val()){
                        $.ajax({
                            url: "resources/php/billing.php",
                            type: "post",
                            data: { otp: otp },
                            success: function(response) {
                                if (response === "Your Order Successfully Placed") {
                                    $("#success-message").text(response).show();
                                    setTimeout(function() {
                                        window.location.href = "index2s.php";
                                    }, 5000);
                                    $(".contact-form")[0].reset();
                                } else {
                                    $("#error-message").text(response).show();
                                }
                            }
                        });
                    } else {
                        $("#error-message").text("Error: Please enter the OTP.").show();
                    }
                });

            } else {
                $("#error-message").text(response).show();
                $(this).prop('disabled', false);
                $(".contact-form")[0].reset();
            }
        }
    });
});

