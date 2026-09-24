/** Behaviour for the CMS at /webcms. Icons are rendered by app.js. */

/* Mobile sidebar */
const sidebar = document.querySelector('[data-sidebar]');
const backdrop = document.querySelector('[data-sidebar-backdrop]');

const setSidebar = (open) => {
    if (! sidebar) {
        return;
    }

    sidebar.classList.toggle('-translate-x-full', ! open);
    backdrop?.classList.toggle('hidden', ! open);
};

document.querySelectorAll('[data-sidebar-open]').forEach((trigger) => {
    trigger.addEventListener('click', () => setSidebar(true));
});

document.querySelectorAll('[data-sidebar-close]').forEach((trigger) => {
    trigger.addEventListener('click', () => setSidebar(false));
});

/* Confirm before destructive submits */
document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (! window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});
