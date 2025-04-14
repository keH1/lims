<?php
    $arrSize = ["200", "300"];
?>

<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-1">
                <a class="nav-link link-icon" href="<?=URI?>/grain/list/" title="Вернуться к списку">
                    <svg class="icon" width="25" height="25">
                        <use xlink:href="<?=URI?>/assets/images/icons.svg#list"/>
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</header>

<form id="grain-form" action="<?=URI?>/grain/updateGrainList/<?= $this->data['grain_list_id'] ?>" method="POST">
    <div class="tab b-none mb-3">
        <!-- Общая информация -->
        <input type="radio" name="tab-btn" id="tab-btn-1" checked>
        <label for="tab-btn-1">Общая информация</label>
        <!-- Информация о ситах -->
        <input type="radio" name="tab-btn" id="tab-btn-2">
        <label for="tab-btn-2">Информация о ситах</label>
        <!-- Сита с круглыми отверстиями -->
        <input type="radio" name="tab-btn" id="tab-btn-3">
        <label for="tab-btn-3">Сита с круглыми отверстиями</label>
        <!-- Сита с квадратными отверстиями -->
        <input type="radio" name="tab-btn" id="tab-btn-4">
        <label for="tab-btn-4">Сита с квадратными отверстиями</label>

        <!-- Таб 1: Общая информация -->
        <div class="tab-content" id="content-1">
            <label for="grain_name" class="mt-2">Название листа в результатах испытаний</label>
            <div class="input-container">
                <input class="form-control mb-10" id="grain_name" type="text" name="grain_list_name"
                       value="<?= $this->data['grain']['name'] ?>">
            </div>

            <label for="grain_gost" class="mt-2">ГОСТ зернового состава</label>
            <div class="input-container">
                <select class="grain__gost_list w-100 mw-100" id="grain_gost"
                        name="grain_list_gost">
                    <option value="0" selected>Выберите ГОСТ</option>
                    <?php foreach ($this->data['grain_list_gost'] as $item): ?>
                        <option value="<?= $item['gost_id'] ?>" <?= ($item['grain_list_id'] == $this->data['grain_list_id'] ? "selected" : "") ?>>
                            <?= $item['gost'] . ' ' . $item['gost_point'] . ' | ' . $item['gost_specification'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Таб 2: Информация о ситах -->
        <div class="tab-content" id="content-2">
            <h4>Информация о ситах</h4>
            <table class="table table-bordered">
                <thead>
                <tr class="text-center">
                    <td>Размер</td>
                    <td>От</td>
                    <td>До</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($this->data['grain_seave_size'][2]['seave_type'][0]['values'] as $key => $sieveTitle): ?>
                    <tr class="text-center align-middle">
                        <td><?= htmlspecialchars($sieveTitle, ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <input class="form-control" type="number" step="any" name="NORM1[<?= $key ?>]"
                                   value="<?= isset($this->data['grain']['norm1'][$key]) ? htmlspecialchars($this->data['grain']['norm1'][$key], ENT_QUOTES, 'UTF-8') : '' ?>">
                        </td>
                        <td>
                            <input class="form-control" type="number" step="any"
                                   name="NORM2[<?= $key ?>]"
                                   value="<?= isset($this->data['grain']['norm2'][$key]) ? htmlspecialchars($this->data['grain']['norm2'][$key], ENT_QUOTES, 'UTF-8') : '' ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Таб 3-4 -->
        <?php $inc = 3; ?>
        <?php foreach($this->data['grain_seave_size'] as $typeSeave): ?>
            <div class="tab-content" id="content-<?= $inc ?>">
                <div class="flex-between">
                    <?php $typeSeaveSign = 0; ?>
                    <?php foreach($typeSeave['seave_type'] as $item): ?>
                        <div>
                            <h4><?= $item['type_to_title'] ?></h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="text-center">
                                        <td>Размер</td>
                                        <td>От</td>
                                        <td>До</td>
                                    </tr>
                                    <?php for($i = 0; $i < count((array)$item['values']); $i++): ?>
                                        <tr class="text-center align-middle">
                                            <td><?= $item['values'][$i] ?></td>
                                            <td>
                                                <input class="form-control" type="number" step="any"
                                                       name="grain[<?= $typeSeave['seave_main_type'] ?>][<?= $item['type_to_input'] ?>][seave_values__from][<?= $i?>]"
                                                       value="<?= $this->data['grain']['data'][$typeSeave['seave_main_type']][$arrSize[$typeSeaveSign]]['seave_values__from'][$i] ?>"
                                                >
                                            </td>
                                            <td>
                                                <input class="form-control" type="number" step="any"
                                                       name="grain[<?= $typeSeave['seave_main_type'] ?>][<?= $item['type_to_input'] ?>][seave_values__to][<?= $i?>]"
                                                       value="<?= $this->data['grain']['data'][$typeSeave['seave_main_type']][$arrSize[$typeSeaveSign]]['seave_values__to'][$i] ?>"
                                                >
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div> 
                    <?php $typeSeaveSign++; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php $inc++; if ($inc > 4) { break; } ?>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>