export function Button({ className = '', id, type = 'button', onClick, text = '' } = {}) {
  const btn = document.createElement('button');
  btn.type = type;
  if (id) btn.id = id;
  btn.className = className;
  btn.textContent = text;
  if (onClick) btn.addEventListener('click', onClick);
  return btn;
}
