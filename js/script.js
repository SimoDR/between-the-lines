function createHTMLBox(msg, type) {
    var box = document.createElement("div");
    box.classList.add("msg_box");

    var msgClass = "success_box";
    if(type == MSG_TYPES.ERROR)
        msgClass = "error_box";
    else if(type == MSG_TYPES.WARNING)
        msgClass = "warning_box";

    box.classList.add(msgClass);

    box.innerHTML = msg;

    return box;
}

function showAlertBox(element, msg) {
    var box = createHTMLBox(msg, MSG_TYPES.ERROR);
    element.parentNode.insertBefore(box, element);
}

function removePreviousBox(element) {
    var prevElement = element.previousElementSibling;
    if(hasClass(prevElement, "msg_box"))
        prevElement.parentNode.removeChild(prevElement);
}