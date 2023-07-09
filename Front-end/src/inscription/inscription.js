import '../assets/styles/styles.scss'

const form = document.querySelector('form');
const error = document.querySelector('.error');

form.addEventListener('submit',(e) => {
    e.preventDefault();

    const name = document.querySelector('#name');
    const email = document.querySelector('#email');
    const password = document.querySelector('#password');

    const user = {
        name : name.value,
        email : email.value,
        password : password.value,
    }
    
    console.log(user.name,user.email,user.password)
    const promiseSubmit = fetch('http://localhost:3000/inscription.php', {
        headers : {
            'Content-Type' : 'application/json'
        },
        method : "POST",
        body : JSON.stringify(user)
    })

    promiseSubmit.then(async resp => {
        try {
            if(!resp.ok){
                const body = await resp.json();
                error.textContent = body.erreur;            
            }else{
                location.href = './login.html'
            }
        }catch(err){
            console.log(err)
        }
    })

     
})
