document.addEventListener("DOMContentLoaded", function() {
    // Calculate total price of products
    var total = 0;
    var totalProducts = document.querySelectorAll('.totalProduct');
    totalProducts.forEach(function(product) {
        var price = parseInt(product.textContent);
        total += price;
    });

    // Calculate delivery cost (5% of total)
    var deliveryCost = parseFloat((total * 0.08).toFixed(2));

    // Calculate total including delivery cost
    var totalEvery = total + deliveryCost;

    // Update HTML elements with calculated values
    document.getElementById('Total').textContent = total + ' dz';
    document.getElementById('TotalLiv').textContent = deliveryCost + ' dz';
    document.getElementById('TotalEvery').textContent = totalEvery + ' dz';

    document.getElementById('totalPrice').value = totalEvery;
});

document.addEventListener('DOMContentLoaded', function() {
    const quantityButtons = document.querySelectorAll('.quantity-btn');

    quantityButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const parentForm = this.closest('.QuantityTd');
            const quantityElement = parentForm.querySelector('.quantity');
            let quantity = parseInt(quantityElement.textContent);

            if (action === 'increment') {
                quantity++;
            } else if (action === 'decrement' && quantity > 1) {
                quantity--;
            }

            quantityElement.textContent = quantity;
            parentForm.querySelector(`input[name="quantityNumber_${this.id.split('_')[1]}"]`).value = quantity;
            parentForm.querySelector(`button[name="UpdateButton_${this.id.split('_')[1]}"]`).style.display = 'inline-block';
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantityInput');
    const saveBtn = document.getElementById('saveBtn');
    let changesMade = false;

    // Add event listener to each quantity input
    quantityInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            // Set changesMade flag to true when input changes
            changesMade = true;
            // Show the save button
            saveBtn.style.display = 'block';
        });
    });

    // Add event listener to save button
    saveBtn.addEventListener('click', function() {
        if (changesMade) {
            // Submit the form when changes have been made
            document.getElementById('quantityForm').submit();
        }
    });
});