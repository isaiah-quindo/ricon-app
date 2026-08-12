import Trix from 'trix';
import 'trix/dist/trix.css';

// Don't render filename/size captions under attached images
Trix.config.attachments.preview.caption = { name: false, size: false };

const MAX_IMAGE_BYTES = 5 * 1024 * 1024; // keep in sync with the server-side 5120KB rule

addEventListener('trix-file-accept', (event) => {
    if (!event.file.type.startsWith('image/')) {
        event.preventDefault();
        alert('Only images can be attached.');
        return;
    }
    if (event.file.size > MAX_IMAGE_BYTES) {
        event.preventDefault();
        alert('Images must be 5MB or smaller.');
    }
});

addEventListener('trix-attachment-add', (event) => {
    const { attachment } = event;
    if (!attachment.file) return;

    const uploadUrl = event.target.closest('[data-upload-url]')?.dataset.uploadUrl;
    if (!uploadUrl) return;

    const form = new FormData();
    form.append('image', attachment.file);

    window.axios.post(uploadUrl, form, {
        onUploadProgress: (e) => {
            if (e.total) attachment.setUploadProgress(Math.round((e.loaded / e.total) * 100));
        },
    })
    .then(({ data }) => attachment.setAttributes({ url: data.url, href: data.url }))
    .catch(() => {
        attachment.remove();
        alert('Image upload failed. Please try again.');
    });
});
