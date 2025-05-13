interface Role {
    name: string;
}

export interface User {
    id: number;
    name: string;
    username: string;
    phone: string;
    roles: { name: string }[];
}



export interface Message {
    value: any;
    error: string;
    info: string;
    success: string;
    warning: string;
    opd: Array<{ id_opd: string; nama_opd: string }>;
    unit: Array<{ id_unit: string; nama_unit: string }>
    kabupaten: Array<{ id_kabupaten: string; nama_kabupaten: string }>
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
        role: string;
    };
    flash: Message,
    cookies: boolean
};

export interface Props {
    flash: Message
}

export interface Page {
    props: Props
}


export interface Can {
    create: boolean;
    edit: boolean;
    delete: boolean;
}

export interface Kegiatan {
    id_kegiatan: number;
    kode_kegiatan: string;
    nama_kegiatan: string;
    tahun: number;
}

export interface PaginationFilters {
    search?: string;
    tahun?: string;
    per_page?: number;
}

export interface Tahun {
    id_tahun: number;
    tahun: number;
    tahun_akun: number;
    tahun_kode_barang: number;
    tahun_sub_kegiatan: number;
    tahun_kegiatan: number;
}
