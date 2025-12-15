import { el } from '../utils/dom.js';
import { Input } from '../atoms/Input.js';
import { Button } from '../atoms/Button.js';

export function LoginCard() {
  const email = Input({ id: 'email', type: 'email', placeholder: 'admin@example.com', required: true });
  const pwd = Input({ id: 'password', type: 'password', placeholder: 'secret', required: true });
  const submit = Button({ className: 'button is-link is-fullwidth', type: 'submit', text: 'Se connecter' });

  const form = el('form', { id: 'login-form' }, [
    el('div', { class: 'field' }, [
      el('label', { class: 'label', for: 'email' }, 'Email'),
      el('div', { class: 'control has-icons-left' }, [
        email,
        el('span', { class: 'icon is-small is-left' }, [el('i', { class: 'fas fa-envelope', 'aria-hidden': 'true' })]),
      ]),
    ]),
    el('div', { class: 'field' }, [
      el('label', { class: 'label', for: 'password' }, 'Mot de passe'),
      el('div', { class: 'control has-icons-left' }, [
        pwd,
        el('span', { class: 'icon is-small is-left' }, [el('i', { class: 'fas fa-lock', 'aria-hidden': 'true' })]),
      ]),
    ]),
    el('div', { class: 'field' }, [
      el('div', { class: 'control' }, [submit]),
    ]),
  ]);

  return el('div', { class: 'card', dataset: { view: 'login' } }, [
    el('header', { class: 'card-header' }, [el('p', { class: 'card-header-title' }, 'Connexion')]),
    el('div', { class: 'card-content' }, [
      el('p', { class: 'content' }, 'Entrez vos identifiants pour accéder à l’API.'),
      form,
      el('div', { id: 'status', class: 'has-text-weight-semibold mt-2', role: 'status' }),
    ]),
  ]);
}
