<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link" href="<?=URI?>/docTemplate/listTemplate/" title="Журнал шаблонов">
                    <i class="fa-solid fa-list"></i>
                </a>
            </li>
            
            <li class="nav-item me-2 d-none">
                <a class="nav-link popup-help" href="/ulab/help/" title="Техническая поддержка">
                    <i class="fa-solid fa-question"></i>
                </a>
            </li>
        </ul>
    </nav>
</header>


<table id="journal" class="table table-striped journal">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap">Макрос</th>
        <th scope="col" class="text-nowrap">Описание</th>
        <th scope="col" class="text-nowrap">Тип</th>
        <th scope="col" class="text-nowrap"></th>
    </tr>
    <tr class="header-search">
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <select class="form-control search">
                <option value="">Все</option>
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

