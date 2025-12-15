import { showView } from '../view.js';
import { me } from '../api.js';

export async function DashboardView() {
  showView('dashboard');
  const user = await me();
  const pre = document.getElementById('me-json');
  if (pre) pre.textContent = user ? JSON.stringify(user, null, 2) : '';
}
