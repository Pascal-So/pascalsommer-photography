function get_shortcut_handler() {
    // This script is loaded with `defer` so we don't need to
    // worry about the dom elements not being present yet.
    var shortcut_elements = document.querySelectorAll('[data-shortcutkeycode]');

    // map from keycode to url
    var shortcuts = {};

    for (i = 0; i < shortcut_elements.length; ++i) {
        var el = shortcut_elements[i];
        var href = el.href;
        var keycode = el.dataset.shortcutkeycode;

        if (href == undefined) {
            console.error('Trying to set a shortcut for an element without href attribute: ', el);
            continue;
        }

        shortcuts[keycode] = href;
    }

    return function(e) {
        // Don't activate the shortcuts if ctrl or shift is held.
        if (e.ctrlKey || e.shiftKey) return;

        // Don't do anything if a textfield is in focus.
        if(document.activeElement){
            var tagName = document.activeElement.tagName.toLowerCase();

            if(tagName == 'input' || tagName == 'textarea'){
                return;
            }
        }

        var url = shortcuts[e.keyCode];
        if (url)
            window.location = url;
    }
}

document.addEventListener('keydown', get_shortcut_handler());
