'use strict';
$('.filter-tickets').on('change',function (){
    let param = $(this).data('value');
    let value = $(this).val();
    let text = window.location;
    let redirectTo = '';
    let polished = removeURLParameter(text.toString(), param);
    if (polished.includes('?')) {
        redirectTo = polished + '&' + param + '=' + value;
    } else {
        redirectTo = polished + '?' + param + '=' + value;
    }
    location.href = redirectTo;
})
function removeURLParameter(url, parameter) {
    let urlParts = url.split('?');
    if (urlParts.length >= 2) {
        let prefix = encodeURIComponent(parameter) + '=';
        let pars = urlParts[1].split(/[&;]/g);
        for (let i = pars.length; i-- > 0;) {
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                pars.splice(i, 1);
            }
        }
        return urlParts[0] + (pars.length > 0 ? '?' + pars.join('&') : '');
    }
    return url;
}
