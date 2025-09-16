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
});
