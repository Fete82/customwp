/**
 * Hanterar exempel 1 formuläret och skicka formuläret med fetch
  */

let delButton = document.querySelector('#del_trans');
delButton.addEventListener('click', doAjax);

function doAjax(event) {
    event.preventDefault();

    /*
     * Skapa formulär data som skickas  med fetch request:et.
     * Vi skickar alltså bara med action (metoden som ska hantera det) och nonce (csrf)
     *
     * */

    let formData = new FormData();
    formData.append('action', "wcm_del_repos_action");
    formData.append('nonce', delButton.getAttribute('data-nonce'));

    /* myAjax.ajaxurl kommer från vår JS-variabel som vi skapade i wp_localize_script() i wp_plugin.php */
    fetch(myAjax.ajaxurl, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if(data.type === "success") {
                const repoList = document.querySelector('#repo_list');
                repoList.innerHTML = `<li>Inga repon</li>`;

                console.log(data.message);
            }
            else {
                alert("Your like could not be added");
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

/** ---------------------------------------- **/

/**
 * Hanterar exempel 2 formuläret och skicka formuläret med fetch
 */

let deleteTransForm2 = document.querySelector('#delete_trans_form_2');
deleteTransForm2.addEventListener('submit', doAjax2);   // Lyssna på submit på formuläret, kör doAjax2 när det det submittas.

function doAjax2(event) {
    event.preventDefault();

    /*
     * Skapa formulär data som skickas med fetch request:et.
     * Det gör vi igenom JS klassen Formdata, som tar emot ett formulär som input.
     * De kommer den lägga ni alla våra inputs med name, som sedan kan skickas som form post data
     *
     * I exemplet ovan la vi till actoin med appendd(), men nu finns den som en hidden input.
     * */

    let formData = new FormData(deleteTransForm2);

    /* Här använder vi formulärets action attribut istället för ajax variabeln myAjax för URL dit ajax-anroopet ska skickas */
    fetch(deleteTransForm2.getAttribute('action'), {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if(data.type === "success") {
                const repoList = document.querySelector('#repo_list');
                repoList.innerHTML = `<li>Inga repon</li>`;

                console.log(data.message);
            }
            else {
                alert("Your like could not be added");
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

