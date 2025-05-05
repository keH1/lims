$(function ($) {
    const body = $('body')

    let ugtpid = $("#zrn_ugtp").val()

    const FORM_4_BEFORE_5_6 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-11_2 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][11_2]" data-fraction="11_2" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][11_2]" data-fraction="11_2" data-trial="1"
                                                   value="11.2" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-8 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][8]" data-fraction="8" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][8]" data-fraction="8" 
                                                   data-trial="1" value="8" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][8]"
                                                   data-fraction="8" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-5_6 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][5_6]" data-fraction="5_6" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][5_6]" data-fraction="5_6" data-trial="1"
                                                   value="5.6" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-4 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][4]" data-fraction="4" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][4]" data-fraction="4" data-trial="1"
                                                   value="4" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][4]"
                                                   data-fraction="4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-2 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][2]" data-fraction="2" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][2]" data-fraction="2" data-trial="1"
                                                   value="2" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][2]"
                                                   data-fraction="2" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][2]"
                                                   data-fraction="2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][2]"
                                                   data-fraction="2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][2]"
                                                   data-fraction="2" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    const ABOVE_5_6_BEFORE_8 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-16 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][16]" data-fraction="16" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][16]" data-fraction="16" data-trial="1"
                                                   value="16" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-11_2 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][11_2]" data-fraction="11_2" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][11_2]" data-fraction="11_2" 
                                                   data-trial="1" value="11.2" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-8 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][8]" data-fraction="8" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][8]" data-fraction="8" data-trial="1"
                                                   value="8" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][8]"
                                                   data-fraction="8" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-5_6 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][5_6]" data-fraction="5_6" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][5_6]" data-fraction="5_6" data-trial="1"
                                                   value="5.6" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-2_8 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-2_8"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][2_8]" data-fraction="2_8" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-2_8"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][2_8]" data-fraction="2_8" data-trial="1"
                                                   value="2.8" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-2_8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][2_8]"
                                                   data-fraction="2_8" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-2_8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][2_8]"
                                                   data-fraction="2_8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-2_8"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][2_8]"
                                                   data-fraction="2_8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-2_8"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][2_8]"
                                                   data-fraction="2_8" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    const ABOVE_8_BEFORE_11_2 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-22_4 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="22.4" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-16 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][16]" data-fraction="16" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][16]" data-fraction="16" 
                                                   data-trial="1" value="16" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-11_2 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][11_2]" data-fraction="11_2" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][11_2]" data-fraction="11_2" data-trial="1"
                                                   value="11.2" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-8 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][8]" data-fraction="8" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][8]" data-fraction="8" data-trial="1"
                                                   value="8" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][8]"
                                                   data-fraction="8" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-4 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][4]" data-fraction="4" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][4]" data-fraction="4" data-trial="1"
                                                   value="4" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][4]"
                                                   data-fraction="4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    const ABOVE_11_2_BEFORE_16 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-31_5 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][31_5]" data-fraction="31_5" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][31_5]" data-fraction="31_5" data-trial="1"
                                                   value="31.5" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-22_4 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][22_4]" data-fraction="22_4" 
                                                   data-trial="1" value="22.4" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-16 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][16]" data-fraction="16" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][16]" data-fraction="16" data-trial="1"
                                                   value="16" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-11_2 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][11_2]" data-fraction="11_2" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][11_2]" data-fraction="11_2" data-trial="1"
                                                   value="11.2" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-5_6 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][5_6]" data-fraction="5_6" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][5_6]" data-fraction="5_6" data-trial="1"
                                                   value="5.6" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    const ABOVE_16_BEFORE_22_4 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-45 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][45]" data-fraction="45" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][45]" data-fraction="45" data-trial="1"
                                                   value="45" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][45]"
                                                   data-fraction="45" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-31_5 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][31_5]" data-fraction="31_5" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][31_5]" data-fraction="31_5" 
                                                   data-trial="1" value="31.5" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-22_4 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="22.4" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-16 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][16]" data-fraction="16" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][16]" data-fraction="16" data-trial="1"
                                                   value="16" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-8 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][8]" data-fraction="8" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][8]" data-fraction="8" data-trial="1"
                                                   value="8" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][8]"
                                                   data-fraction="8" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    const ABOVE_22_4_BEFORE_31_5 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-63 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][63]" data-fraction="63" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][63]" data-fraction="63" data-trial="1"
                                                   value="63" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][63]"
                                                   data-fraction="63" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-45 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][45]" data-fraction="45" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][45]" data-fraction="45" 
                                                   data-trial="1" value="45" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][45]"
                                                   data-fraction="45" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-31_5 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][31_5]" data-fraction="31_5" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][31_5]" data-fraction="31_5" 
                                                   data-trial="1" value="31.5" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-22_4 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="22.4" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-11_2 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][11_2]" data-fraction="11_2" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][11_2]" data-fraction="11_2" data-trial="1"
                                                   value="11.2" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    const ABOVE_31_5_BEFORE_45 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-90 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][90]" data-fraction="90" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][90]" data-fraction="90" data-trial="1"
                                                   value="90" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][90]"
                                                   data-fraction="90" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-63 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][63]" data-fraction="63" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][63]" data-fraction="63" data-trial="1"
                                                   value="63" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][63]"
                                                   data-fraction="63" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-45 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][45]" data-fraction="45" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][45]" data-fraction="45" 
                                                   data-trial="1" value="45" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][45]"
                                                   data-fraction="45" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-31_5 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][31_5]" data-fraction="31_5" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][31_5]" data-fraction="31_5" 
                                                   data-trial="1" value="31.5" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-16 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][16]" data-fraction="16" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][16]" data-fraction="16" data-trial="1"
                                                   value="16" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    const ABOVE_45_BEFORE_63 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-126 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][126]" data-fraction="126" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][126]" data-fraction="126" data-trial="1"
                                                   value="126" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][126]"
                                                   data-fraction="126" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][126]"
                                                   data-fraction="126" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][126]"
                                                   data-fraction="126" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][126]"
                                                   data-fraction="126" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-90 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][90]" data-fraction="90" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][90]" data-fraction="90" data-trial="1"
                                                   value="90" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][90]"
                                                   data-fraction="90" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-63 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][63]" data-fraction="63" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][63]" data-fraction="63" data-trial="1"
                                                   value="63" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][63]"
                                                   data-fraction="63" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-45 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][45]" data-fraction="45" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][45]" data-fraction="45" 
                                                   data-trial="1" value="45" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][45]"
                                                   data-fraction="45" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-22_4 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="22.4" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    const FORM_63_BEFORE_90 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-180 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-180"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][180]" data-fraction="180" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-180"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][180]" data-fraction="180" data-trial="1"
                                                   value="180" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-180"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][180]"
                                                   data-fraction="180" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-180"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][180]"
                                                   data-fraction="180" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-180"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][180]"
                                                   data-fraction="180" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-180"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][180]"
                                                   data-fraction="180" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-126 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][126]" data-fraction="126" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][126]" data-fraction="126" data-trial="1"
                                                   value="126" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][126]"
                                                   data-fraction="126" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][126]"
                                                   data-fraction="126" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][126]"
                                                   data-fraction="126" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][126]"
                                                   data-fraction="126" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-90 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][90]" data-fraction="90" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][90]" data-fraction="90" data-trial="1"
                                                   value="90" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][90]"
                                                   data-fraction="90" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-63 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][63]" data-fraction="63" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][63]" data-fraction="63" data-trial="1"
                                                   value="63" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][63]"
                                                   data-fraction="63" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-31_5 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][31_5]" data-fraction="31_5" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][31_5]" data-fraction="31_5" 
                                                   data-trial="1" value="31.5" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    //широкие фракции
    const FORM_4_BEFORE_8 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-16 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][16]" data-fraction="16" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][16]" data-fraction="16" data-trial="1"
                                                   value="16" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-11_2 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][11_2]" data-fraction="11_2" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][11_2]" data-fraction="11_2" 
                                                   data-trial="1" value="11.2" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-8 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][8]" data-fraction="8" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][8]" data-fraction="8" data-trial="1"
                                                   value="8" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][8]"
                                                   data-fraction="8" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-5_6 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][5_6]" data-fraction="5_6" data-trial="1"
                                                   value="D/1,4" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][5_6]" data-fraction="5_6" data-trial="1"
                                                   value="5.6" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-5_6"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][5_6]"
                                                   data-fraction="5_6" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-4 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][4]" data-fraction="4" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][4]" data-fraction="4" data-trial="1"
                                                   value="4" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][4]"
                                                   data-fraction="4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-2 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][2]" data-fraction="2" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][2]" data-fraction="2" data-trial="1"
                                                   value="2" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][2]"
                                                   data-fraction="2" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][2]"
                                                   data-fraction="2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][2]"
                                                   data-fraction="2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-2"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][2]"
                                                   data-fraction="2" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    const FORM_8_BEFORE_16 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-31_5 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][31_5]" data-fraction="31_5" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][31_5]" data-fraction="31_5" 
                                                   data-trial="1" value="31.5" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-22_4 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="22.4" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-16 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][16]" data-fraction="16" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][16]" data-fraction="16" data-trial="1"
                                                   value="16" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-11_2 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][11_2]" data-fraction="11_2" data-trial="1"
                                                   value="D/1,4" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][11_2]" data-fraction="11_2" 
                                                   data-trial="1" value="11.2" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-11_2"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][11_2]"
                                                   data-fraction="11_2" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-8 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][8]" data-fraction="8" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][8]" data-fraction="8" data-trial="1"
                                                   value="8" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][8]"
                                                   data-fraction="8" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-4 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][4]" data-fraction="4" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][4]" data-fraction="4" data-trial="1"
                                                   value="4" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][4]"
                                                   data-fraction="4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-4"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][4]"
                                                   data-fraction="4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    const FORM_16_BEFORE_31_5 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-63 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][63]" data-fraction="63" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][63]" data-fraction="63" data-trial="1"
                                                   value="63" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][63]"
                                                   data-fraction="63" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-45 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][45]" data-fraction="45" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][45]" data-fraction="45" 
                                                   data-trial="1" value="45" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][45]"
                                                   data-fraction="45" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-31_5 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][31_5]" data-fraction="31_5" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][31_5]" data-fraction="31_5" 
                                                   data-trial="1" value="31.5" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-22_4 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="D/1,4" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][22_4]" data-fraction="22_4" data-trial="1"
                                                   value="22.4" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-22_4"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][22_4]"
                                                   data-fraction="22_4" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-16 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][16]" data-fraction="16" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][16]" data-fraction="16" data-trial="1"
                                                   value="16" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-8 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][8]" data-fraction="8" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][8]" data-fraction="8" data-trial="1"
                                                   value="8" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" min="0" alue="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][8]"
                                                   data-fraction="8" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-8"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][8]"
                                                   data-fraction="8" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`
    const FORM_31_5_BEFORE_63 = `<table class="table table-rubble table-fixed mb-4">
                                <thead>
                                <tr class="table-secondary text-center align-middle">
                                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                                    <th scope="col" class="border-0">Испытание</th>
                                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                                    <th scope="col" class="border-0">Проход</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-126 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][126]" data-fraction="126" data-trial="1"
                                                   value="2D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][126]" data-fraction="126" data-trial="1"
                                                   value="126" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][126]"
                                                   data-fraction="126" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][126]"
                                                   data-fraction="126" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][126]"
                                                   data-fraction="126" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-126"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][126]"
                                                   data-fraction="126" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-90 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][90]" data-fraction="90" data-trial="1"
                                                   value="1,4D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][90]" data-fraction="90" data-trial="1"
                                                   value="90" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][90]"
                                                   data-fraction="90" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-90"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][90]"
                                                   data-fraction="90" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-63 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][63]" data-fraction="63" data-trial="1"
                                                   value="D" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][63]" data-fraction="63" data-trial="1"
                                                   value="63" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][63]"
                                                   data-fraction="63" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-63"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][63]"
                                                   data-fraction="63" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-45 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][45]" data-fraction="45" data-trial="1"
                                                   value="D/1,4" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][45]" data-fraction="45" 
                                                   data-trial="1" value="45" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][45]"
                                                   data-fraction="45" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-45"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][45]"
                                                   data-fraction="45" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-31_5 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][31_5]" data-fraction="31_5" data-trial="1"
                                                   value="d" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][31_5]" data-fraction="31_5" 
                                                   data-trial="1" value="31.5" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-31_5"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][31_5]"
                                                   data-fraction="31_5" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-16 tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center name name-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][16]" data-fraction="16" data-trial="1"
                                                   value="d/2" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][16]" data-fraction="16" data-trial="1"
                                                   value="16" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][16]"
                                                   data-fraction="16" data-trial="1" step="any" min="0"
                                                   value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-16"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][16]"
                                                   data-fraction="16" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    <tr class="tr-low tr-trial-1">
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-cente name name-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][name][low]" data-fraction="low" data-trial="1"
                                                   value="<" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="text" class="form-control text-center fraction fraction-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][fraction][low]" data-fraction="low" data-trial="1"
                                                   value="менее" readonly>
                                        </td>
                                        <th class="text-center align-middle">1</th>
                                        <td>
                                            <input type="number" class="form-control private-remainder private-remainder-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" min="0" value="">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][private_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][total_remainder_by_mass][1][low]"
                                                   data-fraction="low" data-trial="1" step="any" value="" readonly>
                                        </td>
                                        <td class="align-middle" >
                                            <input type="number" class="form-control passed passed-low"
                                                   name="form_data[${ugtpid}][form][grain_composition][passed][low]"
                                                   data-fraction="low" data-trial="1" step="any"
                                                   value="" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`

    function resetValue(items) {
        items.each(function (index, item) {
            $(this).val("");
        });
    }

    //Выбор фракции
    let lastValue = $('.wrapper_fraction .fraction').val()
    body.on('change', '.wrapper_fraction .fraction', function (e) {
        const message = $('.grain-composition-wrapper span.message'),
            tableWrapper = $('.grain-composition-wrapper .table-wrapper')

        let val = $(this).val()

        if (!confirm('Внимание! При смене фракции удаляться все резутаты расчетов. Продолжить?')) {
            $(this).val(lastValue)
            return false;
        }

        $.data(this, 'current', $(this).val())

        const inputReadonly = $("input[readonly]:visible:not('.fraction')")
        resetValue(inputReadonly)

        $('.panel-body.panel-hidden').addClass('d-none')

        if ( $('.grain-composition-wrapper .table-rubble').length !== 0 ) {
            $('.grain-composition-wrapper .table-rubble').remove()
        }

        let table = ''
        switch (val) {
            case '4_5.6':
                table = FORM_4_BEFORE_5_6
                break
            case '5.6_8':
                table = ABOVE_5_6_BEFORE_8
                break
            case '8_11.2':
                table = ABOVE_8_BEFORE_11_2
                break
            case '11.2_16':
                table = ABOVE_11_2_BEFORE_16
                break
            case '16_22.4':
                table = ABOVE_16_BEFORE_22_4
                break
            case '22.4_31.5':
                table = ABOVE_22_4_BEFORE_31_5
                break
            case '31.5_45':
                table = ABOVE_31_5_BEFORE_45
                break
            case '45_63':
                table = ABOVE_45_BEFORE_63
                break
            case '63_90':
                table = FORM_63_BEFORE_90
                break
            case '4_8':
                table = FORM_4_BEFORE_8
                break
            case '8_16':
                table = FORM_8_BEFORE_16
                break
            case '16_31.5':
                table = FORM_16_BEFORE_31_5
                break
            case '31.5_63':
                table = FORM_31_5_BEFORE_63
                break
        }

        tableWrapper.html(table)
    })

    function getMessageErrorContent(messageError = "") {
        if (!messageError) {
            return false;
        }

        return `<div class="messages">
              <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                  <div>
                      ${messageError}
                  </div>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          </div>`;
    }

    /** Расчеты **/
    //ЧО на ситах, % по массе
    function getPrivateRemainderByMass(sampleMass, privateRemainders) {
        let response = {}

        if (typeof obj !== 'object' && $.isEmptyObject(privateRemainders)) {
            return response
        }

        for (const elem of privateRemainders) {
            let fraction = $(elem).data('fraction'),
                val = +$(elem).val()

            let result = (val / sampleMass) * 100;

            response[fraction] = result
        }

        return response
    }

    function getArithmeticMean(data, amountData) {
        if (!$.isArray(data) || data.length === 0 || amountData <= 0) {
            return false;
        }

        let sumData = data.reduce(function (acc, val) {
            return acc + val;
        }, 0);

        return sumData / amountData;
    }

    //ПО на ситах, % по массе
    function getTotalRemainderByMass(privateRemaindersByMass) {
        let response = {},
            totalRemainderByMass = 0

        if (typeof obj !== 'object' && $.isEmptyObject(privateRemaindersByMass)) {
            return response
        }

        for (const elem of privateRemaindersByMass) {
            let fraction = $(elem).data('fraction'),
                val = +$(elem).val()

            totalRemainderByMass += val

            if (val === 0) {
                response[fraction] = val
            } else {
                //response[fraction] = round(totalRemainderByMass, 1)
                response[fraction] = round(totalRemainderByMass, 1)
            }
        }

        return response
    }

    function sum(obj) {
        return Object.keys(obj)
            .reduce(function(sum, key){
                return sum + parseFloat(+obj[key]);
            }, 0)
    }

    /** События **/
    //Определение зернового состава ГОСТ 33029-2014 (2 параллельных определение; сумма частных остатков не отличается более чем на 1% от массы пробы; результат -среднее арифметическое значение с точностью до 0,1%)
    body.on("click", "#calculateGrainComposition", function () {
        const grainCompositionWrapper = $('.grain-composition-wrapper'),
            inputPrivateRemainder1 = $('.private-remainder[data-trial="1"]'),
            inputReadonly1 = grainCompositionWrapper.find("input[readonly][data-trial='1']:not('.fraction'):not('.name')")

        let sampleMass1 = +grainCompositionWrapper.find('.sample-mass[data-trial="1"]').val(),
            fraction = $('.wrapper_fraction .fraction').val()

        let privateRemaindersKeyDiameter = {}

        grainCompositionWrapper.find(".messages").remove()

        if (!fraction) {
            let messageError = "Внимание! Для расчета значений выбирете фракцию!";

            let messageErrorContent = getMessageErrorContent(messageError)

            grainCompositionWrapper.prepend(messageErrorContent)

            const inputReadonly = $("input[readonly]:visible:not('.fraction')")

            resetValue(inputReadonly)
            return false
        }


        //ЧО на ситах, % по массе
        let privateRemaindersByMass1 = getPrivateRemainderByMass(
            sampleMass1,
            inputPrivateRemainder1
        )

        for (let key in privateRemaindersByMass1) {
            $(`.private-remainders-by-mass-${key}[data-trial="1"]`).val(
                round(privateRemaindersByMass1[key], 2).toFixed(2)
            )
        }


        //ПО на ситах, % по массе
        const inputPrivateRemaindersByMass1 = $('.private-remainders-by-mass[data-trial="1"]')

        let totalRemainderByMass1 = getTotalRemainderByMass(inputPrivateRemaindersByMass1)


        for (let key in totalRemainderByMass1) {
            $(`.total-remainder-by-mass-${key}[data-trial="1"]`).val(
                totalRemainderByMass1[key].toFixed(2)
            )

            $(`.average-total-remainder-${key}`).val(totalRemainderByMass1[key].toFixed(1))


            let passed = 100 - totalRemainderByMass1[key];

            $(`.passed-${key}`).val(passed.toFixed(1))
        }


        //сумма частных остатков не отличается более чем на 1% от массы пробы
        let arrPrivateRemainder1 = $.map(inputPrivateRemainder1, function(elem, i) {
            return $(elem).val()
        })

        let diff1 = Math.abs( (sampleMass1 / sum(arrPrivateRemainder1) ) * 100 - 100 )

        if(diff1 > 1) {
            let messageError = 'Сумма частных остатков в первом испытании отличается от массы более чем на 1%, повторите испытание'

            let messageErrorContent = getMessageErrorContent(messageError)

            grainCompositionWrapper.prepend(messageErrorContent)
            resetValue(inputReadonly1)
            $('.average-total-remainder').val('')
            $('.passed').val('')
            return false
        }
    })
})