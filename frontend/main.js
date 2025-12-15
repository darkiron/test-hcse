import { me, logout, onRequest } from './api.js';
import { addRoute, navigate, startRouter } from './router.js';
import { LoginView } from './views/LoginView.js';
import { DashboardView } from './views/DashboardView.js';
import { OffersView } from './views/OffersView.js';
import { ProductsView } from './views/ProductsView.js';

// Dernier appel XHR observé (via bus d’événements)
let lastCall = { url: '', method: 'GET', ms: 0, status: 0, rateLimit: {} };
onRequest((p) => { lastCall = p; });

const meJson = document.getElementById('me-json');
const navLogoutBtn = document.getElementById('nav-logout');
const navStart = document.getElementById('nav-start');

async function refreshMe() {
  const user = await me();
  if (user) {
    if (meJson) meJson.textContent = JSON.stringify(user, null, 2);
    if (navLogoutBtn) navLogoutBtn.style.display = '';
    if (navStart) navStart.style.display = '';
  } else {
    if (meJson) meJson.textContent = '';
    if (navLogoutBtn) navLogoutBtn.style.display = 'none';
    if (navStart) navStart.style.display = 'none';
    // Si pas authentifié, forcer route /login
    if (location.pathname !== '/login') navigate('/login');
  }
}

const logoutBtn = document.getElementById('logout');
if (logoutBtn) {
  logoutBtn.addEventListener('click', async () => {
    try {
      await logout();
    } finally {
      await refreshMe();
      navigate('/login');
    }
  });
}

if (navLogoutBtn) {
  navLogoutBtn.addEventListener('click', async () => {
    try {
      await logout();
    } finally {
      await refreshMe();
      navigate('/login');
    }
  });
}

// --- SPA Router (pages / views) ---
addRoute('/login', async () => {
  await LoginView();
});

addRoute('/dashboard', async () => {
  const user = await me();
  if (!user) return navigate('/login');
  await DashboardView();
});

addRoute('/offers', async () => {
  const user = await me();
  if (!user) return navigate('/login');
  await OffersView();
});

// Route dynamique: /offers/:id/products (listing produits d'une offre)
addRoute(/^\/offers\/(\d+)\/products$/, async (match) => {
  const user = await me();
  if (!user) return navigate('/login');
  await ProductsView(match);
});

// Interception des liens SPA
document.addEventListener('click', (e) => {
  const a = e.target.closest('a[data-link]');
  if (a) {
    e.preventDefault();
    const href = a.getAttribute('href') || '/login';
    navigate(href);
  }
});

// Nettoyage: plus de formulaire /offers/add dans cette itération, page offers = listing

// Initial state
startRouter();
refreshMe();
