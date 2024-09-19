token = localStorage.getItem('jwtToken')
user = []
if (!token){
    window.location.href = "/auth/";
    alert('cette page est protégée, veuillez-vous authentifier ')
    localStorage.removeItem('jwtToken')

}else{
    fetch('Security/controller.php?action=checkToken',{
        method: 'post',
        headers:{
            'Content-type':'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
        .then((response) => response.json().then(r => {


            if (r.status != 200){
                alert(r.message)
                localStorage.removeItem('jwtToken')
                window.location.href = "../../index.html";
            }else{
                user.push(r.data[0])
                console.log(user[0].firstName)
                let message = document.getElementsByClassName('h2')
                console.log(message[0])
                message[0].innerText = 'Bienvenue ' + user[0].firstName + ' ' + user[0].lastName;
            }
        }));
}


const deconnexion = () => {
    if (confirm("Voulez-vous vous deconnectez ?")){
        localStorage.removeItem('jwtToken')
        window.location.href =  "/auth/";
    }

}