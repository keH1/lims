$(function ($) {
    const body = $('body')

    let ugtpId = $('#pwd_zern').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    // Зерновой состав
    function GetGrainComposition32761() {

        function calcA(m_i, m, size, trial) {

            a = roundPlus(((m_i / m )* 100), 2);

            if(size == "Мельче 2.0") {
                prefix = "_2_0";
            }
            if(size == "Мельче 0.125") {
                prefix = "_0_125";
            }
            if(size == "Мельче 0.063") {
                prefix = "_0_063";
            }
            if(size == "Мельче") {
                prefix = "_0_low";
            }

            $(`[name='form_data[${ugtpId}][grain_composition_32761][m_partial_residue${prefix}][${trial}]']`).val(a);

            return a;
        }

        m_2_0_i = calcA($(`[name='form_data[${ugtpId}][grain_composition_32761][m_measured_sample_2_0][0]']`).val(), $(`[name='form_data[${ugtpId}][grain_composition_32761][m_residue][0]']`).val(), "Мельче 2.0", 0);
        m_0_125_i = calcA($(`[name='form_data[${ugtpId}][grain_composition_32761][m_measured_sample_0_125][0]']`).val(), $(`[name='form_data[${ugtpId}][grain_composition_32761][m_residue][0]']`).val(), "Мельче 0.125", 0);
        m_0_063_i = calcA($(`[name='form_data[${ugtpId}][grain_composition_32761][m_measured_sample_0_063][0]']`).val(), $(`[name='form_data[${ugtpId}][grain_composition_32761][m_residue][0]']`).val(), "Мельче 0.063", 0);
        m_0_low_i = calcA($(`[name='form_data[${ugtpId}][grain_composition_32761][m_measured_sample_0_low][0]']`).val(), $(`[name='form_data[${ugtpId}][grain_composition_32761][m_residue][0]']`).val(), "Мельче", 0);

        m_2_0 = roundPlus(m_2_0_i, 2);

        mc_2_0 = roundPlus((100 - m_2_0), 2);
        $(`[name='form_data[${ugtpId}][grain_composition_32761][dust_particle_content_2_0]']`).val(mc_2_0);

        mc_0_125 = roundPlus((mc_2_0 - m_0_125_i), 2); // второе сито
        $(`[name='form_data[${ugtpId}][grain_composition_32761][dust_particle_content_0_125]']`).val(mc_0_125);

        mc_0_063 = roundPlus((mc_0_125 - m_0_063_i), 2); // третье сито
        $(`[name='form_data[${ugtpId}][grain_composition_32761][dust_particle_content_0_063]']`).val(mc_0_063);

        mc_0_low = roundPlus((mc_0_063 - m_0_063_i), 2); // четвертое сито
        $(`[name='form_data[${ugtpId}][grain_composition_32761][dust_particle_content_0_low]']`).val(mc_0_063);
    }

    body.on("click", "#grain_32761", function() {
        GetGrainComposition32761();
    });
})