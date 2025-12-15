// router.js — routeur SPA atomique (History API)

const routes = [];

export function addRoute(path, render) {
  // path peut être une string (match exact) ou une RegExp (match pattern)
  routes.push({ path, render });
}

export function navigate(path) {
  if (location.pathname !== path) {
    history.pushState({}, '', path);
  }
  renderRoute();
}

export function startRouter() {
  window.addEventListener('popstate', renderRoute);
  renderRoute();
}

export async function renderRoute() {
  const current = location.pathname || '/login';
  let route = routes.find(r => typeof r.path === 'string' && r.path === current);
  let match = null;
  if (!route) {
    for (const r of routes) {
      if (r.path instanceof RegExp) {
        const m = current.match(r.path);
        if (m) { route = r; match = m; break; }
      }
    }
  }
  if (!route) {
    route = routes.find(r => r.path === '/login');
  }
  if (route) await route.render(match);
}
