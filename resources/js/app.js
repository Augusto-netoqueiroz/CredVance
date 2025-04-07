import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');

    const loadTheme = () => {
        const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            darkIcon.classList.remove('hidden');
            lightIcon.classList.add('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
        }
    };

    loadTheme();

    themeToggleBtn.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');

        const isDark = document.documentElement.classList.contains('dark');

        localStorage.setItem('theme', isDark ? 'dark' : 'light');

        darkIcon.classList.toggle('hidden', !isDark);
        lightIcon.classList.toggle('hidden', isDark);
    });
});



document.addEventListener('DOMContentLoaded', () => {
    const modalUsuario = document.getElementById('modalUsuario');
    const btnNovoUsuario = document.getElementById('btnNovoUsuario');
    const fecharModal = document.getElementById('fecharModal');

    btnNovoUsuario.addEventListener('click', () => {
        modalUsuario.classList.remove('hidden');
        modalUsuario.classList.add('flex');
    });

    fecharModal.addEventListener('click', () => {
        modalUsuario.classList.add('hidden');
        modalUsuario.classList.remove('flex');
    });

    document.getElementById('formUsuario').addEventListener('submit', (e) => {
        e.preventDefault();

        axios.post('/usuarios', {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
        })
        .then(() => {
            location.reload();
        })
        .catch((error) => {
            console.log(error);
            alert('Erro ao salvar usuário.');
        });
    });
});