/*globals $*/
export default (selector) => {
    $(selector)
        .modal('hide')
        .find('form').get(0).reset();
};