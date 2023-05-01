
window.onload = () => {




    const dates = window.app.dates;
    const propositionCount = window.app.propositionCount;




    let proposition = document.querySelector("#PropositionStat");

    let chart = new Chart(proposition, {
        type: "line",
        data: {

            labels:dates ,
            datasets: [{
                label: "Nombre de proposition",
                data:  propositionCount
            }]
        },

    })



    const dateForm = document.querySelector("#dateForm");
    const dateValue = document.querySelector("#bdaymonth");


    const line = document.querySelector("#line");
    const bar = document.querySelector("#bar");

    line.addEventListener("click", () => {
        chart.config.type="line";
        chart.update();

    });

    bar.addEventListener("click", () => {
        chart.config.type="bar";
        chart.update();

    });



      dateValue.addEventListener("input", () => {



            const Url = new URL(window.location.href);




            console.log(Url.pathname + "?" + "date="+dateValue.value+"&ajax=1");

            fetch(Url.pathname + "?" + "date="+dateValue.value+"&ajax=1", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(response =>
                response.json()

            ).then(data => {

                 chart = new Chart(proposition, {
                    type: "line",
                    data: {

                        labels: data['dates'] ,
                        datasets: [{
                            label: "Nombre de proposition",
                            data:  data['propositionCount']
                        }]
                    },

                })



            }).catch(e => alert(e));

        });


    }







