export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Member {
    name: string;
    status: boolean;
}

export interface Room {
    id: number;
    user_id: number;
    code: string;
    members: Member[];
    created_at: string;
    updated_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};
