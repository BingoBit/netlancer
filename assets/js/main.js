document.addEventListener('DOMContentLoaded', () => {
    const menuButton = document.querySelector('[data-menu-button]');
    if (menuButton) {
        menuButton.addEventListener('click', () => {
            const nav = document.getElementById('main-nav');
            nav.classList.toggle('open');
            menuButton.setAttribute('aria-expanded', nav.classList.contains('open'));
        });
    }

    document.querySelectorAll('[data-account-menu]').forEach((menu) => {
        const toggle = menu.querySelector('[data-account-toggle]');
        const dropdown = menu.querySelector('.account-dropdown');
        if (!toggle || !dropdown) {
            return;
        }

        const close = () => {
            menu.classList.remove('open');
            dropdown.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
        };

        toggle.addEventListener('click', (event) => {
            event.stopPropagation();
            const open = menu.classList.toggle('open');
            dropdown.hidden = !open;
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        dropdown.addEventListener('click', (event) => event.stopPropagation());
        document.addEventListener('click', close);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                close();
            }
        });
    });

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-scroll]').forEach((button) => {
        button.addEventListener('click', () => {
            const box = document.getElementById(button.dataset.scroll);
            const atEnd = box.scrollLeft + box.clientWidth >= box.scrollWidth - 5;
            box.scrollTo({ left: atEnd ? 0 : box.scrollLeft + box.clientWidth, behavior: 'smooth' });
        });
    });

    document.querySelectorAll('[data-tabs]').forEach((tabs) => {
        tabs.querySelectorAll('a').forEach((tab) => {
            tab.addEventListener('click', (event) => {
                event.preventDefault();
                tabs.querySelectorAll('a').forEach((a) => a.classList.remove('active'));
                document.querySelectorAll('.tab-panel').forEach((panel) => panel.classList.remove('active'));
                tab.classList.add('active');
                document.querySelector(tab.getAttribute('href')).classList.add('active');
            });
        });
    });
});
