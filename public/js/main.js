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

const searchInput = document.getElementById('search');
const resultsList = document.getElementById('results');
let selectedIndex = -1; // keeps track of highlighted item

searchInput.addEventListener('keyup', function(e) {
    const query = this.value.trim();

    // Handle arrow keys and Enter
    const items = resultsList.querySelectorAll('li');
    if(e.key === 'ArrowDown') {
        if(items.length > 0){
            selectedIndex = (selectedIndex + 1) % items.length;
            highlightItem(items);
        }
        return;
    } else if(e.key === 'ArrowUp') {
        if(items.length > 0){
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            highlightItem(items);
        }
        return;
    } else if(e.key === 'Enter') {
        if(selectedIndex >= 0 && items[selectedIndex]){
            searchInput.value = items[selectedIndex].textContent;
            resultsList.style.display = 'none';
        }
        return;
    }

    // Normal typing: fetch results
    if(query.length < 1){
        resultsList.style.display = 'none';
        resultsList.innerHTML = '';
        selectedIndex = -1;
        return;
    }

    fetch(`/live-search?q=${query}`)
        .then(response => response.json())
        .then(data => { 
            resultsList.innerHTML = '';
            selectedIndex = -1;

            if(data.length === 0){
                resultsList.innerHTML = '<li style="padding: 8px;">No results found</li>';
            } else {
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.textContent = item.product_name;
                    li.style.padding = '8px';
                    li.style.cursor = 'pointer';

                    li.addEventListener('click', () => {
                        searchInput.value = item.product_name;
                        resultsList.style.display = 'none';
                    });

                    resultsList.appendChild(li);
                });
            }
            resultsList.style.display = 'block';
        })
        .catch(error => console.error(error));
});

// Highlight item function
function highlightItem(items){
    items.forEach((li, index) => {
        li.style.background = (index === selectedIndex) ? '#a1a1a1b7' : '';
    });
}

// Hide dropdown on click outside
document.addEventListener('click', function(event){
    if(!searchInput.contains(event.target) && !resultsList.contains(event.target)){
        resultsList.style.display = 'none';
    }
});
