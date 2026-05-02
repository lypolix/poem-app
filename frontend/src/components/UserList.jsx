export default function UserList({ users }) {
  return (
    <div className="card">
      <h3>Пользователи</h3>
      {users.length === 0 ? (
        <p>Пользователей пока нет.</p>
      ) : (
        <ul className="list">
          {users.map((user) => (
            <li key={user.id}>
              {user.id}. {user.name} — {user.email} {user.age ? `(${user.age})` : ''}
            </li>
          ))}
        </ul>
      )}
    </div>
  )
}