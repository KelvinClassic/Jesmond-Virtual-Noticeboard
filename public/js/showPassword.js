function showPassword(elem){
    if(elem.classList.contains("fa-eye") ){
        elem.classList.remove("fa-eye");
        elem.classList.add("fa-eye-slash");
        elem.previousElementSibling.setAttribute('type', 'text');
    }
    else if(elem.classList.contains("fa-eye-slash") ){
        elem.classList.remove("fa-eye-slash");
        elem.classList.add("fa-eye");
        elem.previousElementSibling.setAttribute('type', 'password');
    }
}
