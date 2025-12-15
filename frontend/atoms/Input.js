export function Input({ id, type = 'text', placeholder = '', className = 'input', required = false } = {}) {
  const i = document.createElement('input');
  if (id) i.id = id;
  i.type = type;
  i.placeholder = placeholder;
  i.className = className;
  if (required) i.required = true;
  return i;
}
