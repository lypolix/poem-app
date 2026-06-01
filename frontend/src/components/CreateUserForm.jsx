import { useState } from 'react'

export default function CreateUserForm({ onSubmit, loading }) {
    const [username, setUsername] = useState('')
    const [password, setPassword] = useState('')
    const [name, setName] = useState('')
    const [email, setEmail] = useState('')
    const [age, setAge] = useState('')
    const [role, setRole] = useState('editor')
    const [error, setError] = useState('')

    const handleSubmit = async (e) => {
        e.preventDefault()
        setError('')

        if (!username || !password || !name || !email) {
            setError('Все поля обязательны')
            return
        }

        try {
            await onSubmit({
                username,
                password,
                name,
                email,
                age: age ? parseInt(age) : null,
                role
            })
            setUsername('')
            setPassword('')
            setName('')
            setEmail('')
            setAge('')
            setRole('editor')
        } catch (e) {
            setError(e.message)
        }
    }

    return (
        <form onSubmit={handleSubmit} className="form">
            <h2>Создать пользователя</h2>

            {error && <div className="message error">{error}</div>}

            <div className="form-group">
                <label htmlFor="username">Логин:</label>
                <input
                    id="username"
                    type="text"
                    value={username}
                    onChange={(e) => setUsername(e.target.value)}
                    placeholder="Минимум 3 символа"
                    disabled={loading}
                />
            </div>

            <div className="form-group">
                <label htmlFor="password">Пароль:</label>
                <input
                    id="password"
                    type="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    placeholder="Минимум 6 символов"
                    disabled={loading}
                />
            </div>

            <div className="form-group">
                <label htmlFor="name">Имя:</label>
                <input
                    id="name"
                    type="text"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    placeholder="Полное имя"
                    disabled={loading}
                />
            </div>

            <div className="form-group">
                <label htmlFor="email">Email:</label>
                <input
                    id="email"
                    type="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    placeholder="example@mail.com"
                    disabled={loading}
                />
            </div>

            <div className="form-group">
                <label htmlFor="age">Возраст:</label>
                <input
                    id="age"
                    type="number"
                    value={age}
                    onChange={(e) => setAge(e.target.value)}
                    placeholder="Опционально"
                    disabled={loading}
                />
            </div>

            <div className="form-group">
                <label htmlFor="role">Роль:</label>
                <select
                    id="role"
                    value={role}
                    onChange={(e) => setRole(e.target.value)}
                    disabled={loading}
                >
                    <option value="editor">Редактор</option>
                    <option value="admin">Администратор</option>
                </select>
            </div>

            <button type="submit" disabled={loading}>
                {loading ? 'Создание...' : 'Создать пользователя'}
            </button>
        </form>
    )
}
