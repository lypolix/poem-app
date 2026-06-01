export default function UserList({ users }) {
  return (
    <div className="card">
      <h3>Список пользователей</h3>
      {users.length === 0 ? (
        <p>Пользователей пока нет.</p>
      ) : (
        <ul className="list">
          {users.map((user) => (
            <li key={user.id} className="list-item">
              <div>
                <strong>{user.name}</strong>
                <div style={{ fontSize: '12px', color: '#999', marginTop: '4px' }}>
                  @{user.username} • {user.role}
                </div>
              </div>
              <div style={{ fontSize: '12px', color: '#999' }}>
                {user.email}
              </div>
            </li>
          ))}
        </ul>
      )}
    </div>
  )
}