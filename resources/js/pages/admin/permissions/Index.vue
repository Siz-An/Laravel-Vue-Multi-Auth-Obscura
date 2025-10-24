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

interface Permission {
    id: number;
    name: string;
    description: string | null;
}

const props = defineProps<{ permissions: Permission[] }>();

const processing = ref(false);

const destroy = (id: number) => {
    if (confirm('Are you sure you want to delete this permission?')) {
        processing.value = true;
        router.delete(`/admin/permissions/${id}`, {
            onSuccess: () => {
                processing.value = false;
                toast.success('Permission deleted successfully');
            },
            onError: () => {
                processing.value = false;
                toast.error('Failed to delete permission');
            },
        });
    }
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Permissions', href: '/admin/permissions' },
];
</script>

<template>
    <Head title="Permissions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Permissions</h2>
                    <p class="text-sm text-muted-foreground">
                        Manage permissions and their roles
                    </p>
                </div>
                <Button as-child>
                    <Link :href="'/admin/permissions/create'">
                        <Plus class="mr-2 h-4 w-4" />
                        Add Permission
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Permissions List</CardTitle>
                    <CardDescription>
                        A list of all permissions in the system
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="permission in permissions" :key="permission.id">
                                <TableCell class="font-medium">{{ permission.name }}</TableCell>
                                <TableCell>{{ permission.description || '-' }}</TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Button variant="outline" size="sm" as-child>
                                            <Link :href="`/admin/permissions/${permission.id}/edit`">
                                                <Pencil class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button 
                                            variant="outline" 
                                            size="sm" 
                                            :disabled="processing"
                                            @click="destroy(permission.id)"
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