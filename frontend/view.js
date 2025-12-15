// view.js — helpers d’affichage des vues atomiques

export function showView(name) {
  const views = Array.from(document.querySelectorAll('[data-view]'));
  views.forEach(v => {
    v.style.display = v.getAttribute('data-view') === name ? '' : 'none';
  });
}
