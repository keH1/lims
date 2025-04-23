const REGEX = {
    // Очистка строки от нечисловых символов
    NUMBERS_ONLY: /[^0-9]/g,
    // Поиск всех пробелов
    FIND_SPACES: /\s+/g
}

/**
 * Функция для задержки выполнения другой функции при частых вызовах
 * @param {Function} func - Функция, выполнение которой нужно отложить
 * @param {number} wait - Время задержки
 * @returns {Function} - Функция с задержкой выполнения
 */
function delayExecution(func, wait) {
    let timeout
    return function() {
        const context = this, args = arguments
        clearTimeout(timeout)
        timeout = setTimeout(() => func.apply(context, args), wait)
    }
}