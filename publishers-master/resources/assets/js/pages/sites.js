/*globals $, alert*/
import displayFormErrors from '../utilities/displayFormErrors';
let showSiteZones = (site_id) => {
    $('.zones').addClass('hide');
    $('#zones' + site_id).removeClass('hide');
    $('#zones' + site_id).goTo();
};
$(document).ready(() => {
    let { location } = window;
    $('.footable').footable();
    $('.alert').delay(3000).fadeOut();
    $('form').submit(event => {
        event.preventDefault();
        let $form = $(event.currentTarget);
        $.post(
                $form.prop('action'),
                $form.serialize(),
                'json'
            )
            .done(() => {
                location.reload();
            })
            .fail(({ responseJSON, status }) => {
                if (status == 422 && responseJSON) {
                    displayFormErrors($form, responseJSON);
                    return true;
                }
                alert('Error! Please contact support or try again later.');
            });
    });
    $('.site-zones').click(e => {
        let site_id = $(e.currentTarget).parents('td').first().data('site_id');
        showSiteZones(site_id);
    });

    $('.i-check').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });

});
