jQuery(document).ready(function ($) {
	$('.button-upload').on('click', function (e) {
		e.preventDefault();

		const button = $(this);
		const inputField = button.siblings('.text-upload');
		const imagePreview = button.siblings('.preview-upload');

		const frame = wp.media({
			title: 'Select or Upload an Icon',
			button: {
				text: 'Use this icon',
			},
			multiple: false,
		});

		frame.on('select', function () {
			const attachment = frame.state().get('selection').first().toJSON();
			inputField.val(attachment.url);
			imagePreview.attr('src', attachment.url);
		});

		frame.open();
	});
});
