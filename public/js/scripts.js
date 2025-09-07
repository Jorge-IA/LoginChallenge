
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function (event) {
            const name = document.querySelector("input[name='name']");
            const email = document.querySelector("input[name='email']");
            const password = document.querySelector("input[name='password']");

            let errors = [];

            if (name && name.value.trim() === "") {
                errors.push("El nombre es obligatorio.");
            }

            if (email && email.value.trim() === "") {
                errors.push("El correo electrónico es obligatorio.");
            }

            if (password && password.value.trim() === "") {
                errors.push("La contraseña es obligatoria.");
            }

            if (errors.length > 0) {
                event.preventDefault();
                alert(errors.join("\n"));
            }
        });
    }
});
