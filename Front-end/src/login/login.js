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
                console.log('ok')

                const userLog = await resp.json();
                error.textContent = userLog.erreur; 
                console.log(userLog)

            }else{
                //location.href = './login.html'
                const userLog = await resp.json();
                console.log(userLog);
            }
            
        }catch(err){
            //console.log(err)
        }
    })

})