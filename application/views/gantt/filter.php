<form action="/ulab/gantt/index" method="GET">
    <div class="filter filters mb-4">
        <div class="row">
            <div class="col">
                <input type="hidden" name="VIEW_MODE" value="<?= $this->data['VIEW_MODE']; ?>" />
                <input value="<?= $this->data['filter']['user_filter']; ?>" name="user_filter" placeholder="Пользователь" type="text" class="form-control"/>
            </div>
            <div class="col">
                <input value="<?= $this->data['filter']['project_filter']; ?>" name="project_filter" placeholder="Проект" type="text" class="form-control"/>
            </div>
            <div class="col" style="display: flex;">
                <button type="submit" name="action" value="search" class="btn btn-primary">Искать</button>
                <button type="submit" name="action" value="reset" class="btn btn-secondary" style="margin-left: 15px;">Сбросить</button>
            </div>
        </div>
    </div>
</form>