$(document).ready(function() {
  const usernameInput = $("#username");
  const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

  usernameInput.on("input", function() {
    const inputValue = $(this).val();

    if (emailRegex.test(inputValue)) {
      // The entered value is an email address
      // You can now proceed to check if the email address exists in the database
      $(this).attr("type", "email");
    } else {
      // The entered value is not an email address
      // You can now proceed to check if the username exists in the database
      $(this).attr("type", "text");
    }
  });
});