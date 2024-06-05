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
    avatar: string | null;
    initials: string;
    full_name: string;
    color: string;
}

export interface Member {
    id: number;
    room_id: number;
    user_id: number | null;
    name: string;
    status: boolean;
    count: number;
    started_at: string | null;
    ended_at: string | null;
    offline: boolean;
    created_at: string | null;
    updated_at: string | null;
    user: User | null;
}

export interface Room {
    id: number;
    user_id: number;
    type: "status" | "counter";
    title: string | null;
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
        botUsername: string;
    };
    app: {
        name: string;
        version: string;
        isLocal: boolean;
    };
    developer: {
        name: string;
        github: string;
    };
};
