<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
    email: string;
    is_super_admin: boolean;
    roles: {
        id: number;
        name: string;
    }[];
}

const props = defineProps<{ users: User[] }>();

const processing = ref(false);

const destroy = (id: number) => {
    if (confirm('Are you sure you want to delete this user?')) {
        processing.value = true;
        router.delete(`/admin/users/${id}`, {
            onSuccess: () => {
                processing.value = false;
                toast.success('User deleted successfully');
            },
            onError: () => {
                processing.value = false;
                toast.error('Failed to delete user');
            },
        });
    }
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Users', href: '/admin/users' },
];
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Users</h2>
                    <p class="text-sm text-muted-foreground">
                        Manage users and their roles
                    </p>
                </div>
                <Button as-child>
                    <Link :href="'/admin/users/create'">
                        <Plus class="mr-2 h-4 w-4" />
                        Add User
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Users List</CardTitle>
                    <CardDescription>
                        A list of all users in the system
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Roles</TableHead>
                                <TableHead>Super Admin</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="user in users" :key="user.id">
                                <TableCell class="font-medium">{{ user.name }}</TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                                <TableCell>
                                    <div class="flex flex-wrap gap-1">
                                        <Badge v-for="role in user.roles" :key="role.id" variant="secondary">
                                            {{ role.name }}
                                        </Badge>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <Badge v-if="user.is_super_admin" variant="default">Yes</Badge>
                                    <Badge v-else variant="outline">No</Badge>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Button variant="outline" size="sm" as-child>
                                            <Link :href="`/admin/users/${user.id}/edit`">
                                                <Pencil class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button 
                                            v-if="!user.is_super_admin || user.id !== $page.props.auth.user.id"
                                            variant="outline" 
                                            size="sm" 
                                            :disabled="processing"
                                            @click="destroy(user.id)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>