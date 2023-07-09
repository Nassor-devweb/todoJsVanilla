import '../assets/styles/styles.scss'

const btn = document.querySelector('form');
const error = document.querySelector('.error');

btn.addEventListener('submit', (e) => {
    e.preventDefault();
    let user = {}
    const email_user = document.querySelector('#email');
    const password_user = document.querySelector('#password');
    user.email = email_user.value;
    user.password = password_user.value;
    error.textContent = ''; 
    const queryFetch = fetch('http://localhost:3000/login.php',{
        method : 'POST',
        headers : {
            'Content-Type' : 'application/json',
        },
        body : JSON.stringify(user)
    })

    queryFetch.then( async resp => {
        try {
            console.log(resp)
            if(!resp.ok){
                const userLog = await resp.json();
                error.textContent = userLog.erreur; 
            }else{
                location.href = './todo.html'
            }
            
        }catch(err){
            //console.log(err)
        }
    })

})