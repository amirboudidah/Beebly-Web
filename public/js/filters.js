
window.onload = () => {

    const FiltersForm = document.querySelector("#filters");




    document.querySelectorAll("#filters input").forEach(input => {
        input.addEventListener("input", () => {
            console.log("yes");

            const Form = new FormData(FiltersForm);

            const Params = new URLSearchParams();

            const Url = new URL(window.location.href);

            Form.forEach((value, key) => {
                Params.append(key, value);
            });


            console.log(Params.toString());

            fetch(Url.pathname + "?" + Params.toString() + "&ajax=1", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(response =>
                response.json()

            ).then(data => {
                const content = document.querySelector("#content");
                content.innerHTML = data.content;

            }).catch(e => alert(e));

        });


        });


        }


