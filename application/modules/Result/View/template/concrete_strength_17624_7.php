<!--Прочность на сжатие ГОСТ 17624 п. 7 схема "В"-->
<div class="measurement-wrapper" id="concreteStrength">
    <h2 class="d-block mb-2"><?= $this->data['sheet']['name_ru'] ?></h2>

    <div class="concrete_strength_17624_7">
        <input type="hidden" name="form_data[<?=$this->data['ugtp_id']?>][sheet_name_ru]"
               value="<?= $this->data['sheet']['name_ru'] ?>" readonly>
        <input type="hidden" name="form_data[<?=$this->data['ugtp_id']?>][type]" value="concrete_strength_17624_7">

        <div class="row mb-3">
            <div class="col-auto">
                <label></label>
                <a class="nav-link bg-white text-dark <?= $this->data['measuring_property']['anchor_journal'] ? '' : 'icon-disabled' ?>"
                   id="measurementJournal" href="<?= $this->data['measuring_property']['anchor_journal'] ?? '' ?>"
                   title="Журнал листов расчёта">
                    <i class="fa-solid fa-list"></i>
                </a>
            </div>
            <div class="form-group col">
                <label for="scheme">Схема испытаний</label>
                <select class="form-select w-100" id="scheme" name="form_data[<?=$this->data['ugtp_id']?>][scheme]" required>
                    <option value="" selected>Выберите схему испытаний</option>
                    <option value="v" <?= 'v' === $this->data['measuring']['scheme'] ? 'selected' : '' ?>>Схема "В"</option>
                    <option value="g" <?= 'g' === $this->data['measuring']['scheme'] ? 'selected' : '' ?> disabled>Схема "Г"</option>
                </select>
            </div>
            <div class="form-group col">
                <label for="measurementList">Список листов расчёта</label>
                <select class="form-select w-100" id="measurementList" name="form_data[<?=$this->data['ugtp_id']?>][measurement_id]" data-ugtp="<?=$this->data['ugtp_id']?>">
                    <?php if (empty($this->data['measuring']['scheme'])): ?>
                        <option value="" disabled>Сначала выберите схему испытаний</option>
                    <?php endif; ?>
                    <?php if (!empty($this->data['measuring']['scheme'])): ?>
                        <?php foreach ($this->data['measuring_property']['graduations'] as $key => $val): ?>
                            <option value="<?= $val['id'] ?>"
                                <?= $val['id'] === $this->data['measuring']['measurement_id'] ? 'selected' : '' ?>
                                    data-scheme="<?= $this->data['measuring']['scheme'] ?>">№ <?= $val['number'] ?> - <?= $val['object'] ?>, от <?= $val['ru_date'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group col">
                <label for="сoncretingDate">Дата бетонирования</label>
                <input type="date" class="form-control bg-white" id="сoncretingDate" name="form_data[<?=$this->data['ugtp_id']?>][сoncreting_date]"
                       value="<?= $this->data['measuring']['сoncreting_date'] ?? date('Y-m-d') ?>">
            </div>
            <div class="form-group col">
                <label for="cipher">Шифр</label>
                <input type="text" class="form-control bg-white" id="cipher" name="form_data[<?=$this->data['ugtp_id']?>][cipher]"
                       value="<?= $this->data['measuring']['cipher'] ?>">
            </div>
        </div>

        <div id="calculationDataWrapper">
            <?php if ($this->data['measuring_property']['is_data_v']): ?>
            <div id="calculationData">
                <div class="row mb-3">
                    <div class="form-group col">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">R</div>
                            </div>
                            <input type="number" class="form-control bg-light-secondary do-not-clean" id="round-R" name="form_data[<?=$this->data['ugtp_id']?>][round_R]" step="any"
                                   value="<?= $this->data['measuring_property']['round_a'] ?>" readonly>
                            <input type="hidden" class="form-control bg-light-secondary do-not-clean" id="R" name="form_data[<?=$this->data['ugtp_id']?>][R]" step="any"
                                   value="<?= $this->data['measuring_property']['a'] ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group col">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">V</div>
                            </div>
                            <input type="number" class="form-control bg-light-secondary do-not-clean" id="round-V" name="form_data[<?=$this->data['ugtp_id']?>][round_V]" step="any"
                                   value="<?= $this->data['measuring_property']['round_b'] ?>" readonly>
                            <input type="hidden" class="form-control bg-light-secondary do-not-clean" id="V" name="form_data[<?=$this->data['ugtp_id']?>][V]" step="any"
                                   value="<?= $this->data['measuring_property']['b'] ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group col">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">r</div>
                            </div>
                            <input type="number" class="form-control bg-light-secondary do-not-clean" id="r" name="form_data[<?=$this->data['ugtp_id']?>][r]" step="any"
                                   value="<?= $this->data['measuring_property']['r'] ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group col">
                        <div class="input-group">
                            <span class="input-group-text">B</span>
                            <input type="text" class="form-control number-only bg-light-secondary do-not-clean" name="form_data[<?=$this->data['ugtp_id']?>][class]"
                                   value="<?= $this->data['measuring_property']['concrete_class'] ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group col">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Прибор</div>
                            </div>
                            <input type="text" class="form-control bg-light-secondary do-not-clean" id="measuringDevice" name="form_data[<?=$this->data['ugtp_id']?>][measuring_device]"
                                   value="<?= $this->data['measuring_property']['measuring_device'] ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group col">
                        <div class="input-group">
                            <select id="method" class="form-control bg-light-secondary do-not-clean pointer-events-none" name="form_data[<?=$this->data['ugtp_id']?>][method]" readonly>
                                <optgroup label="Метод отрыва со скалыванием">
                                    <option value="separation_0.04" <?= 'separation_0.04' === $this->data['measuring_property']['method'] ? 'selected' : '' ?>>Глубина 48 мм</option>
                                    <option value="separation_0.05" <?= 'separation_0.05' === $this->data['measuring_property']['method'] ? 'selected' : '' ?>>Глубина 35 мм</option>
                                    <option value="separation_0.06" <?= 'separation_0.06' === $this->data['measuring_property']['method'] ? 'selected' : '' ?>>Глубина 30 мм</option>
                                </optgroup>
                                <optgroup label="Метод скалывания ребра">
                                    <option value="chipping_0.04" <?= 'chipping_0.04' === $this->data['measuring_property']['method'] ? 'selected' : '' ?>>Скалывание ребра</option>
                                </optgroup>
                                <optgroup label="Разрушающий метод">
                                    <option value="destructive_0.02" <?= 'destructive_0.02' === $this->data['measuring_property']['method'] ? 'selected' : '' ?>>Разрушающий</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col">
                        <div class="input-group">
                            <input type="text" class="form-control number-only bg-light-secondary do-not-clean" id="dayToTest" name="form_data[<?=$this->data['ugtp_id']?>][day_to_test]"
                                   value="<?= $this->data['measuring_property']['day_to_test'] ?>" readonly>
                            <span class="input-group-text">суток</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        Вид установленной градуировочной зависимости: R = <?= $this->data['measuring']['round_R'] ?? '-' ?> * V <?= $this->data['measuring']['round_V'] ?? '-' ?>, Бетон: <?= $this->data['measuring']['class'] ?? '-' ?>, Прибор: <?= $this->data['measuring']['measuring_device'] ?? '-' ?>, Срок проведения испытаний: <?= $this->data['measuring']['day_to_test'] ?? '-' ?>, Среднеквадратическое отклонение S3: <?= $this->data['measuring']['S3'] ?? '-' ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <table class="table text-center align-middle table-bordered">
                            <thead>
                            <tr class="align-middle">
                                <th rowspan="2">Наименование конструкции</th>
                                <th colspan="3">Показания СИ</th>
                                <th colspan="2">Прочность бетона, МПа</th>
                                <th rowspan="2">Фактический класс бетона конструкции по прочности на сжатие</th>
                                <th rowspan="2">+/-</th>
                            </tr>
                            <tr class="align-middle">
                                <th colspan="2">Единичные значения</th>
                                <th>Среднее значение на контролируемом участке</th>
                                <th>Контролируе-мого участка</th>
                                <th>Конструкции</th>
                            </tr>
                            </thead>
                            <tbody class="construction-wrapper">
                            <tr class="construction-row">
                                <td class="name-wrapper" rowspan="<?= count($this->data['measuring']['mean']) ?>">
                                    <input type="text" class="form-control bg-light-secondary do-not-clean" name="form_data[<?=$this->data['ugtp_id']?>][name_for_protocol]"
                                           value="<?= $this->data['measuring_property']['name_for_protocol'] ?>" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control bg-white single-value-1" name="form_data[<?=$this->data['ugtp_id']?>][single_value_1][]"
                                           step="any" value="<?= $this->data['measuring']['single_value_1'][0] ?>">
                                </td>
                                <td>
                                    <input type="number" class="form-control single-value-2 <?= $this->data['measuring']['measuring_device'] === 'ИПС' ? 'bg-light-secondary' : 'bg-white' ?>"
                                           name="form_data[<?=$this->data['ugtp_id']?>][single_value_2][]" step="any"
                                           value="<?= $this->data['measuring']['single_value_2'][0] ?>"
                                        <?= $this->data['measuring']['measuring_device'] === 'ИПС' ? 'disabled' : '' ?>>
                                </td>
                                <td>
                                    <input type="number" class="form-control bg-white mean clear" name="form_data[<?=$this->data['ugtp_id']?>][mean][]"
                                           step="any" value="<?= $this->data['measuring']['mean'][0] ?>">
                                </td>
                                <td>
                                    <input type="number" class="form-control bg-white area-strength bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][single_values][]"
                                           step="any" value="<?= $this->data['measuring']['single_values'][0] ?>" readonly>
                                </td>
                                <td class="construction-strength-wrapper" rowspan="<?= count($this->data['measuring']['mean']) ?>">
                                    <input type="number" class="form-control bg-white bg-light-secondary"
                                           name="form_data[<?=$this->data['ugtp_id']?>][result_value]" step="any"
                                           value="<?= $this->data['measuring']['result_value'] ?>" readonly>
                                </td>
                                <td class="concrete-class-wrapper" rowspan="<?= count($this->data['measuring']['mean']) ?>">
                                    <input type="number" class="form-control bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][concrete_class]"
                                           step="any" value="<?= $this->data['measuring']['concrete_class'] ?>" readonly>
                                </td>
                                <td>
                                    <button class="btn mt-0 btn-square add-construction btn-primary" type="button">
                                        <i class="fa-solid fa-plus icon-fix"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php if ( isset($this->data['measuring']['mean']) && count($this->data['measuring']['mean']) > 1 ): ?>
                                <?php for ($i = 1; $i < count($this->data['measuring']['mean']); $i++): ?>
                                    <tr class="construction-row">
                                        <td>
                                            <input type="number" class="form-control single-value-1 bg-white"
                                                   name="form_data[<?=$this->data['ugtp_id']?>][single_value_1][]" step="any"
                                                   value="<?= $this->data['measuring']['single_value_1'][$i] ?>">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control single-value-2 <?= $this->data['measuring']['measuring_device'] === 'ИПС' ? 'bg-light-secondary' : 'bg-white' ?>"
                                                   name="form_data[<?=$this->data['ugtp_id']?>][single_value_2][]" step="any"
                                                   value="<?= $this->data['measuring']['single_value_2'][$i] ?>"
                                                <?= $this->data['measuring']['measuring_device'] === 'ИПС' ? 'disabled' : '' ?>>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control bg-white mean clear" name="form_data[<?=$this->data['ugtp_id']?>][mean][]"
                                                   step="any" value="<?= $this->data['measuring']['mean'][$i] ?>">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control bg-white area-strength bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][single_values][]"
                                                   step="any" value="<?= $this->data['measuring']['single_values'][$i] ?>" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-square del-construction mt-0 btn-danger">
                                                <i class="fa-solid fa-minus icon-fix"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <table class="table text-center align-middle table-bordered">
                            <thead>
                            <tr class="align-middle">
                                <th colspan="5">Расчет характеристик однородности бетона</th>
                                <th rowspan="3">Текущий коэффициент вариации прочности Vm,%</th>
                                <th rowspan="3">Коэффициент Кт</th>
                                <th rowspan="3">Факт. Прочность бетона в %</th>
                            </tr>
                            <tr class="align-middle">
                                <th colspan="4">Среднеквадратическое отклонение прочности, МПа (для группы конструкций</th>
                                <th>Среднеквадратическое отклонение с учетом установленной градуировочной зависимости</th>
                            </tr>
                            <tr class="align-middle">
                                <th>S1</th>
                                <th>S3=Sт.м.н.</th>
                                <th>S4</th>
                                <th>S2</th>
                                <th>Sm</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input type="number" class="form-control bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][S1]"
                                           step="any" value="<?= $this->data['measuring']['S1'] ?>" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][S3]"
                                           step="any" value="<?= $this->data['measuring_property']['S'] ?>" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][S4]"
                                           step="any" value="<?= $this->data['measuring']['S4'] ?>" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][S2]"
                                           step="any" value="<?= $this->data['measuring']['S2'] ?>" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][Sm]"
                                           step="any" value="<?= $this->data['measuring']['Sm'] ?>" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][Vm]"
                                           step="any" value="<?= $this->data['measuring']['Vm'] ?>" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control bg-white" name="form_data[<?=$this->data['ugtp_id']?>][Kt]"
                                           step="any" value="<?= $this->data['measuring']['Kt'] ?? 1.070 ?>">
                                </td>
                                <td>
                                    <input type="number" class="form-control bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][percent]"
                                           step="any" value="<?= $this->data['measuring']['percent'] ?>" readonly>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <lable for="comment">Примечание</lable>
                        <textarea class="form-control mw-100"
                                  rows="5" id="comment" name="form_data[<?=$this->data['ugtp_id']?>][comment]"><?= $this->data['measuring']['comment'] ?></textarea>
                    </div>
                </div>

                <div class="line-dashed-small"></div>

                <div class="row mb-4 btn-wrapper">
                    <?php if ( true ): ?>
                        <div class="col-auto pe-0">
                            <button type="button" class="btn btn-primary calculate-v me-2" data-ugtp="<?=$this->data['ugtp_id']?>">Рассчитать</button>
                        </div>
                        <div class="col-auto ps-0">
                            <button type="submit" class="btn btn-primary save">Сохранить</button>
                        </div>
                    <?php else: ?>
                        <div class="col-auto">
                            <div class="btn bg-light-secondary">Рассчитать</div>
                        </div>
                        <div class="col-auto ps-0">
                            <div class="btn bg-light-secondary">Сохранить</div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
            <!--./calculationData-->
            <?php endif; ?>
        </div>
        <!--./calculationDataWrapper-->
    </div>
</div>
<!--./measurement-wrapper-->