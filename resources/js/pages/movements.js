import $ from 'jquery'
import select2 from 'select2'
import 'select2/dist/css/select2.min.css'
select2(window, $)

const initSelect2 = (modal) => {
    const $modal = $(modal)
    $modal.find('select[name="account_id"], select[name="service_id"]').each(function () {
        if ($(this).data('select2')) $(this).select2('destroy')
        $(this).select2({
            dropdownParent: $modal,
            width: '100%',
            allowClear: true,
            placeholder: $(this).find('option:first').text(),
        })
    })
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('modalCreate')?.addEventListener('show.bs.modal', function () {
        initSelect2(this)
    })

    document.getElementById('modalEdit')?.addEventListener('show.bs.modal', function () {
        initSelect2(this)
    })
})
