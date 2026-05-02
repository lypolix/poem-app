const API_URL = 'http://localhost:8000'

async function request(path, options = {}) {
  const response = await fetch(`${API_URL}${path}`, {
    headers: { 'Content-Type': 'application/json' },
    ...options
  })

  const data = await response.json()

  if (!response.ok) {
    throw new Error(data.message || 'Ошибка запроса')
  }

  return data
}

export const api = {
  getUsers: () => request('/users'),
  getUserById: (id) => request(`/users/${id}`),
  createUser: (payload) =>
    request('/users', {
      method: 'POST',
      body: JSON.stringify(payload)
    }),

  getPoems: () => request('/poems'),
  getPoemById: (id) => request(`/poems/${id}`),
  createPoem: (payload) =>
    request('/poems', {
      method: 'POST',
      body: JSON.stringify(payload)
    }),
  updatePoem: (id, payload) =>
    request(`/poems/${id}`, {
      method: 'PUT',
      body: JSON.stringify(payload)
    }),
  deletePoem: (id) =>
    request(`/poems/${id}`, {
      method: 'DELETE'
    })
}