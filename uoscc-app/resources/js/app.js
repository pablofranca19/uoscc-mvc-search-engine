import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('menu-button');
    const menu = document.getElementById('main-menu');

    if (button && menu) {
        button.addEventListener('click', () => {
            const isOpen = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', String(!isOpen));
            menu.classList.toggle('hidden', isOpen);
        });
    }

    document.querySelectorAll('[data-filter]').forEach((input) => {
        input.addEventListener('input', () => {
            const query = input.value.toLowerCase().trim();
            const target = document.querySelector(input.dataset.filter);
            if (!target) return;
            target.querySelectorAll('[data-filter-item]').forEach((item) => {
                item.classList.toggle('hidden', query && !item.textContent.toLowerCase().includes(query));
            });
        });
    });
    document.querySelectorAll('[data-region-filter]').forEach((select) => {
        select.addEventListener('change', () => {
            const target = document.querySelector(select.dataset.regionFilter);
            if (!target) return;
            const region = select.value;
            target.querySelectorAll('[data-filter-item]').forEach((item) => {
                item.classList.toggle('hidden', region !== 'Todas as regiões' && item.dataset.region !== region);
            });
        });
    });

});
