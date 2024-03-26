$(document).ready(function() {
    $('#height').on('input', function() {
        $('#height_output').text($(this).val());
    });
});

$(document).ready(function() {
    // Check the selected goal when the page loads
    var selectedGoal = $('#goal').find('option:selected').val();

    if (selectedGoal === 'wl') {
        $('#keto').show();
    } else {
        $('#keto').hide();
    }



    // Show or hide the "keto" option when the "goal" select box changes
    $('#goal').on('change', function() {
        selectedGoal = $(this).find('option:selected').val();

        if (selectedGoal === 'wl') {
            $('#keto').show();
        } else {
            $('#keto').hide();
        }
    });
});

$(document).ready(function() {
    // Set the initial label text
    var initialOption = $('#meal option:first').text();
    $('#choose').text('Choose your ' + initialOption + ' for each day!');

    var initialLimit = parseInt(initialOption[0]);
    limitCheckboxes(initialLimit);

    // Update the label text when the select box changes
    $('#meal').on('change', function() {
        var selectedOption = $(this).find('option:selected').text();
        $('#choose').text('Choose your ' + selectedOption + ' for each day!');

        // Uncheck all checkboxes and reset them when the selected option changes
        $('.choose input[type=checkbox]').prop('checked', false);


        // Hide the "choose" class if "4 meals" is selected
        if (selectedOption === '4 meals') {
            $('.choose input[type=checkbox]').prop('checked', true);
            $('.choose').hide();
        } else {
            $('.choose').show();
            var newLimit = parseInt(selectedOption[0]);
            limitCheckboxes(newLimit);
        }
    });

    function limitCheckboxes(limit) {
        $('.choose input[type=checkbox]').on('change', function(evt) {
            if($('.choose input[type=checkbox]:checked').length > limit) {
                $(this).prop('checked', false);
                alert('You can only select ' + limit + ' meal(s).');
            }
        });
    }

    function limitCheckboxes(limit) {
        $('.choose input[type=checkbox]').off('change').on('change', function(evt) {
            if($('.choose input[type=checkbox]:checked').length > limit) {
                $(this).prop('checked', false);
                alert('You can only select ' + limit + ' meal(s).');
            }
        });
    }
});

$(document).ready(function() {
    // Define the prices for each option
    var prices = {
        diet: {'keto': 215, 'balanced': 168, 'low': 148, 'glu':134 },
        sty: { 
            'keto': { 'veg': 215, 'nonveg': 233, 'egg': 215 },
            'balanced': { 'veg': 168, 'nonveg': 178, 'egg': 168 },
            'low': { 'veg': 148, 'nonveg': 158, 'egg': 148 },
            'glu': { 'veg': 134, 'nonveg': 144, 'egg': 134 }
        }
    };

    // Calculate the initial price
    var price = calculatePrice()

    // Update the price when the selected option in any select box changes
    $('#days, #meal, #diet, #sty').on('change', function() {
        price = calculatePrice()
        $('#price').text('₹ ' + price).show();

        $.ajax({
            url: 'resources/php/index2.php', // Replace this with the path to your PHP script
            type: 'POST',
            data: { price: price },
            success: function(data) {
                console.log('Server response:', data);
            },
            error: function(error) {
                console.log('Error:', error);
            }
        });
    });

    // Calculate the price based on the selected options
    function calculatePrice() {
        var selectedDays = $('#days').find('option:selected').val();
        if (selectedDays === '2w') {
            selectedDays = 14;
        } else if (selectedDays === "4w"){
            selectedDays = 28;
        } else {
            selectedDays = parseInt(selectedDays);
        }
        var selectedMeal = parseInt($('#meal').find('option:selected').val());
        var selectedDiet = $('#diet').find('option:selected').val();
        var selectedSty = $('#sty').find('option:selected').val();

        if (isNaN(selectedDays) || isNaN(selectedMeal)) {
            return 'Invalid selection';
        }

        return prices.sty[selectedDiet][selectedSty] * selectedDays * selectedMeal;
    }
});

$("#submit-btn").click(function(e) {
    e.preventDefault();
    $("#success-message").text('').hide()
    $("#error-message").text('').hide()

    var allFieldsFilled = true;
    $(".contact-form input").each(function() {
        if (!$(this).val()) {
            allFieldsFilled = false;
            return false; // Break out of the each loop
        }
    });
  
    if (allFieldsFilled){
      $.ajax({
        url: "resources/php/index2.php",
        type: "POST",
        data: $(".contact-form").serialize(),
        success: function(response) {
            if (response === "Redirecting to payment Page...") {
                $("#success-message").text(response).show();
                window.location.href = "index3.php"
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