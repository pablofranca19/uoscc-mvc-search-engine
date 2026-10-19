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
        const setMenuOpen = (isOpen) => {
            button.setAttribute('aria-expanded', String(isOpen));
            button.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
            menu.classList.toggle('hidden', !isOpen);
        };
        button.classList.remove('hidden');
        button.classList.add('grid');

        button.addEventListener('click', () => {
            setMenuOpen(button.getAttribute('aria-expanded') !== 'true');
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
                setMenuOpen(false);
                button.focus();
            }
        });
        document.addEventListener('click', (event) => {
            if (!menu.contains(event.target) && !button.contains(event.target)) {
                setMenuOpen(false);
            }
        });
        menu.addEventListener('click', (event) => {
            if (event.target.closest('a')) setMenuOpen(false);
        });
        window.matchMedia('(min-width: 1024px)').addEventListener('change', (event) => {
            if (event.matches) setMenuOpen(false);
        });
    }

    document.querySelector('[data-form-errors]')?.focus();

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
    const themeToggle = document.getElementById('theme-toggle');

    if (themeToggle) {
        const root = document.documentElement;
        const syncThemeToggle = () => {
            const isDark = root.classList.contains('dark');
            themeToggle.setAttribute('aria-pressed', String(isDark));
            themeToggle.setAttribute('aria-label', isDark ? 'Ativar modo claro' : 'Ativar modo escuro');
        };
        themeToggle.addEventListener('click', () => {
            root.classList.toggle('dark');
            localStorage.theme = root.classList.contains('dark') ? 'dark' : 'light';
            syncThemeToggle();
        });
        syncThemeToggle();
    }
});
