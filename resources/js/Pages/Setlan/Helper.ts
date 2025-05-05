import moment from 'moment';
import { customRef } from 'vue';

export const dateFormat = (value: string) => {
    if (value) {
        return moment(String(value)).format('DD-MMM-YYYY')
    }
}

export function getCookie(id: string) {
    const re = new RegExp('(^|;)\\s*' + id + '=([^;]*)');
    const match = re.exec(document.cookie);
    return match ? decodeURIComponent(match[2]) : null;
}

export const currencyFormat = (value: number) => {
    const [integerPart, decimalPart] = value.toString().split('.');
    const formattedIntegerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    if (decimalPart) {
        const formattedDecimalPart = decimalPart.length > 2 ? decimalPart.slice(0, 2) : decimalPart;
        return `Rp ${formattedIntegerPart},${formattedDecimalPart}`;
    } else {
        return `Rp ${formattedIntegerPart}`;
    }
};

export function transformObject(obj:Object) {
  if (obj && Object.keys(obj).length === 0 || typeof obj !== 'object' || obj === null) {
    return [];
  }

  return Object.entries(obj).map(([key, value]) => ({ key, value }));
}


export function totalSums(arr: any, index: number): number {
  const data = transformObject(arr.data);
  data.pop();

  const total = data.reduce((sum, item) => sum + item.value.total, 0);
  return total;
}
export const rupiah = (number: number) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "Rp "
    }).format(number);
}

export const safeParseFloat = (value: any) => parseFloat(value) || 0;

export function Capitalized(name: string): string {
    const arrayName = name.split(' ');
    const capitalizedArray = arrayName.map((element) => {
        const nm = element.toLowerCase();
        const capitalizedFirst = nm[0].toUpperCase();
        const rest = nm.slice(1);
        return capitalizedFirst + rest;
    });
    return capitalizedArray.join(' ');
}

export const menu = {
    dashboard: 'setlan.dashboard',
    barang: 'setlan.barang',
    tabBrg: {
        master: 'setlan.barang.master',
        masuk: 'setlan.barang.masuk',
        keluar: 'setlan.barang.keluar',
    },
    akuntansi: 'setlan.akuntansi',
    tabAkun: {
        akun: 'setlan.akuntansi.akun',
        kebijakan: 'setlan.akuntansi.kebijakan',
    }
}

export function useDebouncedRef(value: string, delay = 700) {
  let timeout:any;
  return customRef((track, trigger) => {
    return {
    get() {
        track()
        return value
    },
    set(newValue) {
        clearTimeout(timeout)
        timeout = setTimeout(() => {
        value = newValue
        trigger()
        }, delay)
    }
    }
})
}

/**
   *
   * @param options array of object
   * @returns
   * Menghapus isi Array yang duplicated
   */
export const mergeAndRemoveDuplicates = (array1:Array<any>, array2:Array<any>) => {
    const map = new Map();
    [...array1, ...array2].forEach((item:any) => {
      map.set(item.id, item);
    });
    return Array.from(map.values());
  };

  /**
   *
   * @param options array of object
   * @returns array
   * This function will return nested array
   */
export const constructNestedArray = (options:any) => {
    const nestedArray:any = [];
    options.forEach((option:any) => {
      const nestedSet = new Set();
      nestedSet.add(option.id);
      nestedArray.push([...nestedSet]);
    });
    return nestedArray;
  };

  export interface Unit {
    id_unit: string;
    id_opd: string;
    nama_unit: string;
  }

  export interface Opd {
    id_opd: string;
    nama_opd: string;
  }

  export interface Kabupaten {
    id_kab: string;
    nama_kab: string;
  }

  export interface KodeBarang {
    id_kd_barang: string;
    nama: string;
  }

  export interface Satuan {
    id: number;
    nama: string;
  }

  export interface Akun {
    id_akun: string;
    nama: string;
  }

  export interface Barang {
    id_barang: string;
    nama_barang: string;
    merek: string | null;
    type: string | null;
    tahun_buat: string | null;
    tahun_beli: string | null;
    harga: string;
    id_kd_barang: number | null;
    satuan_id: number | null;
    created_by: number | null;
    updated_by: number | null;
    deleted_by: number | null;
    created_at: string | null;
    updated_at: string | null;
    id_akun: number | null;
    kode_barang: KodeBarang; // Mungkin null tergantung relasi
    satuan: Satuan; // Mungkin null tergantung relasi
    akun: Akun; // Mungkin null tergantung relasi
  }

  export interface SubKegiatanDetail  {
    id: number | string;
    id_unit: string | null;
    id_opd: string | null;
    id_kab: string | null;
    id_subkeg: string;
    nama_keg: string | null;
    nama: string | null;
  }

  export interface UnitSubKeg {
    id: number;
    id_kab: string;
    id_opd: string;
    id_unit: string;
    id_subkeg: string;
    tahun: number;
    sub_kegiatan?: SubKegiatanDetail; // Mungkin null tergantung relasi
  }

  export interface Mutasi {
    id: number;
    id_barang: string;
    id_subkeg: number;
    id_kab: string;
    id_opd: string;
    id_unit: string;
    jumlah: number;
    pajak: number;
    penyesuaian: number;
    harga: number;
    tot_harga: number;
    type: string;
    tgl_beli: string | null;
    tgl_expired: string | null;
    tahun_buat: string | null;
    tahun: number;
    created_by: number | null;
    updated_by: number | null;
    deleted_by: string | null;
    created_at: string | null;
    updated_at: string | null;
    unit?: Unit; // Mungkin null tergantung relasi
    opd?: Opd; // Mungkin null tergantung relasi
    kabupaten?: Kabupaten; // Mungkin null tergantung relasi
    barang?: Barang; // Mungkin null tergantung relasi
    unit_sub_keg?: UnitSubKeg; // Mungkin null tergantung relasi
    subkeg?: UnitSubKeg; // Mungkin null tergantung relasi
  }
  export interface UnitSubKeg {
    id: number;
    id_subkeg: string;
    id_kab: string;
    id_opd: string;
    id_unit: string;
    tahun: number;
    sub_kegiatan?: SubKegiatanDetail; // Mungkin null tergantung relasi
}
  export interface PaginationLinks {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
  }

  export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
  }

  export interface PaginationMeta {
    current_page: number;
    from: number | null;
    last_page: number;
    links: PaginationLink[];
    path: string;
    per_page: number;
    to: number | null;
    total: number;
  }

  export interface PaginatedMutasiResponse {
    data: Mutasi[];
    links: PaginationLinks;
    meta: PaginationMeta;
  }

  export interface PaginationLinks {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
  }

  export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
  }

  export interface PaginationMeta {
    current_page: number;
    from: number | null;
    last_page: number;
    links: PaginationLink[];
    path: string;
    per_page: number;
    to: number | null;
    total: number;
  }
 export interface PaginatedMutasiResponse {
    current_page: number;
    data: Mutasi[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLinks;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
    meta: PaginationMeta;
  }

