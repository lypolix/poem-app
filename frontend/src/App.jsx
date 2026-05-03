import { useEffect, useState } from 'react'
import { api } from './services/api'
import useLocalStorage from './hooks/useLocalStorage'
import RoleSelector from './components/RoleSelector'
import PoemList from './components/PoemList'
import PoemDetails from './components/PoemDetails'
import PoemForm from './components/PoemForm'
import UserList from './components/UserList'
import UserForm from './components/UserForm'

export default function App() {
  const [role, setRole] = useLocalStorage('role', 'guest')
  const [poems, setPoems] = useState([])
  const [users, setUsers] = useState([])
  const [selectedPoem, setSelectedPoem] = useState(null)
  const [message, setMessage] = useState('')
  const [error, setError] = useState('')

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
    loadData()
  }, [])

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
    try {
      await api.createUser(form)
      setMessage('Пользователь успешно добавлен')
      await loadData()
    } catch (e) {
      setError(e.message)
    }
  }

  return (
    <div className="container">
      <h1>Клиент-серверное приложение «Стихотворение»</h1>

      <RoleSelector role={role} setRole={setRole} />

      {message && <div className="message success">{message}</div>}
      {error && <div className="message error">{error}</div>}

      <div className="grid">
        <PoemList
          poems={poems}
          onSelect={setSelectedPoem}
          onDelete={handleDeletePoem}
          role={role}
        />
        <PoemDetails poem={selectedPoem} />
      </div>

      <PoemForm
        onSubmit={handlePoemSubmit}
        selectedPoem={selectedPoem}
        role={role}
      />

      <div className="grid">
        <UserList users={users} />
        <UserForm onSubmit={handleCreateUser} role={role} />
      </div>
    </div>
  )
}