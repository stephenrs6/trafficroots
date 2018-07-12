/* globals $ */
import displayFormErrors from '../utilities/displayFormErrors';
import Config from '../config';
import imageBlob from '../utilities/imageBlob';
import resetModal from '../utilities/closeAndResetModal';
let $button = $('.btn[for="image_file"]'),
    $imageInput = $('input#image_file'),
    $form = $('form#media_form'),
    $submitButton = $form.find('button[type="submit"]'),
    resetButton = () => {
        $button
            .removeClass('btn-outline')
            .find('span')
            .text('Upload');
    };
if (location.hash) {
    $(`.nav-tabs a[href="${location.hash}"]`).tab('show');
}

$form
    .submit(event => {
        event.preventDefault();
        $form.find('button[type="submit"]').prop("disabled", true);

        $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: new FormData($form.get(0)),
                dataType: 'json',
                processData: false,
                contentType: false
            })
            .then(response => {
                $('#media_table tbody').append(
                    `<tr class="media_row" id="media_row_${response.id}">
                        <td>${response.name}</td>
                        <td>${response.category}</td>
                        <td>${response.location_type}</td>
                        <td>${response.status}</td>
                        <td>${response.date}</td>
                        <td>
                            <a href="#" class="tr-preview" data-toggle="popover" data-html="true" data-placement="left" data-trigger="hover" title="" data-content="<img src='${response.url}' width='120' height='120'>" id="view_media_${response.id}" data-original-title="">
                                <i class="fa fa-camera-retro" aria-hidden="true"></i>
                            </a> 
                        </td>
                    </tr>`
                );
                $('[data-toggle="popover"]').popover();
                resetModal('#addMedia');
                window.toastr.success('Image added successfully.');
            })
            .catch(({ responseJSON, status }) => {
                $submitButton.prop("disabled", false);
                if (status == 422 && responseJSON) {
                    if (responseJSON.file) {
                        resetButton();
                        $('.success[for="image_file"]').hide();
                        $('.error[for="image_file"]').show()
                            .find('span').text(responseJSON.file[0]);
                        delete responseJSON.file;
                    }
                    displayFormErrors($form, responseJSON);
                    return true;
                }
                window.toastr.error('Error! Please contact support or try again later.');
            });
    })
    .on('reset', () => {
        $submitButton.prop("disabled", false);
        $form.find('.error').empty();
        resetButton();
        $imageInput.parent().find('label.success').first().hide();
    });
$imageInput
    .change(({ currentTarget }) => {
        let $currentTarget = $(currentTarget),
            $success = $currentTarget.parent().find('label.success').first().hide(),
            $error = $currentTarget.parent().find('label.error').first().hide(),
            { files } = currentTarget,
            file = null;
        if (files && files.length) {
            file = files[0];
            if (!file) {
                resetButton();
                window.toastr.error('File must be an image');
                $error
                    .show()
                    .find('span').text('File must be an image');
                return false;
            }
            if (file.size > Config.maxFileSizeBytes) {
                resetButton();
                window.toastr.error('Max file size is 300kb');
                $error
                    .show()
                    .find('span').text('Max file size is 300kb');
                return false;
            }
            $success
                .show()
                .find('span').first()
                .text(file.name);
            let src = imageBlob(file);
            if (src) {
                $success.find('div img').first()
                    .attr({ src });
            }
            $button
                .addClass('btn-outline')
                .find('span').first().text('Change');
            return true;
        }
        resetButton();
    });