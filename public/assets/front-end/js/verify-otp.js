"use strict";

var obj = document.getElementById('partitioned');
obj.addEventListener('keydown', stopCarret);
obj.addEventListener('keyup', stopCarret);

function stopCarret() {
    if (obj.value.length > 3){
        setCaretPosition(obj, 3);
    }
}

function setCaretPosition(elem, caretPos) {
    if(elem != null) {
        if(elem.createTextRange) {
            var range = elem.createTextRange();
            range.move('character', caretPos);
            range.select();
        }
        else {
            if(elem.selectionStart) {
                elem.focus();
                elem.setSelectionRange(caretPos, caretPos);
            }
            else
                elem.focus();
        }
    }
}
