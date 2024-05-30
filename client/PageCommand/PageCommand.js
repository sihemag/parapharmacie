/* const decrementBtn = document.getElementById('decrement');
const incrementBtn = document.getElementById('increment');
const quantityDisplay = document.getElementById('quantity');

// Initialize quantity
let quantity = 1;

// Update quantity display
function updateQuantityDisplay() {
    quantityDisplay.textContent = quantity;
} */

document.addEventListener('DOMContentLoaded', function() {
    const decrementButton = document.getElementById('decrement');
    const incrementButton = document.getElementById('increment');
    const quantityInput = document.getElementById('quantityInput');
    const maxQuantity = parseInt(document.getElementById('maxQuantity').value);

    decrementButton.addEventListener('click', function() {
        updateQuantity(-1);
    });

    incrementButton.addEventListener('click', function() {
        updateQuantity(1);
    });

    function updateQuantity(change) {
        let quantity = parseInt(quantityInput.value) + change;
        if (quantity < 1) {
            quantity = 1; // Ensure quantity is never less than 1
        }
        if (quantity > maxQuantity) {
            quantity = maxQuantity; // Limit quantity to the maximum allowed
        }
        quantityInput.value = quantity;
        document.getElementById('quantity').textContent = quantity; // Update the quantity display
    }
});
