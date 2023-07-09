import '../assets/styles/styles.scss'

const form = document.querySelector('#formTache');
const inputTache = document.querySelector('#tache');
const error = document.querySelector('.error');

form.addEventListener('submit', (e) => {
    e.preventDefault()
    const tacheValue = inputTache.value;
    error.textContent = '';
    const tache = {};
    if(tacheValue.trim()){
        tache['tache'] = tacheValue;
        const fechQuerry = fetch('http://localhost:3000/todo.php', {
            method : 'POST',
            headers : {
                'Content-Type' : 'Application/json'
            },
            body : JSON.stringify(tache)
        });

        fechQuerry.then( async (resp) => {
            if(resp.ok){
                const data = await resp.json()
            }
        })


    }else{
        error.textContent = 'Veuillez saisir une tâche avant de valider !!!'
    }
})
