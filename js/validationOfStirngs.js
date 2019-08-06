// JavaScript source code
function validateEmail(mail) {
    mail = mail.trim();
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
        return true;
    }
    return false;
}

function validateName(str) {
    str = str.trim();
    str = str.toLowerCase();
    //Solo puede contener letras y espacios
   var reg = /[A-Za-zñÑ-áéíóúÁÉÍÓÚ\s\t-]/;
    if (reg.test(str))
        return true;

    else
        return false;
}

function validateCalle(str) {
    str = str.trim();
    //Solo puede contener letras y espacios

    if (/^([a-zñáéíóú.A-ZÑ.]+\s)*[a-zñáéíóú.A-ZÑ.]+$/.test(str))
        return true;

    else
        return false;
}

function validateRFC(str) {
    str = str.trim();

    if (str.match(/^[a-zA-Z0-9.-]*$/)) {
        if (str.length === 12) { //Persona moral
            var Nombre = str.substr(0, 3);
            var Fecha = str.substr(3, 6);
            var Homoclave = str.substr(9, 3);

            if (!validateAlpha(Nombre))
                return "Los primeros 3 digitos del RFC tienen que ser letras del alfabeto para Personas Morales";


            if (!validateNumeric(Fecha))
                return "Los digitos del quinto al octavo del RFC estan conformados por la fecha de nacimiento y solo pueden ser valores numéricos";


            if (!validateAlphaOrNumericOrAlphaNumeric(Homoclave))
                return "Los últimos tres elementos del RFC solo pueden ser alphanuméricos, sin espacios ni caracteres que no se encuentren en el alfabeto";

            return true;
        }

        else if (str.length === 13) { //Personas fisicas
            var Nombre = str.substr(0, 4);
            var Fecha = str.substr(4, 6);
            var Homoclave = str.substr(10, 3);

            if (!validateAlpha(Nombre))
                return "Los primeros 4 digitos del RFC tienen que ser letras del alfabeto para Personas Fisicas";


            if (!validateNumeric(Fecha))
                return "Los digitos del quinto al octavo del RFC estan conformados por la fecha de nacimiento y solo pueden ser valores numéricos";


            if (!validateAlphaOrNumericOrAlphaNumeric(Homoclave))
                return "Los últimos tres elementos del RFC solo pueden ser alphanuméricos, sin espacios ni caracteres que no se encuentren en el alfabeto";


            return true;
        }
        else
            return "El RFC debe contener exactamente 14 elementos para personas fisicas y 13 para personas morales";

        return true;
    }

    else
        return "El RFC solo puede contener letras y números";
}

function validateNumeric(str) {
    str = str.trim();
    if (str === true || str === false)
        return false;

    if (str === '')
        return false;

    if (isNaN(str))
        return false;

    else
        return true;
}

function validateAlpha(str) {
    str = str.trim();
    if (str.match(/[0-9]/i))
        return false;
    else
        return true;
}
function validateAlphaNumeric(str) {
    str = str.trim();
    // Which allows only Alphanumeric.

    //It doesn't allow:

    // Only Alpha
    // Only Numbers

    if (str.match(/((^[0-9]+[a-z]+)|(^[a-z]+[0-9]+))+[0-9a-z]+$/i))
        return true;
    else
        return false;
}

function validateAlphaOrNumericOrAlphaNumeric(str) {
    str = str.trim();
    // Which allows Alphanumeric.
    // Alpha
    // Numbers
    if (str.match(/^([0-9]|[a-z])+([0-9a-z]+)$/i))
        return true;
    else
        return false;
}