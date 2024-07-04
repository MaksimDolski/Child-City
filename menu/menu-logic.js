document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all plus buttons
    document.querySelectorAll('.plus').forEach(button => {
        button.addEventListener('click', function() {
            updateQuantity(this.closest('.product-card'), 1); // Increase by 1
        });
    });

    document.querySelectorAll('.minus').forEach(button => {
        button.addEventListener('click', function() {
            updateQuantity(this.closest('.product-card'), -1); // Decrease by 1
        });
    });

    function updateQuantity(parentCard, change) {
        const quantitySpan = parentCard.querySelector('.quantity');
        let currentQuantity = parseInt(quantitySpan.textContent);
        const pricePerUnit = parseFloat(parentCard.querySelector('.product-price').textContent.replace(',', '.'));
        if (currentQuantity + change >= 0 && currentQuantity + change <= 15) {
            currentQuantity += change;
            quantitySpan.textContent = currentQuantity;
            updateTotalPrice();
        }
    }

function updateTotalPrice() {
    const allProducts = document.querySelectorAll('.product-card');
    let totalPriceAll = 0;
    allProducts.forEach(product => {
        const quantity = parseInt(product.querySelector('.quantity').textContent);
        const pricePerUnit = parseFloat(product.querySelector('.product-price').textContent.replace(',', '.'));
        totalPriceAll += quantity * pricePerUnit;
    });
    document.getElementById('total-price-all').textContent = totalPriceAll.toFixed(2);
    const buyButton = document.getElementById('product-btn');
    if (parseFloat(totalPriceAll) === 0) {
        buyButton.disabled = true;
    } else {
        buyButton.disabled = false;
    }
}


});