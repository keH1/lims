$(function ($) {
    const body = $('body')

    let ugtpId = $('#pbc_ugtp').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    // Битумоемкость
    function GetBitumenCapacity() {

        function calcPB(p, trial) {

            m = +$(`[name='form_data[${ugtpId}][bitumen_capacity_32761][m_measured_sample][${trial}]']`).val();
            m1 = +$(`[name='form_data[${ugtpId}][bitumen_capacity_32761][m_remaining_after_test][${trial}]']`).val();

            let sign;
            if(m < m1) {
                alert("Масса отвешенной мерной пробы [меньше] массы оставшегося после испытания минерального порошка в испытании [" + (trial + 1) +"]. Проверьте введенные данные.");
                sign = 0;
            }
            if((m - m1) == 0) {
                alert("Масса отвешенной мерной пробы [равна] массе оставшегося после испытания минерального порошка в испытании [" + (trial + 1) +"]. Проверьте введенные данные.");
                sign = 0;
            }
            if(sign == 0) {
                return 0;
            }

            pb = Math.round(((15 * p) / (m - m1)) * 100);
            return pb;
        }

        p_t_d = $("td.true_density input").val();

        pb_1 = calcPB(p_t_d, 0);
        pb_2 = calcPB(p_t_d, 1);

        if(pb_1 == 0 || pb_2 == 0) {
            return false;
        }

        PB_ariphmetic = Math.round((pb_1 + pb_2) / 2);
        $("td.capacity input").val(PB_ariphmetic);

        let PB_difference = Math.abs(pb_1 - pb_2);
        if(PB_difference > 2) {
            alert('Разница между определениями битумоемкости минерального порошка превышает 2 г и равна ' + PB_difference + " г. Необходимо повторить испытание");
            $("td.capacity input").val("");
        }
    }

    body.on("click", "#bitumen_capacity", function() {
        GetBitumenCapacity();
    });
})