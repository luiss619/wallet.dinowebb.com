import $ from 'jquery'
import select2 from 'select2'
import 'select2/dist/css/select2.min.css'
select2(window, $)

function initSelect2(modal) {
    const $modal = $(modal)
    $modal.find('select.js-select2').each(function () {
        if ($(this).data('select2')) $(this).select2('destroy')
        const hasBlank = !!this.querySelector('option[value=""]')
        $(this).select2({
            dropdownParent: $modal,
            width: '100%',
            allowClear: hasBlank,
            placeholder: hasBlank ? this.querySelector('option[value=""]').text : '',
        })
    })
}

document.addEventListener('show.bs.modal', e => {
    initSelect2(e.target)
})
