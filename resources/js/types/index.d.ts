interface Role {
    name: string;
}

export interface User {
    id: number;
    name: string;
    username: string;
    phone: string;
}

export interface Message {
    value: any;
    error: string;
    info: string;
    success: string;
    warning: string;
    opd: Array<{ id_opd: string; nama_opd: string }>;
    unit: Array<{ id_unit: string; nama_unit: string }>
    kab: Array<{ id_kab: string; nama_kab: string }>
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
