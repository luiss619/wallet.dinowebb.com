/**
 * AjaxTable — server-side pagination, search and sort via AJAX.
 *
 * Usage:  <div data-ajax-table
 *              data-ajax-table-url="/services"
 *              data-ajax-table-per-page="25">
 */

import Swal from 'sweetalert2'

class AjaxTable {
    constructor(el) {
        this.el       = el
        this.url      = el.dataset.ajaxTableUrl
        this.tbody    = el.querySelector('tbody')
        this.page     = 1
        this.perPage  = parseInt(el.dataset.ajaxTablePerPage ?? 25)
        this.search   = ''
        this.sort     = el.dataset.ajaxTableSort ?? ''
        this.dir      = el.dataset.ajaxTableDir  ?? 'asc'
        this.timer    = null
        this.filters  = {}
        this.init()
    }

    init() {
        this.setupSearch()
        this.setupSort()
        this.setupFilters()
        this.restoreFromUrl()
        this.fetch()
    }

    restoreFromUrl() {
        const params = new URLSearchParams(window.location.search)

        const search = this.el.querySelector('[data-table-search]')
        if (search && params.get('search')) {
            search.value = params.get('search')
            this.search  = params.get('search')
        }

        this.el.querySelectorAll('[data-table-filter]').forEach(input => {
            const key = input.dataset.tableFilter
            if (params.get(key)) {
                input.value       = params.get(key)
                this.filters[key] = params.get(key)
            }
        })

        if (params.get('page')) this.page = parseInt(params.get('page'))
    }

    setupSearch() {
        const input = this.el.querySelector('[data-table-search]')
        if (!input) return
        input.addEventListener('input', () => {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
                this.search = input.value.trim()
                this.page   = 1
                this.fetch()
            }, 400)
        })
    }

    setupSort() {
        this.el.querySelectorAll('th[data-table-sort]').forEach(th => {
            th.style.cursor = 'pointer'
            const icon = document.createElement('i')
            const col  = th.dataset.tableSort
            icon.className = (this.sort === col)
                ? (this.dir === 'asc' ? 'ti ti-arrow-up fs-xs ms-1' : 'ti ti-arrow-down fs-xs ms-1')
                : 'ti ti-arrows-sort fs-xs ms-1'
            th.appendChild(icon)

            th.addEventListener('click', () => {
                const col = th.dataset.tableSort
                if (this.sort === col) {
                    this.dir = this.dir === 'asc' ? 'desc' : 'asc'
                } else {
                    this.sort = col
                    this.dir  = 'asc'
                }
                // reset all icons
                this.el.querySelectorAll('th[data-table-sort] i').forEach(i => {
                    i.className = 'ti ti-arrows-sort fs-xs ms-1'
                })
                const icon = th.querySelector('i')
                if (icon) icon.className = this.dir === 'asc'
                    ? 'ti ti-arrow-up fs-xs ms-1'
                    : 'ti ti-arrow-down fs-xs ms-1'

                this.page = 1
                this.fetch()
            })
        })
    }

    setupFilters() {
        this.el.querySelectorAll('[data-table-filter]').forEach(input => {
            input.addEventListener('change', () => {
                const key = input.dataset.tableFilter
                this.filters[key] = input.value.trim()
                this.page = 1
                this.fetch()
            })
        })
    }

    async fetch() {
        this.setLoading(true)
        const params = new URLSearchParams({ page: this.page, per_page: this.perPage })
        if (this.search) params.set('search', this.search)
        if (this.sort)   { params.set('sort', this.sort); params.set('dir', this.dir) }
        Object.entries(this.filters).forEach(([k, v]) => { if (v) params.set(k, v) })

        this.syncUrl(params)

        try {
            const res  = await fetch(`${this.url}?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            })
            const data = await res.json()
            this.tbody.innerHTML = data.rows
            this.renderPagination(data.pagination)
            this.renderInfo(data.pagination)
        } catch (e) {
            console.error('AjaxTable fetch error', e)
        } finally {
            this.setLoading(false)
        }
    }

    syncUrl(params) {
        const url = new URL(window.location.href)
        url.search = params.toString()
        history.replaceState(null, '', url)
    }

    setLoading(on) {
        this.tbody.style.opacity       = on ? '0.4' : '1'
        this.tbody.style.pointerEvents = on ? 'none' : ''
    }

    renderPagination(meta) {
        const el = this.el.querySelector('[data-table-pagination]')
        if (!el) return
        if (!meta || meta.last_page <= 1) { el.innerHTML = ''; return }

        const ul = document.createElement('ul')
        ul.className = 'pagination pagination-sm pagination-boxed mb-0 justify-content-center'

        const li = (html, page, disabled, active) => {
            const item = document.createElement('li')
            item.className = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`
            item.innerHTML = `<a href="#" class="page-link">${html}</a>`
            if (!disabled) item.addEventListener('click', e => { e.preventDefault(); this.page = page; this.fetch() })
            return item
        }

        ul.appendChild(li('<i class="ti ti-chevron-left"></i>', meta.current_page - 1, meta.current_page === 1))

        let s = Math.max(1, meta.current_page - 2)
        let e = Math.min(meta.last_page, s + 4)
        if (e - s < 4) s = Math.max(1, e - 4)
        for (let i = s; i <= e; i++) ul.appendChild(li(i, i, false, i === meta.current_page))

        ul.appendChild(li('<i class="ti ti-chevron-right"></i>', meta.current_page + 1, meta.current_page === meta.last_page))

        el.innerHTML = ''
        el.appendChild(ul)
    }

    renderInfo(meta) {
        const el = this.el.querySelector('[data-table-pagination-info]')
        if (!el) return
        if (!meta || !meta.from) { el.innerHTML = ''; return }
        const label = el.getAttribute('data-table-pagination-info') || 'entries'
        el.innerHTML = `Showing <span class="fw-semibold">${meta.from}</span> to <span class="fw-semibold">${meta.to}</span> of <span class="fw-semibold">${meta.total}</span> ${label}`
    }
}

// ── Edit modal: click [data-edit-url] → fetch JSON → fill form → show modal ──
document.addEventListener('click', async e => {
    const btn = e.target.closest('[data-edit-url]')
    if (!btn) return

    try {
        const res  = await fetch(btn.dataset.editUrl, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        })
        const data = await res.json()

        const modal = document.getElementById('modalEdit')
        if (!modal) return
        const form = modal.querySelector('form')
        if (!form) return

        form.action = btn.dataset.updateUrl

        for (const [key, value] of Object.entries(data)) {
            const field = form.querySelector(`[name="${key}"]`)
            if (field) {
                field.value = value ?? ''
                field.dispatchEvent(new Event('change'))
            }
        }

        bootstrap.Modal.getOrCreateInstance(modal).show()
    } catch (e) {
        console.error('Edit modal error', e)
    }
})

// ── Delete confirmation ──────────────────────────────────────────────────────
document.addEventListener('click', e => {
    const btn = e.target.closest('[data-delete-form] button')
    if (!btn) return
    const form = btn.closest('[data-delete-form]')
    if (!form) return

    e.preventDefault()
    Swal.fire({
        title: '¿Eliminar?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#e3404a',
        reverseButtons: true,
    }).then(result => {
        if (result.isConfirmed) form.submit()
    })
})

// ── Flash toast ──────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-ajax-table]').forEach(el => new AjaxTable(el))

    const flash = document.getElementById('flash-message')
    if (!flash) return
    Swal.fire({
        icon: flash.dataset.type ?? 'success',
        title: flash.dataset.message,
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    })
})
