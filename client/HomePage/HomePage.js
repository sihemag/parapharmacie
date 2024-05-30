$(document).ready(function() {
    $('#addToCartForm').submit(function(event) {
        // Prevent default form submission
        event.preventDefault();
        
        // Perform AJAX request
        $.ajax({
            type: 'POST',
            url: 'your_php_script.php',
            data: $(this).serialize(), // Serialize form data
            success: function(response) {
                // Handle successful response
                alert('Item added to cart successfully!');
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    });
});