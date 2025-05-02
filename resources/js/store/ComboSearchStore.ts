
import { router } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
import { onMounted, ref } from 'vue';

export const comboSearch = defineStore('comboSearch', () => {

    const kab = ref<any>([]);
    const opd = ref<any>([]);
    const unit = ref<any>([]);
    const kabSelect = ref<any>([]);
    const unitSelect = ref<any>([]);
    const opdSelect = ref<any>([]);
    const unitSelectCombo = ref<any>([]);
    const opdSelectCombo = ref<any>([]);
    const opdSelectName = ref<string>('');
    const unitSelectName = ref<string>('');
    const kabSelectName = ref<string>('');
    const yearSelect = ref<string>('');
    const isEnableOpd = ref<boolean>(true);
    const isEnableUnit = ref<boolean>(true);
    const isChooseUnit = ref<boolean>(false);

    function setChooseUnit(value: boolean): void {
        isChooseUnit.value = value;
    };
    function setYear(value: string): void {
        yearSelect.value = value;
    };

    onMounted(() => {

        const listKab = localStorage.getItem("listKab");
        if (listKab != null) {
            kabSelect.value = JSON.parse(listKab);
        };

        const listOpd = localStorage.getItem("listOpd");
        if (listOpd != null) {
            opdSelect.value = JSON.parse(listOpd);
        };

        const listUnit = localStorage.getItem("listUnit");
        if (listUnit != null) {
            unitSelect.value = JSON.parse(listUnit);
        };
    })

    async function setOpds(value: { id_kab: string, nama_kab: string }): Promise<void> {
        if (value.nama_kab == null || value.id_kab == null) { return }
        let opds = [];
        opds = opdSelect.value.filter((opd: { id_kab: string }) => {
            return opd.id_kab == value.id_kab;
        }) as Array<any>;
        if (opds && opds.length == 1) {
            setOpdName(opds[0].nama_opd);
            localStorage.setItem("nama_opd", opds[0].nama_opd ?? '');
            setisEnableOpd(false);
            let units = [];
            units = unitSelect.value.filter((unit: { id_opd: string }) => {
                return unit.id_opd == opds[0].id_opd;
            });

            if (units && units.length == 1) {
                setunit({
                    id_unit: units[0].id_unit,
                    id_opd: opds[0].id_opd,
                    nama_unit: units[0].nama_unit
                })
            } else if (units && units.length > 1) {
                setUnitName('');
                setisEnableUnit(false);
                localStorage.removeItem("nama_unit");
                setunitSelectCombo(units.map((unit: any) => {
                    return { id: unit.id_unit, name: unit.nama_unit, val1: unit.id_opd };
                }));
            }
        } else if (opds && opds.length > 1) {
            setOpdSelectCombo(opds.map((opd: any) => {
                return { id: opd.id_opd, name: opd.nama_opd, val1: opd.id_kab };
            }));
            setisEnableOpd(true);
            setOpdName('');
        }
    }

    async function setUnits(value: any): Promise<void> {
        if (value.id_kab == null || value.id_opd == null || value.nama_opd == null) { return; }
        let units = [];

        units = unitSelect.value.filter((unit: any) => {
            return unit.id_opd == value.id_opd;
        }) as Array<any>;

        localStorage.setItem("listFrOpd", JSON.stringify(units));
        setOpdName(value.nama_opd);
        localStorage.setItem("nama_opd", value.nama_opd)

        if (units && units.length == 1) {
            setunit({
                id_unit: units[0].id_unit,
                id_opd: value.id_opd,
                nama_unit: units[0].nama_unit
            })
        } else if (units && units.length > 1) {
            setunitSelectCombo(units.map((unit: any) => {
                return { id: unit.id_unit, name: unit.nama_unit, val1: unit.id_opd };
            }));
            setisEnableUnit(true);
        }
    }

    function setKab(value: any): void {
        kabSelect.value = value;
    }

    function setunit(value: { id_unit: string; nama_unit: string; id_opd: string }): void {
        const idKabSelect: string | null = localStorage.getItem("kabSelect");
        if (idKabSelect !== null && value.id_unit && value.id_opd) {
                const idKab: { id: string } | null = JSON.parse(idKabSelect);
                router.post(route('setlan.setCookie', { type: 'unit' }), {
                    id_unit: value.id_unit,
                    id_opd: value.id_opd,
                    id_kab: idKab?.id
                }, {
                    preserveState: true,
                });
                localStorage.setItem("nama_unit", value.nama_unit ?? '');
                localStorage.setItem("isChooseUnit", "true");
                setUnitName(value.nama_unit);
                setChooseUnit(true);
            }
    }

    function setOpd(value: any[]): void {
        opdSelect.value = value;
    }

    function setUnit(value: any): void {
        unitSelect.value = value;
    }

    function setisEnableOpd(value: boolean): void {
        isEnableOpd.value = value;
    }
    function setisEnableUnit(value: boolean): void {
        isEnableUnit.value = value;
    }

    function setKabName(value: any): void {
        kabSelectName.value = value;
    }
    function setUnitName(value: any): void {
        unitSelectName.value = value;

    }
    function setOpdName(value: any): void {
        opdSelectName.value = value;
    }

    function setOpdSelectCombo(value: any): void {
        opdSelectCombo.value = value;
    }
    function setunitSelectCombo(value: any): void {
        unitSelectCombo.value = value;
    }

    return {
        kab,
        opd,
        unit,
        setKabName,
        setUnitName,
        unitSelect,
        kabSelect,
        opdSelect,
        setOpdName,
        setKab,
        setUnit,
        setOpd,
        opdSelectName,
        unitSelectName,
        kabSelectName,
        isEnableUnit,
        isEnableOpd,
        setisEnableOpd,
        setisEnableUnit,
        setOpds,
        setUnits,
        setunit,
        isChooseUnit,
        setChooseUnit,
        unitSelectCombo,
        opdSelectCombo,
        setOpdSelectCombo,
        setunitSelectCombo,
        setYear,
        yearSelect,
    }
});



