// api.js — centralise les appels XHR et expose des helpers

const listeners = new Set();
export function onRequest(listener) { listeners.add(listener); return () => listeners.delete(listener); }

function notify(payload) { listeners.forEach(l => { try { l(payload); } catch {} }); }

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
  return null;
}

export async function apiFetch(url, options = {}) {
  const opts = {
    credentials: 'include',
    ...options,
    headers: { ...(options.headers || {}) },
  };
  // Ajout auto CSRF pour requêtes mutables
  const method = (opts.method || 'GET').toUpperCase();
  if (method !== 'GET' && method !== 'HEAD') {
    const xsrf = getCookie('XSRF-TOKEN');
    if (xsrf && !opts.headers['X-XSRF-TOKEN']) opts.headers['X-XSRF-TOKEN'] = xsrf;
  }

  const t0 = performance.now();
  let res;
  try {
    res = await fetch(url, opts);
    const t1 = performance.now();
    notify({
      url,
      method: (opts.method || 'GET').toUpperCase(),
      ms: t1 - t0,
      status: res.status,
      rateLimit: {
        limit: res.headers.get('x-ratelimit-limit'),
        remaining: res.headers.get('x-ratelimit-remaining'),
        retryAfter: res.headers.get('retry-after'),
      }
    });
    return res;
  } catch (e) {
    const t1 = performance.now();
    notify({ url, method: (opts.method||'GET').toUpperCase(), ms: t1 - t0, status: 0, error: String(e) });
    throw e;
  }
}

export async function csrf() { await apiFetch('/sanctum/csrf-cookie'); }
export async function login(email, password) {
  const res = await apiFetch('/api/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
  });
  if (!res.ok) throw new Error(`Login échoué (${res.status})`);
  return res.json();
}
export async function me() {
  const res = await apiFetch('/api/user');
  return res.ok ? res.json() : null;
}
export async function logout() {
  await apiFetch('/api/logout', { method: 'POST' });
}
export async function listOffers() {
  const res = await apiFetch('/api/offers');
  if (!res.ok) throw new Error('Erreur /api/offers');
  return res.json();
}
export async function createOffer(payload) {
  const res = await apiFetch('/api/offers', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
  return { ok: res.ok, status: res.status, body: await res.text() };
}
