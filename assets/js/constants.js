const REGEX = {
    // Очистка строки от нечисловых символов
    NUMBERS_ONLY: /[^0-9]/g,
    // Поиск всех пробелов
    FIND_SPACES: /\s+/g,
    // Проверяет валидный адрес электронной почты вида имя_пользователя@домен.зона
    EMAIL_PATTERN: /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/
}

const TYPE_DOCUMENT = {
    PROTOCOL: 5,
    BATCH: 7
}