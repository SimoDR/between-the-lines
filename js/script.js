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

//more permissive than its backend counterpart: accepted everything which is not number, 2-30 digits
function checkNome(name) {
    return new RegExp(/^[^0-9]{2,30}$/).test(name);;
}

function checkPassword(password) {
    return new RegExp(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/).test(password);
}

function isNotEmpty(str) {
    return str && str.trim().length > 0;
}

function reviewContentLowerBound(str) {
    return str.trim().length>50;
}

function reviewContentUpperBound(str) {
    return str.trim().length<500;
}

function checkTitle(str) {
    return str && str.trim().length>4;
}

function checkAltText(str) {
    return str && str.trim().length>4;
}

function plotLowerBound(str) {
    return str.trim().length>50;
}

function plotUpperBound(str) {
    return str.trim().length<500;
}
    
function isEqual(str1, str2) {
    return str1.trim() == str2.trim();
}
function isDatePast(date) {
    var today = new Date().getFullYear();
    return date < today;
}

function isMinor(arg1, arg2) {
    return arg1<arg2;
}

/*      ------------ link checks to forms ----------- */

function loginChecker() {
    //the id of the form
    if (document.getElementById("loginForm")) {
        //loginControls is a map
        var loginControls = {};
        // every key can have more than a value:
        // loginControls[elementid]=[[check1, message1], [check2, message2],...]
        // ATTENTION: the id attribute of the input tag in the form have to match the map key
        loginControls["username"] = [[isNotEmpty, "Impossibile lasciare questo campo vuoto."]];
        loginControls["password"] = [[isNotEmpty, "Impossibile lasciare questo campo vuoto."]];
        // link the controls to the event "focusOut"
        addFocusOutEvent(loginControls);
        //link the controls to the event "click" of the form submit button
        var loginButton = document.getElementById("loginButton");
        loginButton.addEventListener("click", (event) => {
            if (!clickController(loginControls)) event.preventDefault();
        });
    }
}


function reviewChecker() {
    
    if (document.getElementById("reviewForm")) {
       
        //loginControls is a map
        var reviewControls = {};
        // every key can have more than a value:
        // loginControls[elementid]=[[check1, message1], [check2, message2],...]
        // ATTENTION: the id attribute of the input tag in the form have to match the map key
        reviewControls["reviewContent"] = [[isNotEmpty, "La recensione non può essere vuota."],
                                          [reviewContentLowerBound,"La recensione deve contenere almeno 50 caratteri."],
                                          [reviewContentUpperBound,"La recensione deve contenere al massimo 500 caratteri."]];
        // link the controls to the event "focusOut"
        addFocusOutEvent(reviewControls);
        //link the controls to the event "click" of the form submit button
        var reviewButton = document.getElementById("reviewButton");
        reviewButton.addEventListener("click", (event) => {
            if (!clickController(reviewControls)) event.preventDefault();
        });
    }
}

function registrazioneChecker() {
    if (document.getElementById("registrazioneForm")) {
        var regControls = {};
        regControls["email"] = [[checkEmail, "Inserire una e-mail valida"]];
        regControls["username"] = [[checkUsername, "Il nome utente deve avere tra i 5 e i 30 caratteri ed essere composto da lettere e numeri."]];
        regControls["password"] = [[checkPassword, "La password deve essere di almeno 8 caratteri e deve contenere lettere maiuscole, minuscole e numeri."]];
        // link the controls to the event "focusOut"
        addFocusOutEvent(regControls);
        //control on the passwords match
        var pwd2 = document.getElementById("repeatpassword");
        pwd2.addEventListener("focusout", function (event) {
            removePreviousBox(event.target);
            if (!isEqual(event.target.value, document.getElementById("password").value))
                createMessage(event.target, "Le due password non coincidono.");
        });
        //link the controls to the event "click" of the form submit button
        var regButton = document.getElementById("regButton");
        regButton.addEventListener("click", (event) => {
            if (!clickController(regControls)) event.preventDefault();
        });

    }
}
function newAutoreChecker(){
    if (document.getElementById("autoreForm")) {
        var autoreControls = {};
        autoreControls["authorName"] = [[isNotEmpty, "Il nome non può essere vuoto"],[checkNome, "Il nome deve avere almeno 2 caratteri ed essere formato solo da lettere e spazi"]];
        autoreControls["authorSurname"] = [[isNotEmpty, "Il cognome non può essere vuoto"],[checkNome, "Il cognome deve avere almeno 2 caratteri ed essere formato solo da lettere e spazi"]];
        autoreControls["birthDate"]=[[isNotEmpty, "La data di nascita non può essere vuota"],[isDatePast, "La data di nascita non può essere futura"]];
        autoreControls["deathDate"]=[[isDatePast, "La data di morte non può essere futura"]];
        addFocusOutEvent(autoreControls);
        //death is later than birth
        var death = document.getElementById("deathDate");
        death.addEventListener("focusout", function (event) {

            if (!isMinor(document.getElementById("birthDate").value, event.target.value)) {
                createMessage(event.target, "La data di nascita deve essere precedente a quella di morte.");
                removePreviousBox(event.target);}
            });
        var autoreButton = document.getElementById("autoreButton");
        autoreButton.addEventListener("click", (event) => {
            if (!clickController(autoreControls)) event.preventDefault();
        });
    }
}

function newGenereChecker() {
    if (document.getElementById("genereForm")) {
        var genereControls = {};
        genereControls["genreName"] = [[isNotEmpty, "Impossibile lasciare questo campo vuoto."],[checkNome, "Il genere deve avere almeno 2 caratteri ed essere formato solo da lettere e spazi"]];
        addFocusOutEvent(genereControls);
        //link the controls to the event "click" of the form submit button
        var genereButton = document.getElementById("genereButton");
        genereButton.addEventListener("click", (event) => {
            if (!clickController(genereControls)) event.preventDefault();
        });
    }
}

function modificaUtenteChecker() {
    if (document.getElementById("changeInfoForm")) {
        var userControls = {};
        userControls["user-email"] = [[checkEmail, "Inserire una e-mail valida"]];
        userControls["username"] = [[checkUsername, "Il nome utente deve avere tra i 5 e i 30 caratteri ed essere composto da lettere e numeri."]];
        userControls["newPassword1"] = [[checkPassword, "La password deve essere di almeno 8 caratteri e deve contenere lettere maiuscole, minuscole e numeri."]];
        addFocusOutEvent(userControls);
        //control on the passwords match
        var pwd2 = document.getElementById("newPassword2");
        pwd2.addEventListener("focusout", function (event) {
            removePreviousBox(event.target);
            if (!isEqual(event.target.value, document.getElementById("newPassword1").value))
                createMessage(event.target, "Le due password non coincidono.");
        });
        //link the controls to the event "click" of the form submit button
        var modifyButton = document.getElementById("modificaButton");
        modifyButton.addEventListener("click", (event) => {
            if (!clickController(userControls)) event.preventDefault();
        });

    }
}
function contattiChecker() {
    if (document.getElementById("message-form")) {
        var contattiControls = {};
        contattiControls["e_mail"] = [[checkEmail, "Inserire una e-mail valida"]];
        contattiControls["message"] = [[isNotEmpty, "La recensione non può essere vuota"],
                                     [reviewContentLowerBound,"La recensione deve contenere almeno 50 caratteri."]];
        contattiControls["first_name"] = [[isNotEmpty, "Il nome non può essere vuoto"],[checkNome, "Il nome deve avere almeno 2 caratteri ed essere formato solo da lettere e spazi"]];
        contattiControls["last_name"] = [[isNotEmpty, "Il cognome non può essere vuoto"],[checkNome, "Il cognome deve avere almeno 2 caratteri ed essere formato solo da lettere e spazi"]];
        // link the controls to the event "focusOut"
        addFocusOutEvent(contattiControls);

        //link the controls to the event "click" of the form submit button
        var msgButton = document.getElementById("msgButton");
        msgButton.addEventListener("click", (event) => {
            if (!clickController(contattiControls)) event.preventDefault();
        });

    }
}

 function hasClass(element, className) {
    return element.classList.contains(className);
  }
  MSG_TYPES = {
    ERROR: -1, 
    WARNING: 0, 
    SUCCESS: 1
  };
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
  function showAlertBox(element, message) {
    var alertBox = createHTMLBox(message, MSG_TYPES.ERROR);
    element.parentNode.insertBefore(alertBox, element);
  }

  function changeImg(sourceElement, previewElement) {
    if(sourceElement.files && sourceElement.files[0]) {
      var reader = new FileReader();
      reader.onload = function(ee) {
        previewElement.src = ee.target.result;
      }
      reader.readAsDataURL(sourceElement.files[0]);
    }
  }
  function extensionAccepted(path) {
    var extensions = ['png','jpg','jpeg','gif'];
    return extensions.includes(path.split('.').pop());
  }
  function sizeAccepted(size) {
    return size < 3000000;
  }
  function checkPhoto(image) {
    if(isNotEmpty(image.value)) {
      removePreviousBox(image);
      if(!extensionAccepted(image.value)) {
        showAlertBox(image, "Il formato non è supportato. Sono supportati i formati jpg, jpeg, gif, png");
        return false;
      }
      else if(!sizeAccepted(image.files[0].size)) {
        showAlertBox(image, "Il file è troppo grande. Sono accettati file fino a 3MB");
        return false;
      }
      else {
        return true;
      }
    }
    return false;
  }
  function changeImg(sourceElement, previewElement) {
    if(sourceElement.files && sourceElement.files[0]) {
      var reader = new FileReader();
      reader.onload = function(ee) {
        previewElement.src = ee.target.result;
      }
      reader.readAsDataURL(sourceElement.files[0]);
    }
  }

function newLibroChecker(){
    if (document.getElementById("form_libro")) {
        var libroControls = {};
        // libroControls["author"] = [[isNotEmpty, "L'autore deve essere selezionato"]];
        // libroControls["genre"] = [[isNotEmpty, "Il genere deve essere selezionato"]];
        libroControls["titolo"]=[[checkTitle,"Il titolo non può essere vuoto e deve contenere almeno 5 caratteri."]];
        libroControls["trama"]=[[isNotEmpty,"La trama non può essere vuota"],
                                [plotLowerBound,"La trama deve contenere almeno 50 caratteri."],
                                [plotUpperBound,"La trama deve contenere al massimo 500 caratteri."]];
        libroControls["alt_text"]=[[checkAltText,"Il testo alternativo non può essere vuoto e deve contenere almeno 5 caratteri"]];
        addFocusOutEvent(libroControls);

        var libroButton = document.getElementById("libroButton");
        libroButton.addEventListener("click", (event) => {
            if (!clickController(libroControls)) event.preventDefault();
        });
        var newImg = document.getElementById("fileToUpload");
        newImg.addEventListener("change", function(e) {
        if(checkPhoto(e.target))
            changeImg(newImg, document.getElementById("img_cover_preview"));
        });
    }
}


/* --------- add checks on page load ---------- */

window.onload = function () {
    loginChecker();
    reviewChecker();
    registrazioneChecker();
    newAutoreChecker();
    newGenereChecker();
    modificaUtenteChecker();
    newLibroChecker();
    contattiChecker();
};
