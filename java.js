console.log("Sistema UTSEM cargado correctamente");


function mostrarFormularioBusqueda() {
  const form = document.getElementById("formularioBusqueda");
  if (form) {
    form.style.display = form.style.display === "none" ? "block" : "none";
  }
}

window.mostrarFormularioBusqueda = mostrarFormularioBusqueda;
