import { Button } from '../atoms/Button.js';
import { el } from '../utils/dom.js';

export function Navbar() {
  const brand = el('div', { class: 'navbar-brand' }, [
    el('a', { class: 'navbar-item', 'data-link': '', href: '/login', role: 'link', 'aria-label': 'Accueil' }, 'HelloCSE'),
    el('a', { role: 'button', class: 'navbar-burger', 'aria-label': 'menu', 'aria-expanded': 'false' }, [
      el('span', { 'aria-hidden': 'true' }),
      el('span', { 'aria-hidden': 'true' }),
      el('span', { 'aria-hidden': 'true' }),
    ]),
  ]);

  const start = el('div', { class: 'navbar-start', id: 'nav-start' }, [
    el('a', { class: 'navbar-item', 'data-link': '', href: '/dashboard', role: 'link' }, 'Dashboard'),
    el('a', { class: 'navbar-item', 'data-link': '', href: '/offers', role: 'link' }, 'Offers'),
  ]);

  const logoutBtn = Button({ id: 'nav-logout', className: 'button is-warning is-light', text: 'Logout' });
  logoutBtn.style.display = 'none';

  const end = el('div', { class: 'navbar-end' }, [
    el('div', { class: 'navbar-item' }, [
      el('div', { class: 'buttons' }, [logoutBtn]),
    ]),
  ]);

  const menu = el('div', { class: 'navbar-menu' }, [start, end]);
  return el('nav', { class: 'navbar is-light mb-4', role: 'navigation', 'aria-label': 'main navigation' }, [brand, menu]);
}
