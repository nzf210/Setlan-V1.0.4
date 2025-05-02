import { useForm } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
import { ref } from 'vue';
import Swal from "sweetalert2";


export const mutasiStore = defineStore('mutasiStore', () => {

    const listMutasi = ref<{}>([]);

    function addListMutasi(value: any): void {
        // const formMutasiDraft = useForm({
        //     id_barang: value.id_barang,
        // });
        // formMutasiDraft.post(route('mutasi.draft'), {
        //     onSuccess: page => {
        //         Swal.fire({
        //             toast: true,
        //             icon: "success",
        //             position: "top-end",
        //             showConfirmButton: false,
        //             title: page.props.flash.success,
        //         });
        //     }
        // })
    }

    return {
        listMutasi,
        addListMutasi
    }
});



