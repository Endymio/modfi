function email_link(id)
{
    var a = 'janne.';
    var b = 'kaistinen';
    var c = '@';
    var d = 'iki';
    var e = '.fi';

    el = document.getElementById(id);
    el.innerHTML = '<a class="blank" href="mailto:' + a+b+c+d+e+ '">' +a+b+c+d+e+ '</a>';
}
