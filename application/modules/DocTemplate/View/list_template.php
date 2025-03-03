<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link popup-with-form" href="#add-modal-form" title="Новый шаблон">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link" href="<?=URI?>/docTemplate/listMacros/" title="Журнал макросов">
                    <i class="fa-solid fa-list"></i>
                </a>
            </li>
            <li class="nav-item me-2 d-none">
                <a class="nav-link popup-help" href="/ulab/help/" title="ПОМОГИТЕ">
                    <i class="fa-solid fa-question"></i>
                </a>
            </li>
        </ul>
    </nav>
</header>


<table id="journal_template" class="table table-striped journal">
    <thead>
    <tr class="table-light">
        <th scope="col"></th>
        <th scope="col" class="text-nowrap">Название</th>
        <th scope="col" class="text-nowrap">Шаблон</th>
        <th scope="col" class="text-nowrap">Описание</th>
        <th scope="col" class="text-nowrap">Тип</th>
        <th scope="col" class="text-nowrap"></th>
    </tr>
    <tr class="header-search">
        <th scope="col">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <select class="form-control search">
                <option value="">Тип шаблона</option>
                <?php foreach ($this->data['type_list'] as $item): ?>
                    <option value="<?=$item['id']?>"><?=$item['name']?></option>
                <?php endforeach; ?>
            </select>
        </th>
        <th scope="col">
        </th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<form id="add-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/docTemplate/addTemplate/" method="post" enctype="multipart/form-data">
    <div class="title mb-3 h-2">
        Добавление шаблона
    </div>

    <div class="line-dashed-small"></div>

    <div class="mb-3">
        <label class="form-label">Название <span class="redStars">*</span></label>
        <input type="text" name="form[name]" class="form-control template_name" value="" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Файл <span class="redStars">*</span></label>
        <input type="file" name="file" class="form-control" value="" accept=".doc, .docx" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Тип <span class="redStars">*</span></label>
        <select name="form[id_template_type]" class="form-control template_type" required>
            <option value="">Тип шаблона</option>
            <?php foreach ($this->data['type_list'] as $item): ?>
                <option value="<?=$item['id']?>"><?=$item['name']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Описание</label>
        <textarea name="form[description]" class="form-control template_description"></textarea>
    </div>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>


<form id="edit-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/docTemplate/addTemplate/" method="post" enctype="multipart/form-data">
    <div class="title mb-3 h-2">
        Редактирование шаблона
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" name="id" class="template_id" value="">

    <div class="mb-3">
        <label class="form-label">Название <span class="redStars">*</span></label>
        <input type="text" name="form[name]" class="form-control template_name" value="" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Файл</label>
        <input type="file" name="file" class="form-control" value="" accept=".doc, .docx">
    </div>

    <div class="mb-3">
        <label class="form-label">Тип <span class="redStars">*</span></label>
        <select name="form[id_template_type]" class="form-control template_type" required>
            <option value="">Тип шаблона</option>
            <?php foreach ($this->data['type_list'] as $item): ?>
                <option value="<?=$item['id']?>"><?=$item['name']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Описание</label>
        <textarea name="form[description]" class="form-control template_description"></textarea>
    </div>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
