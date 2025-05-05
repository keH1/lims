$(function ($) {
    const body = $('body')

    let ugtpId = $('#ps_ugtp').val()

    // function roundPlus(x, n) {
    //     if(isNaN(x) || isNaN(n)) return false;
    //     let m = Math.pow(10, n);
    //     return Math.round(x * m) / m;
    // }
    //
    // function GetSwelling() {
    //
    //     let selectSeries;
    //
    //     function GetSelectionVariable(series, arrParam) {
    //
    //         let arrCheck = [];
    //         let arrUnique = [];
    //         let sign = 0;
    //         arrParam.forEach(function(trial) {
    //
    //             m4 = Number($(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_air_after_vacuum][${series}][${trial}]']`).val());
    //             m = Number($(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_air][${series}][${trial}]']`).val());
    //
    //             console.log(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_air_after_vacuum][${series}][${trial}]']`)
    //
    //             diff_m4_m = roundPlus((m4 - m), 2);
    //             $(`[name='form_data[${ugtpId}][swelling_32761][diff_m4_m][${series}][${trial}]']`).val(diff_m4_m);
    //
    //             m2 = Number($(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_air_after_saturation_soaking_water][${series}][${trial}]']`).val());
    //             m3 = Number($(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_water_after_saturation_soaking_water][${series}][${trial}]']`).val());
    //
    //             diff_m2_m3 = roundPlus((m2 - m3), 2);
    //             $(`[name='form_data[${ugtpId}][swelling_32761][diff_m2_m3][${series}][${trial}]']`).val(diff_m2_m3);
    //
    //             w = roundPlus(((m4 - m) / (m2 - m3)) * 100, 2);
    //             $(`[name='form_data[${ugtpId}][swelling_32761][water_saturation][${series}][${trial}]']`).val(w);
    //
    //             if(w >= 4 && w <= 5) {
    //                 if(sign == 0) {
    //                     arrCheck.push(series);
    //                     arrUnique = [...new Set(arrCheck)];
    //                     sign++;
    //                 }
    //
    //                 $($(`[name='form_data[${ugtpId}][swelling_32761][water_saturation][${series}][${trial}]']`).parent().css("border", "2px solid red"));
    //
    //                 if($(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_air_trial][${series}][${trial}]']`).val() != "") {
    //
    //                     arrUnique.forEach(function(selectionSeries) {
    //
    //                         selectSeries = selectionSeries;
    //
    //                         m5 = Number($(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_air_trial][${selectionSeries}][${trial}]']`).val());
    //                         m6 = Number($(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_water_trial][${selectionSeries}][${trial}]']`).val());
    //
    //                         diff_m5_m6 = roundPlus((m5 - m6), 2);
    //                         $(`[name='form_data[${ugtpId}][swelling_32761][diff_m5_m6][${selectionSeries}][${trial}]']`).val(diff_m5_m6);
    //
    //                         m = Number($(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_air][${selectionSeries}][${trial}]']`).val());
    //                         m1 = Number($(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_water][${selectionSeries}][${trial}]']`).val());
    //
    //                         diff_m_m1 = roundPlus((m - m1), 2);
    //                         $(`[name='form_data[${ugtpId}][swelling_32761][diff_m_m1][${selectionSeries}][${trial}]']`).val(diff_m_m1);
    //
    //                         m2 = Number($(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_air_after_saturation_soaking_water][${selectionSeries}][${trial}]']`).val());
    //                         m3 = Number($(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_water_after_saturation_soaking_water][${selectionSeries}][${trial}]']`).val());
    //
    //                         h = roundPlus(((((m5 - m6) - (m - m1)) / (m - m1)) * 100), 2);
    //                         $(`[name='form_data[${ugtpId}][swelling_32761][swelling][${selectionSeries}][${trial}]']`).val(h);
    //                     });
    //
    //                     h1 = Number($(`[name='form_data[${ugtpId}][swelling_32761][swelling][${series}][0]']`).val());
    //                     h2 = Number($(`[name='form_data[${ugtpId}][swelling_32761][swelling][${series}][1]']`).val());
    //                     h3 = Number($(`[name='form_data[${ugtpId}][swelling_32761][swelling][${series}][2]']`).val());
    //
    //                     swelling = roundPlus(((h1 + h2 + h3) / 3), 1);
    //                     $(`[name='form_data[${ugtpId}][swelling_32761][swelling_average][${series}]']`).val(swelling);
    //                 }
    //             }
    //             else {
    //
    //                 arrCheck.push(series);
    //                 arrUnique = [...new Set(arrCheck)];
    //
    //                 $($(`[name='form_data[${ugtpId}][swelling_32761][water_saturation][${series}][${trial}]']`).parent().css("border", "inherit"));
    //
    //                 arrUnique.forEach(function(selectionSeries) {
    //
    //                     $(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_air_trial][${selectionSeries}][${trial}]']`).val(""); // m5
    //                     $(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_water_trial][${selectionSeries}][${trial}]']`).val(""); // m6
    //
    //                     $(`[name='form_data[${ugtpId}][swelling_32761][diff_m5_m6][${selectionSeries}][${trial}]']`).val(""); // diff_m5_m6
    //
    //                     $(`[name='form_data[${ugtpId}][swelling_32761][diff_m_m1][${selectionSeries}][${trial}]']`).val(""); // diff_m_m1
    //
    //                     $(`[name='form_data[${ugtpId}][swelling_32761][swelling][${selectionSeries}][${trial}]']`).val(""); // h
    //
    //                     $(`[name='form_data[${ugtpId}][swelling_32761][swelling_average][${selectionSeries}]']`).val(""); // h average
    //                 });
    //             }
    //         });
    //
    //         return $(`[name='form_data[${ugtpId}][swelling_32761][swelling_average][${series}]']`).val()
    //     }
    //
    //     arrParam = [
    //         0, 1, 2
    //     ];
    //     arrSwel = [
    //         GetSelectionVariable(0, arrParam), GetSelectionVariable(1, arrParam), GetSelectionVariable(2, arrParam)
    //     ]
    //
    //
    //
    //     arrSwel.forEach(function(value) {
    //         if (value) {
    //             return $(`[name='form_data[${ugtpId}][swelling_32761][swelling_result]']`).val(value)
    //         }
    //     });
    //
    //     if (swel_1 || swel_2 || swel_3) {
    //         $(`[name='form_data[${ugtpId}][swelling_32761][swelling_average][${series}]']`).val()
    //     }
    //
    //     let arrSwellingParam = [];
    //     arrParam.forEach(function(trial) {
    //         arrSwellingParam.push($(`[name='form_data[${ugtpId}][swelling_32761][swelling][${selectSeries}][${trial}]']`).val());
    //     });
    //
    //     let diff_1 = roundPlus(Math.abs(arrSwellingParam[0] - arrSwellingParam[1]), 1);
    //     let diff_2 = roundPlus(Math.abs(arrSwellingParam[0] - arrSwellingParam[2]), 1);
    //     let diff_3 = roundPlus(Math.abs(arrSwellingParam[1] - arrSwellingParam[2]), 1);
    //
    //     if(diff_1 > 0.2) {
    //         alert('Разница между определениями набухания образцов 1-ого и 2-ого испытания в серии [' + (selectSeries + 1) + '] превышает 0.2 % и равна [' + diff_1 + ' %]. Необходимо повторить испытание');
    //         $(`[name='form_data[${ugtpId}][swelling_32761][swelling_average][${selectSeries}]']`).val("");
    //     }
    //     if(diff_2 > 0.2) {
    //         alert('Разница между определениями набухания образцов 1-ого и 3-ого испытания в серии [' + (selectSeries + 1) + '] превышает 0.2 % и равна [' + diff_2 + ' %]. Необходимо повторить испытание');
    //         $(`[name='form_data[${ugtpId}][swelling_32761][swelling_average][${selectSeries}]']`).val("");
    //     }
    //     if(diff_3 > 0.2) {
    //         alert('Разница между определениями набухания образцов 2-ого и 3-ого испытания в серии [' + (selectSeries + 1) + '] превышает 0.2 % и равна [' + diff_3 + ' %]. Необходимо повторить испытание');
    //         $(`[name='form_data[${ugtpId}][swelling_32761][swelling_average][${selectSeries}]']`).val("");
    //     }
    // }

    function GetSwelling() {
        let firstAvgAr = []
        let secondAvgAr = []
        let thirdAvgAr = []

        let firstAvgInput = $(`[name='form_data[${ugtpId}][swelling_32761][swelling_average][0]'`)
        let secondAvgInput = $(`[name='form_data[${ugtpId}][swelling_32761][swelling_average][1]'`)
        let thirdAvgInput = $(`[name='form_data[${ugtpId}][swelling_32761][swelling_average][2]'`)

        for(let i = 0; i < 3; i++) {
            let arrayAvg = []

            for(let j = 0; j < 3; j++) {
                let firstMass = $(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_air][${i}][${j}]']`).val()
                let secondMass = $(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_water][${i}][${j}]']`).val()
                let thirdMass = $(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_air_after_saturation_soaking_water][${i}][${j}]']`).val()
                let fourthMass = $(`[name='form_data[${ugtpId}][swelling_32761][m_suspended_water_after_saturation_soaking_water][${i}][${j}]']`).val()

                let resultInput = $(`[name='form_data[${ugtpId}][swelling_32761][swelling][${i}][${j}]']`)

                let result = ((thirdMass - fourthMass) - (firstMass - secondMass)) / (firstMass - secondMass) * 100

                arrayAvg.push(Number(result.toFixed(2)))

                resultInput.val(result.toFixed(2))
            }

            switch (i) {
                case 0:
                    firstAvgAr = arrayAvg
                    break;
                case 1:
                    secondAvgAr = arrayAvg
                    break;
                case 2:
                    thirdAvgAr = arrayAvg
                    break;
            }
        }

        let firstAvg = average(firstAvgAr).toFixed(2)
        let secondAvg = average(secondAvgAr).toFixed(2)
        let thirdAvg = average(thirdAvgAr).toFixed(2)

        firstAvgInput.val(firstAvg)
        secondAvgInput.val(secondAvg)
        thirdAvgInput.val(thirdAvg)
    }

    body.on("click", "#swelling_32761", function() {
        GetSwelling();
    });
})