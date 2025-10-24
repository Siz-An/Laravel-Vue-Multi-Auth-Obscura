<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import DeleteConfirmationModal from '@/components/DeleteConfirmationModal.vue';

interface Role {
    id: number;
    name: string;
    description: string | null;
}

const props = defineProps<{ role: Role }>();

const showDeleteModal = ref(false);
const processing = ref(false);

const confirmDelete = () => {
    showDeleteModal.value = true;
};

const handleDelete = () => {
    processing.value = true;
    router.delete(`/admin/roles/${props.role.id}`, {
        onSuccess: () => {
            processing.value = false;
            showDeleteModal.value = false;
            toast.success('Role deleted successfully');
        },
        onError: () => {
            processing.value = false;
            showDeleteModal.value = false;
            toast.error('Failed to delete role');
        },
    });
};

const handleCancel = () => {
    showDeleteModal.value = false;
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Roles', href: '/admin/roles' },
    { title: 'Delete Role', href: `/admin/roles/${props.role.id}/delete` },
];
</script>

<template>
    <Head title="Delete Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Delete Role</h2>
                    <p class="text-sm text-muted-foreground">
                        Permanently remove this role
                    </p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Delete Role</CardTitle>
                    <CardDescription>
                        This action cannot be undone. This will permanently delete the role "{{ props.role.name }}".
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex items-center gap-2">
                        <Button variant="destructive" @click="confirmDelete">
                            Delete Role
                        </Button>
                        <Button variant="outline" @click="$inertia.visit('/admin/roles')">
                            Cancel
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>

    <DeleteConfirmationModal
        :open="showDeleteModal"
        title="Delete Role"
        :description="`Are you sure you want to delete the role '${props.role.name}'? This action cannot be undone.`"
        confirm-text="Delete Role"
        :processing="processing"
        @confirm="handleDelete"
        @cancel="handleCancel"
    />
</template>