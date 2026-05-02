export default function RoleSelector({ role, setRole }) {
  return (
    <div className="card">
      <h3>Роль пользователя</h3>
      <select value={role} onChange={(e) => setRole(e.target.value)}>
        <option value="guest">Гость</option>
        <option value="editor">Редактор</option>
        <option value="admin">Администратор</option>
      </select>
    </div>
  )
}