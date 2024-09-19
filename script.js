const auth = []
const sendData = (data) => {
    return new Promise(() => {
        fetch('Security/controller.php?action=authenticate',{
            method: 'post',
            headers:{
                'Content-type':'application/json'
            },
            body:JSON.stringify(data)
        })
        .then((response) =>{
            response.json().then(r => {
                r.token && auth.push(r)

                try {
                    if (auth[0].token != null){
                        localStorage.setItem('jwtToken',auth[0].token)
                        window.location.href = "dashboard.html"
                    }else{
                        alert('Erreur')
                    }
                }catch(ex){
                    alert("Utilisateur non identifié")
                }
            })

        })

    })
} 

document.getElementById('authentificationForm').addEventListener('submit', (e) => {
    e.preventDefault()
    const authentificationForm = new FormData(document.getElementById('authentificationForm'))
    const data = {}
    authentificationForm.forEach((value,key) => {
        data[key] = value
    })
    //console.log(data);
    
    sendData(data)
        .then(result => {console.log(result);})
        .catch(error => {console.log(error);
})





})