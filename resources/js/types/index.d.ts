export interface User {
    id: number;
    telegram_id: number;
    name: string;
    email: string | null;
    email_verified_at: string | null;
    created_at: string | null;
    updated_at: string | null;
    avatar: string | null;
    initials: string;
    color: string;
    canEdit: boolean;
}

export type MemberType = "default" | "offline" | "guest" | "pending";
export type MemberRole = "default" | "owner" | "editor";

export interface Profession {
    id: number;
    name: string;
    abbreviation: string;
    color: string;
}

export interface Member {
    id: number;
    room_id: number;
    user_id: number | null;
    type: MemberType;
    name: string;
    initials: string;
    color: string;
    status: boolean;
    count: number;
    started_at: string | null;
    ended_at: string | null;
    created_at: string | null;
    updated_at: string | null;
    profession: Profession | null;
    user: User | null;
}

export interface Room {
    id: number;
    user_id: number;
    type: "status" | "counter";
    title: string | null;
    code: string;
    show_professions: boolean;
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
        modes: {
            telegram: boolean;
            slack: boolean;
        };
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
