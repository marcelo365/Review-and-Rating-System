let profile = document.getElementsByClassName("profile")[0];

function mostrarPerfil() {
    profile.classList.toggle('active');
}

window.onscroll = () => {
    profile.classList.remove('active');
}