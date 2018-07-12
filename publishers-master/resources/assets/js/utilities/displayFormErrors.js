/* globals $ */
export default ($form, errors) => {
    $.each(errors, (name, message) => {
        $form.find('input[name="' + name + '"] + .error')
            .removeClass('hide')
            .text(message);
    });
};