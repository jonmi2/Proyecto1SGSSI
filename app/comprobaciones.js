//aqui tienen que estar las comprobaciones tras darle a un botón de enviar formulario o alguna cosa así
// comprobar q lo que metes tiene el formato adecuado DNI, gmail....

function comprobarFormato() {
    const nombreApellidos = document.getElementById('nombre_apellidos').value.trim();
    const dni = document.getElementById('dni').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const fechaNacimiento = document.getElementById('fecha_nacimiento').value.trim();
    const mail = document.getElementById('mail').value.trim();

    // Validación de nombre y apellidos (solo texto)
    const nombreApellidosRegex = /^[a-zA-ZÁÉÍÓÚÑáéíóúñ\s]+$/;
    if (!nombreApellidosRegex.test(nombreApellidos)) {
        alert('El nombre y apellidos solo deben contener letras.');
        return false;
    }

    // Validación de DNI con letra
    const dniRegex = /^[0-9]{8}[A-Za-z]$/;
    if (!dniRegex.test(dni) || !validarLetraDNI(dni)) {
        alert('El formato del DNI es incorrecto o la letra no corresponde al número.');
        return false;
    }

    // Validación de teléfono (9 dígitos)
    const telefonoRegex = /^[0-9]{9}$/;
    if (!telefonoRegex.test(telefono)) {
        alert('El teléfono debe tener 9 dígitos.');
        return false;
    }

    // Validación de fecha de nacimiento (aaaa-mm-dd)
    const fechaNacimientoRegex = /^\d{4}-\d{2}-\d{2}$/;
    if (!fechaNacimientoRegex.test(fechaNacimiento)) {
        alert('La fecha de nacimiento debe estar en formato aaaa-mm-dd.');
        return false;
    }

    // Validación de email (formato válido)
    const mailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!mailRegex.test(mail)) {
        alert('El formato del correo electrónico es inválido.');
        return false;
    }

    // Si todas las validaciones son correctas
    return true;
}

// Función para validar el formulario antes de enviarlo
function validarFormulario() {
    return comprobarFormato(); // Llama a la función comprobarFormato
}


// Función para validar que la letra del DNI es correcta
function validarLetraDNI(dni) {
    const numero = parseInt(dni.slice(0, 8), 10);
    const letra = dni.charAt(8).toUpperCase();
    const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    const letraCorrecta = letras[numero % 23];
    return letra === letraCorrecta;
}


function mostrarAlerta(mensaje) {
    alert(mensaje);
}


