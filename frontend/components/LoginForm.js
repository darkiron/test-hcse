import { csrf, login, me } from '../api.js';
import { navigate } from '../router.js';

export function mountLogin() {
  const form = document.getElementById('login-form');
  const statusEl = document.getElementById('status');
  if (!form) return;
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    try {
      statusEl.textContent = 'Connexion en cours…';
      await csrf();
      await login(email, password);
      statusEl.textContent = 'POST /api/login → 200';
      const user = await me();
      if (user) navigate('/dashboard');
    } catch (err) {
      statusEl.textContent = (err?.message || 'Erreur de connexion');
      statusEl.classList.add('has-text-danger');
    }
  }, { once: true });
}
