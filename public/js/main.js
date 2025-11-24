setTimeout(() => {
        let alert = document.getElementById('errorAlert');
        if (alert) {
            alert.style.transition = "0.5s";
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000); // 5000 ms يعني 5 ثواني

    let input = document.querySelector('input[name="search"]');
let results = document.getElementById('liveResults');

input.addEventListener('keyup', function () {
    let value = this.value.trim();
    if (value.length < 2) {
        results.innerHTML = "";
        return;
    }

    fetch(`/search-products?search=${value}`)
        .then(res => res.json())
        .then(data => {
            results.innerHTML = "";
            data.forEach(product => {
                let item = document.createElement('li');
                item.className = "list-group-item list-group-item-action";
                item.textContent = product.product_name + " | " + product.barcode;
                item.onclick = () => addToCart(product.id);
                results.appendChild(item);
            });
        });
});

function addToCart(id) {
    fetch(`/add-to-cart`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ id })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            location.reload(); // 🔥 يعيد تحميل الصفحة لعرض المنتج في الجدول
        }
    });
}