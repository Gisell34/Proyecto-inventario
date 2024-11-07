// Función para buscar pedidos
function buscarPedido() {
    let input = document.getElementById('buscarPedido').value.toLowerCase();
    let pedidoItems = document.getElementsByClassName('product-item');

    for (let i = 0; i < pedidoItems.length; i++) {
        let pedidoID = pedidoItems[i].getElementsByTagName('h3')[0].innerText.toLowerCase();
        if (pedidoID.includes(input)) {
            pedidoItems[i].style.display = "";
        } else {
            pedidoItems[i].style.display = "none";
        }
    }
}