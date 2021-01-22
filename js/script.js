/* --------- utils ------------- */
/**
 * @param {Node} element
 * @param {string} msg the error message string
 */
function createMessage(element, msg) {
    var error = document.createElement("div");
    error.classList.add("errorMessage");
    error.innerHTML = msg;
    //insert the div before the element as a child of element.parentNode
    element.parentNode.insertBefore(error, element);
}
// remove error messages if there is any
function removePreviousBox(element) {
    var prevElement = element.previousElementSibling;
    if (prevElement)
        if (prevElement.classList.contains("errorMessage"))
            prevElement.parentNode.removeChild(prevElement);
}

// add the checks on focus out to every form field in fields
function addFocusOutEvent(fields) {
    // for every field (of the form)
    for (field in fields) {
        var element = document.getElementById(field);
        // on focus out event
        element.addEventListener("focusout", function (event) {
            //remove other error messages if there is any
            removePreviousBox(event.target);
            // array with all the checks for field
            var fieldChecks = fields[event.target.id];
            //for every check
            for (var i = 0; i < fieldChecks.length; i++) {
                // if the check is not passed
                if (!fieldChecks[i][0](event.target.value)) {
                    //create error message
                    createMessage(event.target, fieldChecks[i][1]);
                    //only the first error is showed
                    break;
                }
            }
        });
    }
}

/**
 * @return {boolean} true if no error on checks, false otherwise
 */
function clickController(fields) {
    //same behaviour of addFocusOutEvent
    var correct = true;
    for (field in fields) {
        var currentField = document.getElementById(field);
        removePreviousBox(currentField);
        var fieldChecks = fields[field];
        for (var i = 0; i < fieldChecks.length; i++) {
            if (!fieldChecks[i][0](currentField.value)) {
                correct = false;
                createMessage(currentField, fieldChecks[i][1]);
                break;
            }
        }
    }
    // returns the result of the checks performed
    return correct;
}

/*   ---------- checks ----------   */

function checkEmail(email) {
    return new RegExp(/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/).test(email);
}

function checkUsername(username) {
    return new RegExp(/^[a-zA-Z0-9]{5,30}$/).test(username);
}

function checkNome(name) {
    return true;
}

function checkPassword(password) {
    return new RegExp(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/).test(password);
}

function isNotEmpty(str) {
    return str && str.trim().length > 0;
}

/*      ------------ link checks to forms ----------- */

function loginChecker() {
    if (document.getElementById("loginForm")) {
        //loginControls is a map
        var loginControls = {};
        // every key can have more than a value:
        // loginControls[elementid]=[[check1, message1], [check2, message2],...]
        // ATTENTION: the id attribute of the input tag in the form have to match the map key
        loginControls["username"] = [[isNotEmpty, "Inserire un nome utente"],[checkUsername, "Inserire un nome utente valido. "]];
        loginControls["password"] = [[isNotEmpty, "Inserire una password."]];
        // link the controls to the event "focusOut"
        addFocusOutEvent(loginControls);
        //link the controls to the event "click" of the form submit button
        var loginButton = document.getElementById("loginButton");
        loginButton.addEventListener("click", (event) => {
            if (!clickController(loginControls)) event.preventDefault();
        });
    }
}

/* --------- add checks on page load ---------- */

window.onload = function () {
    loginChecker();
};
