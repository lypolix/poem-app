export default function PoemList({ poems, onSelect, onDelete, role }) {
  return (
    <div className="card">
      <h3>Список стихотворений</h3>
      {poems.length === 0 ? (
        <p>Стихотворений пока нет.</p>
      ) : (
        <ul className="list">
          {poems.map((poem) => (
            <li key={poem.id} className="list-item">
              <div>
                <strong>{poem.title}</strong> — {poem.author}
              </div>
              <div className="actions">
                <button onClick={() => onSelect(poem)}>Открыть</button>
                {(role === 'editor' || role === 'admin') && (
                  <button onClick={() => onSelect(poem)}>Редактировать</button>
                )}
                {role === 'admin' && (
                  <button className="danger" onClick={() => onDelete(poem.id)}>
                    Удалить
                  </button>
                )}
              </div>
            </li>
          ))}
        </ul>
      )}
    </div>
  )
}