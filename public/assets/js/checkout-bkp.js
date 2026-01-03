document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const checkbox = document.getElementById('c_ship_different_address');
    const billingSection = document.getElementById('ship_different_address');
    const expiryInput = document.getElementById('card_expiry');
    const cvvField = document.getElementById('card_cvv');
    const errorText = document.getElementById('expiry_error');
    const cardNumberInput = document.getElementById('card_number');

    if (!form) return console.error('Checkout form not found');

    // Show/hide billing section dynamically
    checkbox.addEventListener('change', function() {
        billingSection.style.display = checkbox.checked ? 'none' : 'block';
        ['billing_first_name','billing_last_name','billing_state','billing_address','billing_city','billing_zip_code']
            .forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.border = '';
            });
    });

    // Validation function
    function validateField(el) {
        const value = el.value.trim();
        let valid = true;

        switch(el.id) {
            case 'phone_number':
                valid = /^\d{10}$/.test(value);
                break;
            case 'shipping_zip_code':
            case 'billing_zip_code':
                valid = /^\d{5}$/.test(value);
                break;
            case 'email_address':
                valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                break;
            case 'card_number':
                valid = /^\d{16}$/.test(value.replace(/\s/g, ''));
                break;
            case 'card_cvv':
                const cardType = document.getElementById('card_type').value;
                valid = cardType === 'amex' ? /^\d{4}$/.test(value) : /^\d{3}$/.test(value);
                break;
            case 'card_expiry':
                valid = validateExpiry();
                break;
            default:
                valid = value !== '';
        }

        el.style.border = valid ? '2px solid green' : '2px solid red';
        return valid;
    }

    // Get all fields to validate
    function getFieldsToValidate() {
        let fields = [
            'first_name','last_name','shipping_state','shipping_address','shipping_city','shipping_zip_code',
            'email_address','phone_number','date_of_birth','gender',
            'card_number','card_type','card_expiry','card_cvv'
        ];
        if (!checkbox.checked) {
            fields.push('billing_first_name','billing_last_name','billing_state','billing_address','billing_city','billing_zip_code');
        }
        return fields;
    }

    // Live validation
    document.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('input', () => validateField(field));
    });

    // Card number formatting
    cardNumberInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '').slice(0,16);
        this.value = value.replace(/(.{4})/g, '$1 ').trim();
    });

    // Expiry formatting
    expiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g,'');
        if (value.length > 2) value = value.slice(0,2) + '/' + value.slice(2,4);
        e.target.value = value.slice(0,5);
    });

    expiryInput.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && this.value.endsWith('/')) {
            this.value = this.value.slice(0, -1);
        }
    });

    function validateExpiry() {
        const value = expiryInput.value;
        if (!/^\d{2}\/\d{2}$/.test(value)) return false;

        const [mm, yy] = value.split('/');
        const month = parseInt(mm,10);
        const year = 2000 + parseInt(yy,10);
        const now = new Date();
        const currentMonth = now.getMonth()+1;
        const currentYear = now.getFullYear();

        if (month < 1 || month > 12) return false;
        if (year < currentYear || year > currentYear + 20) return false;
        if (year === currentYear && month < currentMonth) return false;

        return true;
    }

    function showExpiryError(msg) {
        errorText.textContent = msg;
        errorText.style.display = 'block';
        expiryInput.style.border = '2px solid red';
        expiryInput.focus();
    }

    // Submit handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let valid = true;

        const fields = getFieldsToValidate();
        fields.forEach(id => {
            const el = document.getElementById(id);
            if (el && !validateField(el)) valid = false;
        });

        if (!validateExpiry()) {
            showExpiryError('Invalid expiry date');
            valid = false;
        } else {
            errorText.style.display = 'none';
            expiryInput.style.border = '2px solid green';
        }

        if (valid) {
            form.submit();
        } else {
            const firstInvalid = document.querySelector('input[style*="red"], select[style*="red"]');
            if(firstInvalid) firstInvalid.scrollIntoView({behavior:'smooth', block:'center'});
        }
    });


    const cardNumber = document.getElementById('card_number');
    const cardTypeSelect = document.getElementById('card_type');

    cardNumber.addEventListener('input', function () {
    const number = cardNumber.value.replace(/\s+/g, ''); // remove spaces

    // Automatically insert spaces every 4 digits
    cardNumber.value = number.replace(/(\d{4})(?=\d)/g, '$1 ').trim();

    let detectedType = '';

    if (/^4/.test(number)) {
      detectedType = 'visa';
    } else if (/^5[1-5]/.test(number)) {
      detectedType = 'mastercard';
    } else if (/^3[47]/.test(number)) {
      detectedType = 'amex';
    } else if (/^6(?:011|5)/.test(number)) {
      detectedType = 'discover';
    }

    // Update select dropdown automatically
    if (detectedType) {
      cardTypeSelect.value = detectedType;
    } else {
      cardTypeSelect.value = '';
    }
  });
  
});
