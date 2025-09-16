document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formOptions");
    if (!form) return;

    let timer;

    form.addEventListener("input", () => {
        clearTimeout(timer);
        timer = setTimeout(saveFormData, 1000); // автосохранение через 1с
    });

    function saveFormData() {
        let data = {
            phones: [],
            socials: [],
            settings: {}
        };

        // телефоны
        document.querySelectorAll(".phones_item").forEach(item => {
            let phone = item.querySelector(".phone_input--number")?.value || "";
            let desc  = item.querySelector(".phone_input--description")?.value || "";
            if (phone || desc) {
                data.phones.push({ value: phone, description: desc });
            }
        });

        // соцсети
        document.querySelectorAll(".social_item").forEach(item => {
            let name = item.querySelector(".social_input--name")?.value || "";
            let link = item.querySelector(".social_input--link")?.value || "";
            if (name || link) {
                data.socials.push({ name: name, link: link });
            }
        });

        // настройки
        data.settings.address  = form.querySelector("input[name='address']")?.value || "";
        data.settings.linkMap  = form.querySelector("input[name='linkMap']")?.value || "";
        data.settings.workTime = form.querySelector("input[name='workTime']")?.value || "";

        // отправка на сервер
        fetch("/api/formOption/save/", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
            .then(res => res.json())
            .then(result => {
                console.log("Автосохранение:", result);
            })
            .catch(err => console.error("Ошибка автосохранения:", err));
    }

    function validatePhone(phone) {
        // Убираем все пробелы, скобки и дефисы для проверки
        const digitsOnly = phone.replace(/\D/g, '');
        // Должно быть 11 цифр и начинаться с 7
        return digitsOnly.length === 11 && digitsOnly.startsWith('7');
    }

    function validateSocialLink(link) {
        const urlRegex = /^(https?:\/\/).+/;
        return urlRegex.test(link.trim());
    }

    function removeError(input) {
        const next = input.nextElementSibling;
        if (next && next.classList.contains('error-message')) {
            next.remove();
        }
    }

    function showError(input, message) {
        removeError(input);
        const span = document.createElement('span');
        span.className = 'error-message';
        span.style.color = 'red';
        span.style.fontSize = '12px';
        span.textContent = message;
        input.insertAdjacentElement('afterend', span);
    }

    // Телефоны
    const phoneInputs = document.querySelectorAll('.phone_input--number');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (!validatePhone(input.value)) {
                showError(input, 'Неверный формат телефона');
            } else {
                removeError(input);
            }
        });
    });

    // Ссылки соцсетей
    const socialLinks = document.querySelectorAll('.social_input--link');
    socialLinks.forEach(input => {
        input.addEventListener('input', function() {
            if (!validateSocialLink(input.value)) {
                showError(input, 'Неверная ссылка');
            } else {
                removeError(input);
            }
        });
    });
});
