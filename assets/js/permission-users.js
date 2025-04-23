$(function ($) {
    const config = {
        selectors: {
            fioFilter: '[data-filter="fio"]',
            departmentFilter: '[data-filter="department"]',
            positionFilter: '[data-filter="position"]',
            roleFilter: '[data-filter="role"]',
            userCard: '[data-user-card]',
            userName: '[data-user-name]',
            userDepartment: '[data-user-department]',
            userPosition: '[data-user-position]',
            userRole: '[data-user-role]',
            resetButton: '[data-filter-reset]',
            filterInfo: '#filter-info'
        },
        filteringTime: 1000,
        storageKey: 'permission_users_filter'
    }
    
    let filterState = {
        fio: '',
        department: '',
        position: '',
        role: ''
    }
    
    let originalInput = {
        fio: ''
    }
    
    function init() {
        restoreFilterState()
        
        const hasActiveFilters = Object.values(filterState).some(value => value !== '')
        
        if (hasActiveFilters) {
            applyFilters()
        } 

        bindEvents()
    }
    
    /*
    * Привязка обработчиков событий к фильтрам
    */
    function bindEvents() {
        const delayedFilter = delayExecution(applyFilters, config.filteringTime)
        
        /**
         * Обработчик изменения фильтра
         */
        function handleFilterChange(filterType, $element, isInput = false) {
            $element.on(isInput ? 'input' : 'change', function() {
                const inputValue = $(this).val()
                
                if (filterType === 'fio') {
                    originalInput.fio = inputValue
                    filterState[filterType] = inputValue.toLowerCase().trim()
                } else {
                    filterState[filterType] = inputValue
                }
                
                if (isInput) {
                    delayedFilter()
                } else {
                    applyFilters()
                }
                
                saveFilterState()
            })
        }
        
        handleFilterChange('fio', $(config.selectors.fioFilter), true)
        handleFilterChange('department', $(config.selectors.departmentFilter))
        handleFilterChange('position', $(config.selectors.positionFilter))
        handleFilterChange('role', $(config.selectors.roleFilter))
        
        $(config.selectors.resetButton).on('click', function(e) {
            e.preventDefault()
            resetFilters()
        })
    }
    
    /*
    * Применение фильртов
    */
    function applyFilters() {
        const { fio, department, position, role } = filterState
        
        $(config.selectors.userCard).each(function() {
            const $card = $(this)
            
            const cardData = {
                fio: $card.find(config.selectors.userName).text().toLowerCase() || '',
                department: $card.find(config.selectors.userDepartment).text() || '',
                position: $card.find(config.selectors.userPosition).text() || '',
                role: $card.find(config.selectors.userRole).val() || ''
            }
            
            let match = true
            
            if (fio) {
                const searchWords = fio.split(REGEX.FIND_SPACES)
                match = !searchWords.some(word => word && !cardData.fio.includes(word))
            }
            
            if (match && department && cardData.department !== department) match = false
            if (match && position && cardData.position !== position) match = false
            if (match && role && cardData.role !== role) match = false
            
            $card.toggle(match)
        })
        
        updateFilterCounts()
    }
    
    /*
    * Сброс всех фильтров
    */
    function resetFilters() {
        $.each(filterState, function(key) {
            filterState[key] = ''
            $(config.selectors[`${key}Filter`]).val('')
        })
        
        originalInput.fio = ''
        localStorage.removeItem(config.storageKey)
        
        $(config.selectors.userCard).show()
        updateFilterCounts()
    }
    
    /*
    * Обновление счетчика отфильтрованных результатов
    */
    function updateFilterCounts() {
        const total = $(config.selectors.userCard).length
        const visible = $(config.selectors.userCard + ':visible').length
        
        $(config.selectors.filterInfo).text(
            visible < total ? `Показано ${visible} из ${total} пользователей` : ''
        )
    }
    
    /*
    * Сохранение состояния фильтров
    */
    function saveFilterState() {
        const stateToSave = {
            filterState: filterState,
            originalInput: originalInput
        }
        
        localStorage.setItem(config.storageKey, JSON.stringify(stateToSave))
    }
    
    /*
    * Восстановление состояния фильтров
    */
    function restoreFilterState() {
        const savedState = localStorage.getItem(config.storageKey)
        if (!savedState) {
            return false
        }
        
        const parsed = JSON.parse(savedState)
        if (!parsed) {
            return false
        }
        
        let hasStoredFilters = false
        
        if (parsed.originalInput) {
            originalInput.fio = parsed.originalInput.fio || ''
        }
        
        if (parsed.filterState) {
            $.each(parsed.filterState, function(key, filterValue) {
                if (filterValue) {
                    filterState[key] = filterValue
                    
                    if (key === 'fio' && originalInput.fio) {
                        $(config.selectors.fioFilter).val(originalInput.fio)
                    } else {
                        $(config.selectors[`${key}Filter`]).val(filterValue)
                    }
                    
                    hasStoredFilters = true
                } else {
                    filterState[key] = ''
                }
            })
        }
        
        return hasStoredFilters
    }
    
    init()
})