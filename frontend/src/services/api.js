const API_URL = 'http://localhost:8000'

let authToken = localStorage.getItem('authToken') || null

async function request(path, options = {}) {
  const headers = {
    'Content-Type': 'application/json',
    ...options.headers
  }

  if (authToken && !options.skipAuth) {
    headers['Authorization'] = `Bearer ${authToken}`
  }

  const response = await fetch(`${API_URL}${path}`, {
    ...options,
    headers
  })

  const data = await response.json()

  if (!response.ok) {
    throw new Error(data.message || 'Ошибка запроса')
  }

  return data
}

export const api = {
  login: (username, password) =>
    request('/auth/login', {
      method: 'POST',
      skipAuth: true,
      body: JSON.stringify({ username, password })
    }).then(data => {
      authToken = data.token
      localStorage.setItem('authToken', authToken)
      return data
    }),

  logout: () =>
    request('/auth/logout', {
      method: 'POST'
    }).then(data => {
      authToken = null
      localStorage.removeItem('authToken')
      return data
    }),

  getCurrentUser: () =>
    request('/auth/me', {
      method: 'GET'
    }),

  setAuthToken: (token) => {
    authToken = token
    if (token) {
      localStorage.setItem('authToken', token)
    } else {
      localStorage.removeItem('authToken')
    }
  },

  getAuthToken: () => authToken,

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