<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table/index';
import { Badge } from '@/components/ui/badge';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';

interface Permission {
    id: number;
    name: string;
    description: string | null;
}

interface Role {
    id: number;
    name: string;
    description: string | null;
    permissions: Permission[];
}

const props = defineProps<{ roles: Role[] }>();

const processing = ref(false);

const destroy = (id: number) => {
    if (confirm('Are you sure you want to delete this role?')) {
        processing.value = true;
        router.delete(`/admin/roles/${id}`, {
            onSuccess: () => {
                processing.value = false;
                toast.success('Role deleted successfully');
            },
            onError: () => {
                processing.value = false;
                toast.error('Failed to delete role');
            },
        });
    }
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Roles', href: '/admin/roles' },
];
</script>

<template>
    <Head title="Roles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Roles</h2>
                    <p class="text-sm text-muted-foreground">
                        Manage roles and their permissions
                    </p>
                </div>
                <Button as-child>
                    <Link :href="'/admin/roles/create'">
                        <Plus class="mr-2 h-4 w-4" />
                        Add Role
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Roles List</CardTitle>
                    <CardDescription>
                        A list of all roles in the system
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Permissions</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="role in roles" :key="role.id">
                                <TableCell class="font-medium">{{ role.name }}</TableCell>
                                <TableCell>{{ role.description || '-' }}</TableCell>
                                <TableCell>
                                    <div class="flex flex-wrap gap-1">
                                        <Badge v-for="permission in role.permissions" :key="permission.id" variant="secondary">
                                            {{ permission.name }}
                                        </Badge>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Button variant="outline" size="sm" as-child>
                                            <Link :href="`/admin/roles/${role.id}/edit`">
                                                <Pencil class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button 
                                            variant="outline" 
                                            size="sm" 
                                            :disabled="processing"
                                            @click="destroy(role.id)"
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