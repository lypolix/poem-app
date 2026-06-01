import { useEffect, useState } from 'react'
import { api } from './services/api'
import useLocalStorage from './hooks/useLocalStorage'
import LoginForm from './components/LoginForm'
import CreateUserForm from './components/CreateUserForm'
import PoemList from './components/PoemList'
import PoemDetails from './components/PoemDetails'
import PoemForm from './components/PoemForm'
import UserList from './components/UserList'

export default function App() {
  const [currentUser, setCurrentUser] = useState(null)
  const [poems, setPoems] = useState([])
  const [users, setUsers] = useState([])
  const [selectedPoem, setSelectedPoem] = useState(null)
  const [message, setMessage] = useState('')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)

  useEffect(() => {
    const token = localStorage.getItem('authToken')
    if (token) {
      api.setAuthToken(token)
      restoreUser()
    }
  }, [])

  const restoreUser = async () => {
    try {
      const user = await api.getCurrentUser()
      setCurrentUser(user)
    } catch (e) {
      localStorage.removeItem('authToken')
      api.setAuthToken(null)
    }
  }

  const loadData = async () => {
    try {
      const poemsData = await api.getPoems()
      const usersData = await api.getUsers()
      setPoems(poemsData)
      setUsers(usersData)
    } catch (e) {
      setError(e.message)
    }
  }

  useEffect(() => {
    if (currentUser) {
      loadData()
    }
  }, [currentUser])

  const handleLoginSuccess = (userData) => {
    setCurrentUser(userData)
    setMessage('Вы успешно вошли!')
  }

  const handleLogout = async () => {
    try {
      await api.logout()
      setCurrentUser(null)
      setMessage('Вы успешно вышли')
    } catch (e) {
      setError(e.message)
    }
  }

  const handlePoemSubmit = async (form) => {
    setError('')
    setMessage('')
    try {
      if (selectedPoem) {
        await api.updatePoem(selectedPoem.id, form)
        setMessage('Стихотворение успешно обновлено')
      } else {
        await api.createPoem(form)
        setMessage('Стихотворение успешно добавлено')
      }
      setSelectedPoem(null)
      await loadData()
    } catch (e) {
      setError(e.message)
    }
  }

  const handleDeletePoem = async (id) => {
    setError('')
    setMessage('')
    try {
      await api.deletePoem(id)
      setMessage('Стихотворение удалено')
      if (selectedPoem?.id === id) {
        setSelectedPoem(null)
      }
      await loadData()
    } catch (e) {
      setError(e.message)
    }
  }

  const handleCreateUser = async (form) => {
    setError('')
    setMessage('')
    setLoading(true)
    try {
      await api.createUser(form)
      setMessage('Пользователь успешно добавлен')
      await loadData()
    } catch (e) {
      setError(e.message)
    } finally {
      setLoading(false)
    }
  }

  if (!currentUser) {
    return (
      <div className="container">
        <h1>Клиент-серверное приложение «Стихотворение»</h1>
        <div className="login-container">
          <LoginForm onLoginSuccess={handleLoginSuccess} />
          {message && <div className="message success">{message}</div>}
          {error && <div className="message error">{error}</div>}
        </div>
      </div>
    )
  }

  return (
    <div className="container">
      <div className="header">
        <h1>Клиент-серверное приложение «Стихотворение»</h1>
        <div className="user-info">
          <span>{currentUser.name} ({currentUser.role})</span>
          <button onClick={handleLogout} className="logout-btn">Выход</button>
        </div>
      </div>

      {message && <div className="message success">{message}</div>}
      {error && <div className="message error">{error}</div>}

      <div className="grid">
        <PoemList
          poems={poems}
          onSelect={setSelectedPoem}
          onDelete={handleDeletePoem}
          role={currentUser.role}
        />
        <PoemDetails poem={selectedPoem} />
      </div>

      <PoemForm
        onSubmit={handlePoemSubmit}
        selectedPoem={selectedPoem}
        role={currentUser.role}
      />

      {currentUser.role === 'admin' && (
        <div className="admin-section">
          <h2>Управление пользователями</h2>
          <div className="grid">
            <CreateUserForm onSubmit={handleCreateUser} loading={loading} />
            <UserList users={users} />
          </div>
        </div>
      )}
    </div>
  )
}