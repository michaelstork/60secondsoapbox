
export default {
    inserted: function (el, binding) {
        const frag = document.createDocumentFragment();

        // create file input
        const fileInput = document.createElement('input');
        fileInput.setAttribute('name', binding.arg);
        fileInput.setAttribute('type', 'file');
        fileInput.setAttribute('tabIndex', '-1');

        // create wrapper element to hide file input
        const fileInputWrapper = document.createElement('div');
        fileInputWrapper.style.opacity = '0';
        fileInputWrapper.style.position = 'absolute';
        fileInputWrapper.style.width = '0px';
        fileInputWrapper.style.height = '0px';
        fileInputWrapper.style.overflow = 'hidden';
        fileInputWrapper.appendChild(fileInput);

        frag.appendChild(fileInputWrapper);
        el.parentNode.insertBefore(frag, el.nextElementSibling);

        // on clicking link, trigger click on hidden file input to open file selection dialog
        el.addEventListener('click', function (e) {
            e.preventDefault();
            fileInput.click();
        });

        // on file selected, call upload method
        fileInput.addEventListener('change', function () {
            if (fileInput.files.length) {
                binding.value(fileInput.files[0]);
            }
        });
    }
}