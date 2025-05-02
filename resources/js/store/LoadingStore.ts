import { defineStore } from 'pinia';
import { ref } from 'vue';

export const loadingStore = defineStore('loadingStore', () => {

    const adminLocal = localStorage.getItem('isAdmin')
    const loading = ref<boolean>(false);
    const isAdmin = ref<boolean>(adminLocal ? adminLocal=='true' : false );

    function setLoading(value: boolean): void {
        loading.value = value;
    }
    function setAdmin(value: boolean): void {
        isAdmin.value = value;
    }

    return {
        loading,
        setLoading,
        setAdmin,
        isAdmin
    }
});



