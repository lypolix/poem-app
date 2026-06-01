import { useState } from 'react'
import { api } from '../services/api'

export default function LoginForm({ onLoginSuccess }) {
    const [username, setUsername] = useState('')
    const [password, setPassword] = useState('')
    const [error, setError] = useState('')
    const [loading, setLoading] = useState(false)

    const handleSubmit = async (e) => {
        e.preventDefault()
        setError('')
        setLoading(true)

        try {
            const data = await api.login(username, password)
            onLoginSuccess(data)
            setUsername('')
            setPassword('')
        } catch (e) {
            setError(e.message)
        } finally {
            setLoading(false)
        }
    }

    return (
        <form onSubmit={handleSubmit} className="form">
            <h2>Вход</h2>

            {error && <div className="message error">{error}</div>}

            <div className="form-group">
                <label htmlFor="username">Логин:</label>
                <input
                    id="username"
                    type="text"
                    value={username}
                    onChange={(e) => setUsername(e.target.value)}
                    placeholder="Введите логин"
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
                    placeholder="Введите пароль"
                    disabled={loading}
                />
            </div>

            <button type="submit" disabled={loading}>
                {loading ? 'Загрузка...' : 'Войти'}
            </button>

            <p className="hint">
                <strong>Тестовые учетные данные:</strong><br />
                Администратор: admin / admin123<br />
                Редактор: editor1 / password123
            </p>
        </form>
    )
}
