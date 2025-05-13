import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

export const useMenuStore = defineStore('menuStore', () => {
    // State
    const tahun = ref('');
    const namaKabupaten = ref('');
    const namaUnit = ref('');
    const namaOpd = ref('');
    const namaUser = ref('');

    // Load state from localStorage on store initialization
    const loadFromLocalStorage = () => {
        const savedState = localStorage.getItem('menuStoreState');
        if (savedState) {
            const parsedState = JSON.parse(savedState);
            tahun.value = parsedState.tahun ?? '';
            namaKabupaten.value = parsedState.namaKabupaten ?? '';
            namaUnit.value = parsedState.namaUnit ?? '';
            namaOpd.value = parsedState.namaOpd ?? '';
            namaUser.value = parsedState.namaUser ?? '';
        }
    };

    // Save state to localStorage whenever state changes
    const saveToLocalStorage = () => {
        const stateToSave = {
            tahun: tahun.value,
            namaKabupaten: namaKabupaten.value,
            namaUnit: namaUnit.value,
            namaOpd: namaOpd.value,
            namaUser: namaUser.value,
        };
        localStorage.setItem('menuStoreState', JSON.stringify(stateToSave));
    };

    // Actions (Methods)
    function setTahun(newTahun: string) {
        tahun.value = newTahun;
    }

    function setNamaUser(newUser: string) {
        namaUser.value = newUser;
    }

    function setNamaKabupaten(newNamaKabupaten: string) {
        namaKabupaten.value = newNamaKabupaten;

    }

    function setNamaUnit(newNamaUnit: string) {
        namaUnit.value = newNamaUnit;

    }

    function setNamaOpd(newNamaOpd: string) {
        namaOpd.value = newNamaOpd;

    }

    function resetState() {
        tahun.value = '';
        namaKabupaten.value = '';
        namaUnit.value = '';
        namaOpd.value = '';
        namaUser.value = '';
        localStorage.removeItem('menuStoreState'); // Hapus data dari localStorage saat reset
    }

    // Load state from localStorage when store is initialized
    loadFromLocalStorage();

    // Watch for changes in state and save to localStorage
    watch(
        [tahun, namaKabupaten, namaUnit, namaOpd, namaUser],
        () => {
            saveToLocalStorage();
        },
        { deep: true }
    );

    return {
        tahun,
        namaKabupaten,
        namaUnit,
        namaOpd,
        namaUser,
        setTahun,
        setNamaUser,
        setNamaKabupaten,
        setNamaUnit,
        setNamaOpd,
        resetState,
    };
});
