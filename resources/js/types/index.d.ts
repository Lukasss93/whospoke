export interface User {
    id: number;
    telegram_id: number;
    username: string | null;
    first_name: string;
    last_name: string | null;
    email: string | null;
    email_verified_at: string | null;
    created_at: string | null;
    updated_at: string | null;
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
    started_at: string | null;
    ended_at: string | null;
    created_at: string | null;
    updated_at: string | null;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    app: {
        name: string;
        version: string;
    };
    developer: {
        name: string;
        github: string;
    };
};
