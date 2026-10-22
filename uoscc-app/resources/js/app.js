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

    const menuButton = document.getElementById('menu-button');
    const menu = document.getElementById('main-menu');
    const menuOverlay = document.getElementById('menu-overlay');

    if (menuButton && menu) {
        const setMenuOpen = (isOpen) => {
            menuButton.setAttribute('aria-expanded', String(isOpen));
            menuButton.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
            menu.classList.toggle('translate-x-full', !isOpen);
            menu.classList.toggle('translate-x-0', isOpen);
            document.body.classList.toggle('overflow-hidden', isOpen);
            if (menuOverlay) {
                menuOverlay.classList.toggle('invisible', !isOpen);
                menuOverlay.classList.toggle('opacity-0', !isOpen);
                menuOverlay.classList.toggle('opacity-100', isOpen);
            }
        };

        setMenuOpen(false);

        menuButton.addEventListener('click', () => {
            setMenuOpen(menuButton.getAttribute('aria-expanded') !== 'true');
        });
        menuOverlay?.addEventListener('click', () => setMenuOpen(false));
        document.querySelectorAll('[data-menu-close]').forEach((closeButton) => {
            closeButton.addEventListener('click', () => setMenuOpen(false));
        });
        menu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => setMenuOpen(false));
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && menuButton.getAttribute('aria-expanded') === 'true') {
                setMenuOpen(false);
                menuButton.focus();
            }
        });
    }

    document.querySelector('[data-form-errors]')?.focus();

    document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
        const field = document.getElementById(toggle.dataset.passwordToggle);
        if (!field) return;
        toggle.hidden = false;
        toggle.addEventListener('click', () => {
            const show = field.type === 'password';
            field.type = show ? 'text' : 'password';
            toggle.textContent = show ? 'Ocultar senha' : 'Mostrar senha';
            toggle.setAttribute('aria-pressed', String(show));
        });
    });

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

    const counters = document.querySelectorAll('[data-counter]');

    if (counters.length) {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        const runCounter = (element) => {
            const target = Number.parseInt(element.dataset.counter, 10) || 0;
            const duration = 2200;

            if (prefersReducedMotion || target === 0) {
                element.textContent = String(target);
                return;
            }

            const startTime = performance.now();
            const tick = (now) => {
                const progress = Math.min((now - startTime) / duration, 1);
                const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                element.textContent = String(Math.round(eased * target));
                if (progress < 1) {
                    requestAnimationFrame(tick);
                } else {
                    element.textContent = String(target);
                }
            };

            requestAnimationFrame(tick);
        };

        if ('IntersectionObserver' in window && !prefersReducedMotion) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        observer.unobserve(entry.target);
                        runCounter(entry.target);
                    }
                });
            }, { threshold: 0.4 });

            counters.forEach((counter) => {
                counter.textContent = '0';
                observer.observe(counter);
            });
        } else {
            counters.forEach(runCounter);
        }
    }
});
