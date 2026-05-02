export default function PoemDetails({ poem }) {
  return (
    <div className="card">
      <h3>Просмотр стихотворения</h3>
      {poem ? (
        <>
          <h4>{poem.title}</h4>
          <p><b>Автор:</b> {poem.author}</p>
          <p><b>Жанр:</b> {poem.genre || 'Не указан'}</p>
          <pre className="poem-text">{poem.text}</pre>
        </>
      ) : (
        <p>Выберите стихотворение из списка.</p>
      )}
    </div>
  )
}