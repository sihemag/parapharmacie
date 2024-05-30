document.querySelectorAll('.Box-Command').forEach(function(command) {
    var subTotalElements = command.querySelectorAll(".subTotal");
    var total = 0;

    subTotalElements.forEach(function(element) {
        var subTotalValue = parseFloat(element.innerHTML);
        total += subTotalValue;
    });

    var totalHtml = command.querySelector(".totalPrice");
    totalHtml.innerHTML = total.toFixed(2) + " Dz";
});