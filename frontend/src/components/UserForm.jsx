import { useState } from 'react'

export default function UserForm({ onSubmit, role }) {
  const [form, setForm] = useState({
    id: '',
    name: '',
    email: '',
    age: ''
  })

  if (role !== 'admin') {
    return null
  }

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value })
  }

  const handleSubmit = (e) => {
    e.preventDefault()
    onSubmit({
      ...form,
      age: form.age ? Number(form.age) : null
    })
    setForm({ id: '', name: '', email: '', age: '' })
  }

  return (
    <div className="card">
      <h3>Добавление пользователя</h3>
      <form onSubmit={handleSubmit} className="form">
        <input name="id" placeholder="ID" value={form.id} onChange={handleChange} />
        <input name="name" placeholder="Имя" value={form.name} onChange={handleChange} />
        <input name="email" placeholder="Email" value={form.email} onChange={handleChange} />
        <input name="age" placeholder="Возраст" value={form.age} onChange={handleChange} />
        <button type="submit">Добавить пользователя</button>
      </form>
    </div>
  )
}