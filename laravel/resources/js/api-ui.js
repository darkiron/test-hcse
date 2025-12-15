const API_BASE = (import.meta && import.meta.env && import.meta.env.VITE_API_BASE) || 'http://localhost:8080';

function $(sel) { return document.querySelector(sel); }
function setText(sel, text) { const el = $(sel); if (el) el.textContent = text; }
function setCode(sel, obj) { const el = $(sel); if (el) el.textContent = typeof obj === 'string' ? obj : JSON.stringify(obj, null, 2); }
function showStatus(sel, text, cls) {
  const el = $(sel);
  if (!el) return;
  el.style.display = 'inline-block';
  el.classList.remove('ok', 'warn', 'err');
  if (cls) el.classList.add(cls);
  el.textContent = text;
}
function lastError(err) {
  const box = $('#last-error');
  if (!box) return;
  if (!err) { box.textContent = 'Aucune'; return; }
  if (typeof err === 'string') { box.textContent = err; return; }
  box.textContent = JSON.stringify(err, null, 2);
}

async function api(path, options = {}) {
  const url = API_BASE.replace(/\/$/, '') + path;
  const t0 = performance.now();
  try {
    const res = await fetch(url, {
      method: options.method || 'GET',
      headers: {
        'Accept': 'application/json',
        ...(options.body ? { 'Content-Type': 'application/json' } : {}),
        ...(options.headers || {}),
      },
      credentials: 'include', // pour Sanctum si utilisé
      body: options.body ? JSON.stringify(options.body) : undefined,
    });
    const dt = Math.round(performance.now() - t0);
    let data = null;
    const ct = res.headers.get('content-type') || '';
    if (ct.includes('application/json')) {
      data = await res.json();
    } else {
      data = await res.text();
    }
    return { ok: res.ok, status: res.status, data, dt };
  } catch (e) {
    return { ok: false, status: 0, data: String(e), dt: 0 };
  }
}

function init() {
  setText('#api-base', API_BASE);

  // Etat /api/user
  (async () => {
    showStatus('#api-status .status', 'Chargement…', 'warn');
    const r = await api('/api/user');
    const el = $('#api-status');
    if (!el) return;
    if (r.ok) {
      el.innerHTML = `<span class="status ok">${r.status} OK (${r.dt}ms)</span>`;
    } else if (r.status >= 500) {
      el.innerHTML = `<span class="status err">${r.status} 5xx (${r.dt}ms)</span>`;
      lastError(r.data);
    } else {
      el.innerHTML = `<span class="status warn">${r.status || 'ERR'} (${r.dt}ms)</span>`;
      lastError(r.data);
    }
  })();

  // Bouton offres
  const btnOffers = $('#btn-load-offers');
  if (btnOffers) {
    btnOffers.addEventListener('click', async () => {
      showStatus('#offers-status', 'Chargement…', 'warn');
      const r = await api('/api/offers');
      if (r.ok) {
        showStatus('#offers-status', `${r.status} OK (${r.dt}ms)`, 'ok');
        setCode('#offers-json', r.data);
      } else {
        const cls = r.status >= 500 || r.status === 0 ? 'err' : 'warn';
        showStatus('#offers-status', `${r.status || 'ERR'}`, cls);
        setCode('#offers-json', r.data);
        lastError(r.data);
      }
    });
  }

  // Form produits par offre
  const form = $('#products-form');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = Number($('#offer-id').value || 1);
      showStatus('#products-status', 'Chargement…', 'warn');
      const r = await api(`/api/offers/${id}/products`);
      if (r.ok) {
        showStatus('#products-status', `${r.status} OK (${r.dt}ms)`, 'ok');
        setCode('#products-json', r.data);
      } else {
        const cls = r.status >= 500 || r.status === 0 ? 'err' : 'warn';
        showStatus('#products-status', `${r.status || 'ERR'}`, cls);
        setCode('#products-json', r.data);
        lastError(r.data);
      }
    });
  }
}

document.addEventListener('DOMContentLoaded', init);
