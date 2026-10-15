import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const normalizeSearch = (value) => value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase('pt-BR').trim();
    document.querySelectorAll('[data-discovery]').forEach((section) => {
        const form = section.querySelector('[data-discovery-form]');
        const activities = [...section.querySelectorAll('[data-activity], [data-organization-card], [data-space]')];
        const updateResults = () => {
            const filters = Object.fromEntries(new FormData(form));
            const words = normalizeSearch(filters.search).split(/\s+/).filter(Boolean);
            let count = 0;
            activities.forEach((activity) => {
                const searchable = normalizeSearch(activity.dataset.search);
                const matches = words.every((word) => searchable.includes(word)) &&
                    ['organization', 'type', 'region', 'date', 'kind', 'registration'].every((key) => !filters[key] || activity.dataset[key] === filters[key]) &&
                    (!filters.capacity || (Number(filters.capacity) > 0 && Number(activity.dataset.capacity) >= Number(filters.capacity))) &&
                    (!filters.structure || JSON.parse(activity.dataset.structure || '[]').includes(filters.structure));
                activity.classList.toggle('hidden', !matches);
                if (matches) count += 1;
            });
            section.querySelector('[data-result-count]').textContent = count === 1 ? '1 resultado encontrado' : `${count} resultados encontrados`;
            section.querySelector('[data-empty-results]').classList.toggle('hidden', count !== 0);
        };
        form.addEventListener('submit', (event) => event.preventDefault());
        form.addEventListener('input', updateResults);
        form.addEventListener('change', updateResults);
        form.addEventListener('reset', () => queueMicrotask(updateResults));
        section.querySelector('[data-clear-filters]').addEventListener('click', () => {
            form.reset();
            form.elements.namedItem('search').focus();
        });
        updateResults();
    });

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
