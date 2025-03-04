<!-- Modals -->
<!--create user modal-->
<form id="gantt-create-user-modal" method="POST" action="/ulab/gantt/addUser"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Добавить пользователя
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label for="username" class="form-label">Ф.И.О. Пользователя</label>
            <input id="username" name="name" type="text" class="form-control"/>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label for="position" class="form-label">Должность</label>
            <input id="position" name="position" type="text" class="form-control"/>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label for="salary" class="form-label">Оклад</label>
            <input id="salary" name="salary" type="number" class="form-control"/>
        </div>
    </div>

    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" onclick="submitCreateForm('gantt-create-user-modal');" class="btn btn-primary">Сохранить
        </button>
        <button type="button" onclick="$.magnificPopup.close();" data-js-close-modal class="btn btn-secondary"
                style="margin-left: 5px">Закрыть
        </button>
    </div>
</form>

<!--create project modal-->
<form id="gantt-create-project-modal" method="POST" action="/ulab/gantt/addProject"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" onsubmit="generateProjectColor();">
    <div class="title mb-3 h-2">
        Добавить проект
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label for="project_name" class="form-label">Название проекта</label>
            <input id="project_name" name="project_name" type="text" class="form-control"/>
            <input id="color1" name="color1" type="hidden"/>
            <input id="color2" name="color2" type="hidden"/>
        </div>
    </div>

    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" onclick="generateProjectColor();submitCreateForm('gantt-create-project-modal');"
                class="btn btn-primary">Сохранить
        </button>
        <button type="button" onclick="$.magnificPopup.close();" data-js-close-modal class="btn btn-secondary"
                style="margin-left: 5px">Закрыть
        </button>
    </div>
</form>

<!--show user edit modal-->
<form id="gantt-show-user-modal" method="POST" action="/ulab/gantt/editUser"
class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
<div class="title mb-3 h-2 mt-3">
    <input id="user_name" name="name" type="text" class="form-control"/>
</div>
<div class="line-dashed-small"></div>
<div class="row mb-3">
    <div class="col">
        <label for="user_salary" class="form-label">Оклад</label>
        <input id="user_salary" name="salary" type="number" class="form-control"/>
        <input id="user_id" name="user_id" type="hidden"/>
        <input id="user_project_id" name="user_project_id" type="hidden"/>
    </div>
</div>

<div class="row mb-3">
    <div class="col">
        <label for="user_position" class="form-label">Должность</label>
        <input id="user_position" name="position" type="text" class="form-control"/>
    </div>
</div>

<div id="timelines_part">
    <div class="line-dashed-small"></div>

    <h2>Таймлайны</h2>
    <div id="timeline_container">

    </div>
</div>

<div class="d-flex">
    <button type="button" onclick="submitCreateForm('gantt-show-user-modal');"
            class="btn btn-primary">Сохранить
    </button>
    <button type="button" onclick="$.magnificPopup.close();" data-js-close-modal class="btn btn-secondary"
            style="margin-left: 5px">Закрыть
    </button>
</div>
</form>

<!--show user project modal-->
<form id="gantt-show-project-modal" method="POST" action="/ulab/gantt/editProject"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Редактирование проекта
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label for="project_name" class="form-label">Название проекта</label>
            <input id="proj_name" name="project_name" type="text" class="form-control"/>
            <input id="project_id" name="project_id" type="hidden" class="form-control"/>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label for="add_users_select" class="form-label">Добавить в проект: </label>
            <select class="form-select" id="add_users_select" name="project_member_id">
                <option value="-1">Выбрать</option>
            </select>
        </div>
    </div>

    <div id="timelines_part_2">
        <div class="line-dashed-small"></div>

        <h2>Таймлайны</h2>
        <div id="timeline_container_2">

        </div>
    </div>

    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" onclick="submitCreateForm('gantt-show-project-modal');"
                class="btn btn-primary">Сохранить
        </button>
        <button type="button" onclick="$.magnificPopup.close();" data-js-close-modal class="btn btn-secondary"
                style="margin-left: 5px">Закрыть
        </button>
    </div>
</form>