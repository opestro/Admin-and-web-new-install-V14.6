'use strict'
$(window).on("load", () => {
    const { createPopup } = window.picmoPopup;
    const trigger = document.getElementById('trigger');
    const inputField = document.getElementById('msgInputValue');

    const picker = createPopup(
        {},
        {
            referenceElement: trigger,
            triggerElement: trigger,
            position: "right-end",
        }
    );

    $('#trigger').on('click', () => {
        picker.toggle();
    });

    picker.addEventListener('emoji:select', (selection) => {
        const { emoji } = selection;

        const startPos = inputField.selectionStart;
        const endPos = inputField.selectionEnd;
        const value = inputField.value;

        inputField.value = value.substring(0, startPos) + emoji + value.substring(endPos);

        inputField.selectionStart = inputField.selectionEnd = startPos + emoji.length;

        inputField.focus();
    });
});
