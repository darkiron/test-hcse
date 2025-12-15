import { showView } from '../view.js';
import { mountLogin } from '../components/LoginForm.js';

export async function LoginView() {
  showView('login');
  // Monte le composant une seule fois (le listener est en { once: true })
  mountLogin();
  // Focus accessibilité
  const email = document.getElementById('email');
  if (email) email.focus();
}
