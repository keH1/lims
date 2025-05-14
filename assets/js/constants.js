const REGEX = {
    // Очистка строки от нечисловых символов
    NUMBERS_ONLY: /[^0-9]/g,
    // Поиск всех пробелов
    FIND_SPACES: /\s+/g,
    // Проверяет валидный адрес электронной почты вида имя_пользователя@домен.зона
    EMAIL_PATTERN: /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/,
    // Удаляет 'e', 'E', '+' из числовых полей
    REMOVE_EXPONENTIAL: /[eE\+]/g,
    // Удаляет минус '-' из числовых полей
    REMOVE_MINUS: /-/g,
    // Удаляет точку и всё после неё
    REMOVE_DECIMAL: /\..*/g
}

const TYPE_DOCUMENT = {
    PROTOCOL: 5,
    BATCH: 7
}

const PROTOCOL_WINDOW_MAX_WAIT_MS = 10000