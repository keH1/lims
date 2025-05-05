<div class="measurement-wrapper" id="frost_gost">
    <h3 class="mb-3">Определение морозостойкости ГОСТ 33109-2014</h3>

    <input type="hidden" value="<?=$this->data['ugtp_id']?>" id="ugtp_id_frost">
    <input type="hidden" name="form_data[<?=$this->data['ugtp_id']?>][type]" value="d_frost" >

    <div class="formA mb-3">
        <table class="table table-fixed list_data mb-3">
            <thead>
            <tr class="table-secondary text-center align-middle">
                <th scope="col">Масса мерной пробы до испытания, г</th>
                <th scope="col">Масса остатка на сите с размером ячеек d, после определенного цикла испытания, г</th>
                <th scope="col">Потеря массы при испытании в отдельной фракции щебня (гравия), в процентах</th>
                <th scope="col">Содержание данной фракции, в процентах</th>
                <th scope="col">Морозостойкость зерен щебня (гравия)</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody class="frost_list">
            <?php if(empty($this->data['measuring']['form'])): ?>
                <tr class="frost_block" data-frost-id="0">
                    <td>
                        <input type="number" id="mass-before-<?=$this->data['ugtp_id']?>-0" class="form-control mass-before" name="form_data[<?=$this->data['ugtp_id']?>][form][mass_before][]" value="<?=$this->data['measuring']['form']['mass_before'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" id="mass-after-<?=$this->data['ugtp_id']?>-0" class="form-control mass-after" name="form_data[<?=$this->data['ugtp_id']?>][form][mass_after][]" value="<?=$this->data['measuring']['form']['mass_after'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" id="mass-loss-<?=$this->data['ugtp_id']?>-0" class="form-control mass-loss" name="form_data[<?=$this->data['ugtp_id']?>][form][mass_loss][]" value="<?=$this->data['measuring']['form']['mass_loss'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" id="content-of-fraction-<?=$this->data['ugtp_id']?>-0" class="form-control content-of-fraction" name="form_data[<?=$this->data['ugtp_id']?>][form][content_of_fraction][]" value="<?=$this->data['measuring']['form']['content_of_fraction'] ?? ''?>">
                    </td>
                    <td class="text-center align-middle span-input" rowspan="1">
                        <input type="number" class="form-control result-frost-gost"
                               step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value]"
                               value="<?= $this->data['measuring']['form']['result_value'] ?? '' ?>" readonly>
                    </td>
                    <td>
                        <button class="btn btn-success add-frost" type="button" style="border-radius: 10px">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.2502 6C11.2502 5.58579 11.586 5.25 12.0002 5.25C12.4145 5.25 12.7502 5.58579 12.7502 6V11.2502H18.0007C18.4149 11.2502 18.7507 11.586 18.7507 12.0002C18.7507 12.4145 18.4149 12.7502 18.0007 12.7502H12.7502V18.0007C12.7502 18.4149 12.4145 18.7507 12.0002 18.7507C11.586 18.7507 11.2502 18.4149 11.2502 18.0007V12.7502H6C5.58579 12.7502 5.25 12.4145 5.25 12.0002C5.25 11.586 5.58579 11.2502 6 11.2502H11.2502V6Z" fill="#ffffff"/>
                            </svg>
                        </button>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($this->data['measuring']['form']['mass_before'] as $key => $value): ?>
                    <?php if($key == 0): ?>
                        <tr class="frost_block" data-frost-id="0">
                            <td>
                                <input type="number" id="mass-before-<?=$this->data['ugtp_id']?>-0" class="form-control mass-before" name="form_data[<?=$this->data['ugtp_id']?>][form][mass_before][]" value="<?=$this->data['measuring']['form']['mass_before'][$key] ?? ''?>">
                            </td>
                            <td>
                                <input type="number" id="mass-after-<?=$this->data['ugtp_id']?>-0" class="form-control mass-after" name="form_data[<?=$this->data['ugtp_id']?>][form][mass_after][]" value="<?=$this->data['measuring']['form']['mass_after'][$key] ?? ''?>">
                            </td>
                            <td>
                                <input type="number" id="mass-loss-<?=$this->data['ugtp_id']?>-0" class="form-control mass-loss" name="form_data[<?=$this->data['ugtp_id']?>][form][mass_loss][]" value="<?=$this->data['measuring']['form']['mass_loss'][$key] ?? ''?>">
                            </td>
                            <td>
                                <input type="number" id="content-of-fraction-<?=$this->data['ugtp_id']?>-0" class="form-control content-of-fraction" name="form_data[<?=$this->data['ugtp_id']?>][form][content_of_fraction][]" value="<?=$this->data['measuring']['form']['content_of_fraction'][$key] ?? ''?>">
                            </td>
                            <td class="text-center align-middle span-input" rowspan="<?= count($this->data['measuring']['form']['mass_before']) ?>">
                                <input type="number" class="form-control result-frost-gost"
                                       step="any"
                                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value]"
                                       value="<?= $this->data['measuring']['form']['result_value'] ?? '' ?>" readonly>
                            </td>
                            <td>
                                <button class="btn btn-success add-frost" type="button" style="border-radius: 10px">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.2502 6C11.2502 5.58579 11.586 5.25 12.0002 5.25C12.4145 5.25 12.7502 5.58579 12.7502 6V11.2502H18.0007C18.4149 11.2502 18.7507 11.586 18.7507 12.0002C18.7507 12.4145 18.4149 12.7502 18.0007 12.7502H12.7502V18.0007C12.7502 18.4149 12.4145 18.7507 12.0002 18.7507C11.586 18.7507 11.2502 18.4149 11.2502 18.0007V12.7502H6C5.58579 12.7502 5.25 12.4145 5.25 12.0002C5.25 11.586 5.58579 11.2502 6 11.2502H11.2502V6Z" fill="#ffffff"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr class="frost_block" data-frost-id="<?= $key ?>">
                            <td>
                                <input type="number" id="mass-before-<?=$this->data['ugtp_id']?>-<?= $key ?>" class="form-control mass-before" name="form_data[<?=$this->data['ugtp_id']?>][form][mass_before][]" value="<?=$this->data['measuring']['form']['mass_before'][$key] ?? ''?>">
                            </td>
                            <td>
                                <input type="number" id="mass-after-<?=$this->data['ugtp_id']?>-<?= $key ?>" class="form-control mass-after" name="form_data[<?=$this->data['ugtp_id']?>][form][mass_after][]" value="<?=$this->data['measuring']['form']['mass_after'][$key] ?? ''?>">
                            </td>
                            <td>
                                <input type="number" id="mass-loss-<?=$this->data['ugtp_id']?>-<?= $key ?>" class="form-control mass-loss" name="form_data[<?=$this->data['ugtp_id']?>][form][mass_loss][]" value="<?=$this->data['measuring']['form']['mass_loss'][$key] ?? ''?>">
                            </td>
                            <td>
                                <input type="number" id="content-of-fraction-<?=$this->data['ugtp_id']?>-<?= $key ?>" class="form-control content-of-fraction" name="form_data[<?=$this->data['ugtp_id']?>][form][content_of_fraction][]" value="<?=$this->data['measuring']['form']['content_of_fraction'][$key] ?? ''?>">
                            </td>
                            <td>
                                <button class="btn btn-success add-frost" type="button" style="border-radius: 10px">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.2502 6C11.2502 5.58579 11.586 5.25 12.0002 5.25C12.4145 5.25 12.7502 5.58579 12.7502 6V11.2502H18.0007C18.4149 11.2502 18.7507 11.586 18.7507 12.0002C18.7507 12.4145 18.4149 12.7502 18.0007 12.7502H12.7502V18.0007C12.7502 18.4149 12.4145 18.7507 12.0002 18.7507C11.586 18.7507 11.2502 18.4149 11.2502 18.0007V12.7502H6C5.58579 12.7502 5.25 12.4145 5.25 12.0002C5.25 11.586 5.58579 11.2502 6 11.2502H11.2502V6Z" fill="#ffffff"/>
                                    </svg>
                                </button>
                                <button class="btn btn-danger del-frost" type="button" style="border-radius: 10px">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.25 12C5.25 11.5858 5.58579 11.25 6 11.25H18.0007C18.4149 11.25 18.7507 11.5858 18.7507 12C18.7507 12.4142 18.4149 12.75 18.0007 12.75H6C5.58579 12.75 5.25 12.4142 5.25 12Z" fill="#ffffff"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" id="frostGostCalculate" class="btn btn-primary frost-gost-calculate"
                        name="frost_gost_calculate">Рассчитать</button>
            </div>
            <div class="col flex-grow-0">
                <button type="submit" class="btn btn-primary save-frost">Сохранить</button>
            </div>
        </div>
    </div>
</div>
