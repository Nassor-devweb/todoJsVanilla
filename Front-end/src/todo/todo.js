import '../assets/styles/styles.scss'

const form = document.querySelector('#formTache');
const inputTache = document.querySelector('#tache');
const error = document.querySelector('.error');
const content = document.querySelector('.content');
const contentImg = document.querySelector('.nav__list');
const profilMenu = document.querySelector('.userprofil');
const disconect = document.querySelector('.disconect');

profilMenu.addEventListener('mouseover',(e) =>{
    profilMenu.removeAttribute('id')
})

profilMenu.addEventListener('mouseout',(e) =>{
    profilMenu.setAttribute('id','visiblemenu')
})

disconect.addEventListener('click',async (e)=>{
    profilMenu.dispatchEvent(new Event('mouseout'))
    try{
        const disconectQuerry = fetch('http://localhost:3000/logout.php');
        disconectQuerry.then((resp) =>{
            location.href = './login.html'
        })
    }catch (err){
        console.log(err)
    }
})


function deleteTache(idTache){
    const id = {};
    id.id_tache = idTache;
    const deleteQuerry = fetch('http://localhost:3000/todo.php', {
        method : 'DELETE',
        headers : {
            'Content-Type' : 'application/json'
        },
        body : JSON.stringify(id)
    })

    deleteQuerry.then(async (resp) =>{
        if(resp.ok){
            const allTaches = await resp.json();
            afficherListe(allTaches);
        }else{
            location.href = './login.html';
        }
    })
}

function finishTache(valueCheck,idTache){
    const finished_tache = {};
    finished_tache.value = valueCheck;
    finished_tache.id_tache = idTache;
    const finishedQuerry = fetch('http://localhost:3000/todo.php',{
        method : 'PATCH',
        headers : {
            'Content-Type' : 'application/json'
        },
        body : JSON.stringify(finished_tache)
    })
    finishedQuerry.then(async (resp) =>{
        if(resp.ok){
            const data = await resp.json();
            afficherListe(data)
            console.log(data)
        }else{
            location.href = './login.html'
        }
    })
}

function EditTache(newValue,idTache){
    const finished_tache = {};
    finished_tache.nom_tache = newValue;
    finished_tache.id_tache = idTache;
    const finishedQuerry = fetch('http://localhost:3000/udapteTache.php',{
        method : 'PATCH',
        headers : {
            'Content-Type' : 'application/json'
        },
        body : JSON.stringify(finished_tache)
    })
    finishedQuerry.then(async (resp) =>{
        if(resp.ok){
            const data = await resp.json();
            afficherListe(data)
            console.log(data)
        }else{
            location.href = './login.html'
        }
    })

}


function afficherListe(dataListe){
    if(dataListe['listTache'].length > 0){
        const liContent = dataListe['listTache'].map((curr) => {

            const li = document.createElement('li'); // liContent
            li.setAttribute('class','task');

            const divValue = document.createElement('div'); // divContent
            divValue.className = 'ckeckValue';
            const input = document.createElement('input');
            input.setAttribute('type','checkbox');
            if(curr.finished_tache){
                input.checked = true;
            }
            input.addEventListener('change',(e)=>{
                if(e.target.checked){
                    finishTache(1,curr.id_tache);
                }else{
                    finishTache(0,curr.id_tache);
                }
            })
            const value = document.createElement('span');
            value.setAttribute('id',curr.id_tache);
            value.textContent = curr.nom_tache;
            divValue.append(input,value);

            value.addEventListener('click',() => {
                iEdit.dispatchEvent(new Event('click'))
            })

//------------------------------ContentIcone-------------------------------

            const divIcone = document.createElement('div'); // divContent
            divIcone.setAttribute('class', 'icone');
            divIcone.classList.add(curr.id_tache);
            const iEdit = document.createElement('i');
            iEdit.classList.add('fa-solid','fa-pen-to-square');

//------------------------------InputEdit-----------------------------------
            const inputEdit = document.createElement('input');
            inputEdit.setAttribute('name','udapte_tache');
            inputEdit.setAttribute('id','udapte_tache');

            const divBtnValidate = document.createElement('div');
            divBtnValidate.className = 'btn-validate';

//------------------------------divIconeValidationEdition-----------------------------------

            const divIcoValidate = document.createElement('div');
            divIcoValidate.classList.add('icoValidate');

//------------------------------iconeValidation-----------------------------------
           
            const icoValidate = document.createElement('i');
            icoValidate.classList.add('fa-regular','fa-circle-check');
            divIcoValidate.append(icoValidate);

//------------------------------divIconeCancelEdition-----------------------------------
            const divIcoCancel = document.createElement('div');
            divIcoCancel.classList.add('icoCancel');

//------------------------------iconeValidation-----------------------------------
           
            const icoCancel = document.createElement('i');
            icoCancel.classList.add('fa-solid','fa-xmark');
            divIcoCancel.append(icoCancel)

//------------------------------ContentValidationCancel-----------------------------------
            divBtnValidate.append(divIcoValidate,divIcoCancel);

           
            
            divIcoValidate.addEventListener('click',(e) => {
                const newTache = inputEdit.value;
                if(newTache.trim()){
                    EditTache(newTache,curr.id_tache)
                }
                inputEdit.replaceWith(value);
                divBtnValidate.replaceWith(divIcone);
            })

            divIcoCancel.addEventListener('click', ()=>{
                inputEdit.replaceWith(value);
                divBtnValidate.replaceWith(divIcone);
            })

            iEdit.addEventListener('click',(e)=>{
                inputEdit.value = '';
                value.replaceWith(inputEdit);
                divIcone.replaceWith(divBtnValidate);
                inputEdit.focus()
            })

            const iDelete = document.createElement('i');
            iDelete.classList.add('fa-solid','fa-trash-can');
            iDelete.addEventListener('click',(e)=>{
                deleteTache(curr.id_tache);
            },{
                capture : true
            })
            divIcone.append(iEdit,iDelete);

            li.append(divValue,divIcone);

            return li;
        }) 
        const arrowProfile = document.createElement('i')
        arrowProfile.setAttribute('class','fa-solid');
        arrowProfile.classList.add('fa-sort-down');
        arrowProfile.addEventListener('mouseover',(e)=>{
            profilMenu.removeAttribute('id');
        })
        const photo = document.createElement('img');
        photo.setAttribute('src',dataListe['dataUser'].photo_user)
        photo.setAttribute('alt','photo de profil');
        photo.setAttribute('id','photoUser')
        const photoUser = document.querySelector('#photoUser');
        if(photoUser){
            photoUser.remove();
        }
        contentImg.append(photo,arrowProfile);

        const listInsert = document.querySelector('.list')
        const listContainer = document.createElement('div');
        listContainer.setAttribute('class','list')
        listContainer.append(...liContent);
        if(listInsert){
            listInsert.remove();
        }
        content.insertAdjacentElement('beforeend',listContainer);
    }else{
        const listInsert = document.querySelector('.list')
        if(listInsert){
            listInsert.remove()
        }
    }
}


function getAllTaches() {
    try {
        fetch('http://localhost:3000/todo.php')
        .then(async resp => {
            if(resp.ok){
                const data = await resp.json();
                afficherListe(data);
                console.log(data)
            }else{
                location.href = './login.html'
            }
        })
    }catch(err){
        location.href = './login.html'
    }
    
}

getAllTaches()

form.addEventListener('submit', (e) => {
    e.preventDefault()
    const tacheValue = inputTache.value;
    error.textContent = '';
    const tache = {};
    inputTache.value = '';
    if(tacheValue.trim()){
        tache['tache'] = tacheValue;
        const fechQuerry = fetch('http://localhost:3000/todo.php', {
            method : 'POST',
            headers : {
                'Content-Type' : 'Application/json'
            },
            body : JSON.stringify(tache)
        });

        fechQuerry.then( async resp => {
            if(resp.ok){
                try {
                    console.log(resp)
                    const data = await resp.json()
                    afficherListe(data);   
                    console.log(data)
                }catch(err){
                    console.log(err)
                }
            }else{
                location.href = './login.html'
            }
        })
    }else{
        error.textContent = 'Veuillez saisir une tâche avant de valider !!!'
    }
})
