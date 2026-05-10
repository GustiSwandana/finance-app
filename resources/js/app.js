import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const themeStorageKey = 'theme';

const getPreferredTheme = () => {
    const storedTheme = window.localStorage.getItem(themeStorageKey);

    if (storedTheme === 'light' || storedTheme === 'dark') {
        return storedTheme;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

const applyTheme = (theme) => {
    const isDark = theme === 'dark';

    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.dataset.theme = theme;
};

const syncThemeToggles = (theme) => {
    const isDark = theme === 'dark';

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.setAttribute('aria-pressed', isDark ? 'true' : 'false');

        const label = button.querySelector('[data-theme-label]');
        if (label) {
            label.textContent = isDark ? 'Light Mode' : 'Dark Mode';
        }

        const darkIcon = button.querySelector('[data-theme-icon-dark]');
        const lightIcon = button.querySelector('[data-theme-icon-light]');

        if (darkIcon) {
            darkIcon.classList.toggle('hidden', isDark);
        }

        if (lightIcon) {
            lightIcon.classList.toggle('hidden', !isDark);
        }
    });
};

const setTheme = (theme) => {
    window.localStorage.setItem(themeStorageKey, theme);
    applyTheme(theme);
    syncThemeToggles(theme);
};

const initializeTheme = () => {
    const theme = getPreferredTheme();

    applyTheme(theme);
    syncThemeToggles(theme);

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
            setTheme(nextTheme);
        });
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeTheme, { once: true });
} else {
    initializeTheme();
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
    const storedTheme = window.localStorage.getItem(themeStorageKey);

    if (storedTheme === 'light' || storedTheme === 'dark') {
        return;
    }

    const nextTheme = event.matches ? 'dark' : 'light';
    applyTheme(nextTheme);
    syncThemeToggles(nextTheme);
});

Alpine.start();
