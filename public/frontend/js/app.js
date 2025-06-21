// resources/js/app.js

document.addEventListener('DOMContentLoaded', function() {
    // =============================================
    // Quantity Controls
    // =============================================
    document.querySelectorAll('.minus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.nextElementSibling;
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });

    document.querySelectorAll('.plus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const max = parseInt(input.max);
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        });
    });

    // =============================================
    // Payment Method Toggle
    // =============================================
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const creditCardForm = document.getElementById('creditCardForm');
    
    if (paymentMethods.length && creditCardForm) {
        paymentMethods.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.id === 'creditCard') {
                    creditCardForm.style.display = 'flex';
                } else {
                    creditCardForm.style.display = 'none';
                }
            });
        });
    }

    // =============================================
    // Form Validation
    // =============================================
    const forms = document.querySelectorAll('form.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });

    // =============================================
    // Toast Notifications
    // =============================================
    const toastElList = [].slice.call(document.querySelectorAll('.toast'));
    const toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 5000
        });
    });

    toastList.forEach(toast => toast.show());

    // =============================================
    // Tooltips
    // =============================================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // =============================================
    // Cart Item Removal Confirmation
    // =============================================
    document.querySelectorAll('.remove-from-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                e.preventDefault();
            }
        });
    });

    // =============================================
    // Price Range Slider
    // =============================================
    const priceRange = document.getElementById('priceRange');
    const priceOutput = document.getElementById('priceOutput');
    
    if (priceRange && priceOutput) {
        priceRange.addEventListener('input', function() {
            priceOutput.textContent = '$' + this.value;
        });
    }

    // =============================================
    // Mobile Menu Toggle
    // =============================================
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
        });
    }

    // =============================================
    // Product Image Zoom
    // =============================================
    document.querySelectorAll('.product-img-zoom').forEach(img => {
        img.addEventListener('click', function() {
            const modal = document.getElementById('imageZoomModal');
            const modalImg = document.getElementById('zoomedImage');
            
            if (modal && modalImg) {
                modalImg.src = this.src;
                const zoomModal = new bootstrap.Modal(modal);
                zoomModal.show();
            }
        });
    });

    // =============================================
    // Address Form Toggle (Billing/Shipping)
    // =============================================
    const sameAddressCheckbox = document.getElementById('sameAddress');
    const shippingAddressForm = document.getElementById('shippingAddressForm');
    
    if (sameAddressCheckbox && shippingAddressForm) {
        sameAddressCheckbox.addEventListener('change', function() {
            shippingAddressForm.style.display = this.checked ? 'none' : 'block';
        });
    }

    // =============================================
    // Lazy Loading Images
    // =============================================
    if ('loading' in HTMLImageElement.prototype) {
        const lazyImages = document.querySelectorAll('img.lazy');
        lazyImages.forEach(img => {
            img.loading = 'lazy';
        });
    } else {
        // Fallback for browsers that don't support lazy loading
        const lazyLoadInstance = new LazyLoad({
            elements_selector: ".lazy"
        });
    }

    // =============================================
    // AJAX Add to Cart
    // =============================================
    document.querySelectorAll('.ajax-add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const quantity = this.dataset.quantity || 1;
            const url = this.dataset.url || '/cart/add';
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    const cartCount = document.getElementById('cartCount');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                    }
                    
                    // Show success message
                    alert('Product added to cart!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding to cart');
            });
        });
    });
});

// =============================================
// Debounce Function for Search
// =============================================
function debounce(func, wait, immediate) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// =============================================
// Product Search Functionality
// =============================================
const searchInput = document.getElementById('productSearch');
if (searchInput) {
    searchInput.addEventListener('keyup', debounce(function() {
        const searchTerm = this.value.trim();
        if (searchTerm.length > 2) {
            // Implement your search logic here
            console.log('Searching for:', searchTerm);
        }
    }, 300));
}

// =============================================
// Initialize Bootstrap Popovers
// =============================================
const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
popoverTriggerList.map(function(popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
});
