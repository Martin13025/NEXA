document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.add-to-cart');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const productId = btn.getAttribute('data-id');
            let cart = JSON.parse(localStorage.getItem('cart')) || {};

            if (cart[productId]) {
                cart[productId]++;
            } else {
                cart[productId] = 1;
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            document.cookie = 'cart=' + encodeURIComponent(JSON.stringify(cart)) + '; path=/; max-age=' + (7*24*60*60);

            alert('Товар добавлен в корзину');
        });
    });
});

const clearBtn = document.createElement('button');
clearBtn.textContent = 'Очистить корзину';
clearBtn.onclick = () => {
    localStorage.removeItem('cart');
    document.cookie = 'cart=; path=/; max-age=0';
    alert('Корзина очищена');
    location.reload();
};
document.body.prepend(clearBtn);

