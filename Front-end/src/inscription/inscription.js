import '../assets/styles/styles.scss'

const form = document.querySelector('form');
const error = document.querySelector('.error');

form.addEventListener('submit',(e) => {
    e.preventDefault();

    const name = document.querySelector('#name');
    const email = document.querySelector('#email');
    const password = document.querySelector('#password');
    const photo_user = document.querySelector('#img')

    const user = {
        name : name.value,
        email : email.value,
        password : password.value,
        path_photo : photo_user.files[0]
    }

    let formData = new FormData();
    formData.append('name', user.name);
    formData.append('email', user.email);
    formData.append('password', user.password);
    formData.append('path_photo', user.path_photo);
    console.log(formData)

    const promiseSubmit = fetch('http://localhost:3000/inscription.php', {
        method : "POST",
        body : formData
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
