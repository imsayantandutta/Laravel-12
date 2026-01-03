document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('checkoutForm');
    if (!form) return;

    const checkbox = document.getElementById('c_ship_different_address');
    const billingSection = document.getElementById('ship_different_address');

    const expiryInput = document.getElementById('card_expiry');
    const errorText = document.getElementById('expiry_error');

    const cardNumberInput = document.getElementById('card_number');
    const cardTypeSelect = document.getElementById('card_type');

    /* ===============================
       Billing Address Toggle
    =============================== */
    function toggleBilling() {
        const billingInputs = billingSection.querySelectorAll('input, select');

        if (checkbox.checked) {
            billingSection.style.display = 'none';
            billingInputs.forEach(el => el.disabled = true);
        } else {
            billingSection.style.display = 'block';
            billingInputs.forEach(el => el.disabled = false);
        }
    }

    checkbox.addEventListener('change', toggleBilling);
    toggleBilling(); // init

    /* ===============================
       Field Validation
    =============================== */
    function validateField(el) {
        const value = el.value.trim();
        let valid = true;

        switch (el.id) {
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
                valid = value.replace(/\D/g, '').length === 16;
                break;

            case 'card_cvv':
                valid = /^\d{3,4}$/.test(value);
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

    function getFieldsToValidate() {
        let fields = [
            'first_name', 'last_name', 'shipping_state', 'shipping_address',
            'shipping_city', 'shipping_zip_code', 'email_address',
            'phone_number', 'date_of_birth', 'gender',
            'card_number', 'card_expiry', 'card_cvv'
        ];

        if (!checkbox.checked) {
            fields.push(
                'billing_first_name', 'billing_last_name',
                'billing_state', 'billing_address',
                'billing_city', 'billing_zip_code'
            );
        }

        return fields;
    }

    /* ===============================
       Live Validation
    =============================== */
    document.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('input', () => validateField(field));
    });

    /* ===============================
       Card Number Formatting + Type
    =============================== */
    cardNumberInput.addEventListener('input', function () {
        let number = this.value.replace(/\D/g, '').slice(0, 16);
        this.value = number.replace(/(\d{4})(?=\d)/g, '$1 ').trim();

        let type = '';
        if (/^4/.test(number)) type = 'visa';
        else if (/^5[1-5]/.test(number)) type = 'mastercard';
        else if (/^3[47]/.test(number)) type = 'amex';
        else if (/^6(?:011|5)/.test(number)) type = 'discover';

        cardTypeSelect.value = type;
    });

    /* ===============================
       Expiry Formatting + Validation
    =============================== */
    expiryInput.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 2) value = value.slice(0, 2) + '/' + value.slice(2, 4);
        this.value = value.slice(0, 5);
    });

    function validateExpiry() {
        const value = expiryInput.value;
        if (!/^\d{2}\/\d{2}$/.test(value)) return false;

        const [mm, yy] = value.split('/');
        const month = parseInt(mm, 10);
        const year = 2000 + parseInt(yy, 10);

        const now = new Date();
        const currentMonth = now.getMonth() + 1;
        const currentYear = now.getFullYear();

        if (month < 1 || month > 12) return false;
        if (year < currentYear || (year === currentYear && month < currentMonth)) return false;
        if (year > currentYear + 20) return false;

        return true;
    }

    /* ===============================
       Form Submit
    =============================== */
    form.addEventListener('submit', function (e) {
        let valid = true;

        getFieldsToValidate().forEach(id => {
            const el = document.getElementById(id);
            if (el && !validateField(el)) valid = false;
        });

        if (!validateExpiry()) {
            errorText.textContent = 'Invalid expiry date';
            errorText.style.display = 'block';
            expiryInput.style.border = '2px solid red';
            valid = false;
        } else {
            errorText.style.display = 'none';
        }

        if (!valid) {
            e.preventDefault();
            const firstInvalid = document.querySelector('input[style*="red"], select[style*="red"]');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

});
