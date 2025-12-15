import { el } from '../utils/dom.js';
import { Navbar } from '../molecules/Navbar.js';
import { LoginCard } from '../organisms/LoginCard.js';

function DashboardCard() {
  return el('div', { class: 'card mt-5', dataset: { view: 'dashboard' }, style: 'display:none' }, [
    el('header', { class: 'card-header' }, [el('p', { class: 'card-header-title' }, 'Dashboard')]),
    el('div', { class: 'card-content' }, [
      el('article', { class: 'message is-info' }, [
        el('div', { class: 'message-header' }, [el('p', {}, 'Profil')]),
        el('div', { class: 'message-body' }, [el('pre', { id: 'me-json' })]),
      ]),
      el('button', { id: 'logout', class: 'button is-warning is-fullwidth mt-3' }, 'Se déconnecter'),
    ]),
  ]);
}

function OffersCard() {
  return el('div', { class: 'card mt-5', dataset: { view: 'offers' }, style: 'display:none' }, [
    el('header', { class: 'card-header' }, [el('p', { class: 'card-header-title' }, 'Offres publiées')]),
    el('div', { class: 'card-content' }, [el('div', { id: 'offers' })]),
  ]);
}

export function MainLayout() {
  const container = el('div', { class: 'container' }, [
    el('div', { class: 'columns is-centered' }, [
      el('div', { class: 'column is-8-tablet is-6-desktop is-5-widescreen' }, [
        Navbar(),
        LoginCard(),
        DashboardCard(),
        OffersCard(),
      ]),
    ]),
  ]);
  return el('section', { class: 'section', dataset: { route: '' } }, [container]);
}
