/*globals tr_handle*/
let div = document.querySelectorAll(`#tr_${tr_handle},.tr_${tr_handle}`),
    iframe = document.createElement('iframe'),
    keywords = () => {
        let clean = input => {
                return input.replace(/\W/g, ' ').replace(/[ ]{2,}/g, ' ').trim();
            },
            meta = name => {
                let tag = document.querySelector("meta[name='" + name + "']");
                return (tag !== null) ? tag.getAttribute('content') : '';
            };
        return encodeURIComponent(
            clean(
                meta('keywords') + ' ' + meta('description') + ' ' + document.title
            )
        ).substring(0, 400);
    };

iframe.setAttribute('src', `${location.protocol}//service.trafficroots.com/service/${tr_handle}/${keywords()}`);
iframe.setAttribute('allowtransparency', 'true');
iframe.setAttribute('scrolling', 'no');
iframe.setAttribute('frameborder', '0');
iframe.setAttribute('marginheight', '0');
iframe.setAttribute('marginwidth', '0');

if (div.length) {
    div.forEach(element => {
        if (element.querySelector('iframe')) {
            return;
        }
        iframe.setAttribute('width', element.dataset.width);
        iframe.setAttribute('height', element.dataset.height);
        while (element.hasChildNodes()) {
            element.removeChild(element.lastChild);
        }
        element.appendChild(iframe);
    });
} else {
    if (!window.trafficroots) {
        window.trafficroots = [];
    }
    window.trafficroots.push(`error element wit class .tr_${tr_handle} was not found`);
}
delete window.tr_handle;