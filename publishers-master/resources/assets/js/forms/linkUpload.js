/* globals $ */
import displayFormErrors from '../utilities/displayFormErrors';
import resetModal from '../utilities/closeAndResetModal';
let $form = $('form#link_form'),
    $submitButton = $form.find('button[type="submit"]');

$form
    .on('reset', () => {
        $form.find('.error').empty();
        $submitButton.prop("disabled", false);
    })
    .submit(event => {
        $submitButton.prop("disabled", true);
        event.preventDefault();

        $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: new FormData($form.get(0)),
                dataType: 'json',
                processData: false,
                contentType: false
            })
            .then(response => {
                $('#links_table tbody').append(
                    `<tr class="Link_row" id="Link_row_${response.id}">
                        <td>${response.name}</td>
                        <td>${response.category}</td>
                        <td>${response.url}</td>
                        <td>${response.status}</td>
                        <td>${response.date}</td>
                    </tr>`
                );
                resetModal('#addLink');
                window.toastr.success('Link added successfully.');
            })
            .catch(({ responseJSON, status }) => {
                $submitButton.prop("disabled", false);
                if (status == 422 && responseJSON) {
                    displayFormErrors($form, responseJSON);
                    return true;
                }
                window.toastr.error('Error! Please contact support or try again later.');
            });
    });