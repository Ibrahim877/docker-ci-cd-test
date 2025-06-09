import Swal from 'sweetalert2'
import moment from 'moment'

export const useSwal = Swal
export const useModal = (modalId, action = true) => {
    const el = document.getElementById(modalId)
    if (el) {
        if (action) {
            const modal = new bootstrap.Modal(el)
            modal.show()
        } else {
            const modal = bootstrap.Modal.getInstance(el)
            modal.hide()
        }
    }
}
export const useFileUrl = path => useRuntimeConfig().public.backendUrl + '/storage/' + path
export const useDateFormat = (date, withTime = false) => {
    let format = 'DD-MM-YYYY'
    if (withTime) {
        format += ' HH:mm'
    }
    return moment(date).format(format)
}
export const useTableIndex = (index = 0, {page = 1, perPage = 10}) => perPage * (page - 1) + parseInt(index) + 1
