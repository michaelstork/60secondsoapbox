import debounce from 'lodash/debounce';

export default {
    inserted: function (el, binding) {
        const input = el.querySelector('input');

        // create element for invalid status error message
        const message = document.createElement('span');
        message.setAttribute('class', 'async-validate-error');

        // create element for valid status indicator
        const check = document.createElement('i');
        check.setAttribute('class', 'mdi mdi-check async-validate-valid');
        
        const frag = document.createDocumentFragment();
        frag.appendChild(message);
        frag.appendChild(check);
        el.insertBefore(frag, el.lastChild);

        input.addEventListener('input', debounce(function (e) {
            // don't bother validating unless the pattern is valid
            if (e.target.validity.patternMismatch || e.target.validity.typeMismatch) return;

            const validator = binding.value.validator(e.target.value);

            validator && validator.then(
                () => {
                    e.target.setCustomValidity('');
                    e.target.dataset.asyncValidated = true;
                    message.innerText = '';
                },
                errorResponse => {
                    e.target.setCustomValidity(binding.value.message);
                    e.target.dataset.asyncValidated = false;
                    message.innerText = binding.value.message || errorResponse.data.message;
                }
            )
            .finally(() => {
                let event = new Event('input');
                e.target.form.dispatchEvent(event);
            });
        }, 250));
    }
}