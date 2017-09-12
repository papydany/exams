var v_r;

function checkEmail(strng, say) {
    var error = "";
    if (strng == "") {
        error = say == "" ? "You didn't enter an email address.\n" : say + ".\n";
        v_r = strng
    }
    var emailFilter = /^.+@.+\..{2,3}$/;
    if (!(emailFilter.test(strng))) {
        error = say == "" ? "Please enter a valid email address.\n" : say + ".\n";
        v_r = strng
    } else {
        var illegalChars = /[\(\)\&lt;\&gt;\,\;\:\\\"\[\]]/;
        if (strng.match(illegalChars)) {
            error = "The email address contains illegal characters.\n";
            v_r = strng
        }
    }
    return error
}
function checkPhone(strng, l) {
    l = (l == "") ? 12 : l;
    var error = "";
    if (strng == "") {
        error = "You didn't enter a phone number.\n";
        v_r = strng
    }
    var stripped = strng.replace(/[\(\)\.\-\ ]/g, "");
    if (isNaN(parseInt(stripped))) {
        error = "The phone number contains illegal characters.";
        v_r = strng
    }
    if (!(stripped.length == l)) {
        error = "The phone number is the wrong length. Make sure you included an area code.\n";
        v_r = strng
    }
    return error
}
function checkPassword(strng) {
    var error = "";
    if (strng == "") {
        error = "You didn't enter a password.\n";
        v_r = strng
    }
    var illegalChars = /[\W_]/;
    if ((strng.length < 6) || (strng.length > 8)) {
        error = "The password is the wrong length.\n";
        v_r = strng
    } else {
        if (illegalChars.test(strng)) {
            error = "The password contains illegal characters.\n";
            v_r = strng
        } else {
            if (!((strng.search(/(a-z)+/)) && (strng.search(/(A-Z)+/)) && (strng.search(/(0-9)+/)))) {
                error = "The password must contain at least one uppercase letter, one lowercase letter, and one numeral.\n";
                v_r = strng
            }
        }
    }
    return error
}
function checkUsername(strng) {
    var error = "";
    if (strng == "") {
        error = "You didn't enter a username.\n";
        v_r = strng
    }
    var illegalChars = /\W/;
    if ((strng.length < 4) || (strng.length > 10)) {
        error = "The username is the wrong length.\n";
        v_r = strng
    } else {
        if (illegalChars.test(strng)) {
            error = "The username contains illegal characters.\n";
            v_r = strng
        }
    }
    return error
}
function isEmpty(strng, say) {
    var error = "";
    if (strng.value.length == 0) {
        error = say == "" ? "The mandatory text area has not been filled in.\n" : say + ".\n";
        v_r = strng
    }
    return error
}
function checkRadio(radb, say) {
    var error = "";
    var cue = false;
    var n = radb.length;
    for (var i = 0; i < n; i++) {
        if (radb[i].checked) {
            cue = true;
            break
        }
    }
    if (!cue) {
        error = say == "" ? "Please check a radio button.\n" : say + ".\n";
        v_r = radb[0];
    }
    return error
}
function checkDropdown(choice, say) {
    var error = "";
    if (choice.selectedIndex == 0) {
        error = say == "" ? "You didn't choose an option from the drop-down list.\n" : say + ".\n";
        v_r = choice
    }
    return error
};