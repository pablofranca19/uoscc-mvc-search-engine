const phoneInput = document.querySelector('[data-phone-mask]');

if (phoneInput) {
    phoneInput.addEventListener('input', () => {
        const digits = phoneInput.value.replace(/\D/g, '').slice(0, 11);

        if (digits.length <= 2) {
            phoneInput.value = digits ? `(${digits}` : '';

            return;
        }

        const number = digits.slice(2);
        const separatorPosition = digits.length <= 10 ? 4 : 5;

        phoneInput.value = `(${digits.slice(0, 2)}) ${number.slice(0, separatorPosition)}${number.length > separatorPosition ? `-${number.slice(separatorPosition)}` : ''}`;
    });
}
