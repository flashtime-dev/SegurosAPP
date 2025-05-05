import { UserCard } from "@/components/user-card";
import AppLayout from "@/layouts/app-layout";
import { User } from "@/types";
import { Head, usePage } from "@inertiajs/react";

export default function Index() {
    const { props } = usePage<{ users: User[] }>();
    const users = props.users;
    return (
        <AppLayout breadcrumbs={[{ title: 'Usuarios', href: '/usuarios' }]}>
            <Head title="Usuarios" />

            <div className="container mx-auto px-4 py-6">
                <h1 className="text-2xl font-bold mb-6">Usuarios</h1>

                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {users.map((usuario) => (
                        <UserCard key={usuario.id} usuario={usuario} />
                    ))}
                </div>

            </div>
        </AppLayout>
    );
}