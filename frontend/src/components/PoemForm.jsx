import { useEffect, useState } from 'react'

const initialState = {
  id: '',
  title: '',
  author: '',
  text: '',
  genre: '',
  createdBy: ''
}

export default function PoemForm({ onSubmit, selectedPoem, role }) {
  const [form, setForm] = useState(initialState)

  useEffect(() => {
    if (selectedPoem) {
      setForm(selectedPoem)
    } else {
      setForm(initialState)
    }
  }, [selectedPoem])

  if (role === 'guest') {
    return null
  }

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value })
  }

  const handleSubmit = (e) => {
    e.preventDefault()
    onSubmit(form)
    setForm(initialState)
  }

  return (
    <div className="card">
      <h3>{selectedPoem ? 'Редактирование стихотворения' : 'Добавление стихотворения'}</h3>
      <form onSubmit={handleSubmit} className="form">
        <input name="id" placeholder="ID" value={form.id} onChange={handleChange} disabled={!!selectedPoem} />
        <input name="title" placeholder="Название" value={form.title} onChange={handleChange} />
        <input name="author" placeholder="Автор" value={form.author} onChange={handleChange} />
        <input name="genre" placeholder="Жанр" value={form.genre} onChange={handleChange} />
        <input name="createdBy" placeholder="Кем добавлено" value={form.createdBy} onChange={handleChange} />
        <textarea name="text" placeholder="Текст стихотворения" value={form.text} onChange={handleChange} rows="8" />
        <button type="submit">{selectedPoem ? 'Сохранить изменения' : 'Добавить стихотворение'}</button>
      </form>
    </div>
  )
}