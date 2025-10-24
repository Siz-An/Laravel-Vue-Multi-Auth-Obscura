<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import DeleteConfirmationModal from '@/components/DeleteConfirmationModal.vue';

interface Permission {
    id: number;
    name: string;
    description: string | null;
}

const props = defineProps<{ permission: Permission }>();

const showDeleteModal = ref(false);
const processing = ref(false);

const confirmDelete = () => {
    showDeleteModal.value = true;
};

const handleDelete = () => {
    processing.value = true;
    router.delete(`/admin/permissions/${props.permission.id}`, {
        onSuccess: () => {
            processing.value = false;
            showDeleteModal.value = false;
            toast.success('Permission deleted successfully');
        },
        onError: () => {
            processing.value = false;
            showDeleteModal.value = false;
            toast.error('Failed to delete permission');
        },
    });
};

const handleCancel = () => {
    showDeleteModal.value = false;
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Permissions', href: '/admin/permissions' },
    { title: 'Delete Permission', href: `/admin/permissions/${props.permission.id}/delete` },
];
</script>

<template>
    <Head title="Delete Permission" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Delete Permission</h2>
                    <p class="text-sm text-muted-foreground">
                        Permanently remove this permission
                    </p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Delete Permission</CardTitle>
                    <CardDescription>
                        This action cannot be undone. This will permanently delete the permission "{{ props.permission.name }}".
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex items-center gap-2">
                        <Button variant="destructive" @click="confirmDelete">
                            Delete Permission
                        </Button>
                        <Button variant="outline" @click="$inertia.visit('/admin/permissions')">
                            Cancel
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>

    <DeleteConfirmationModal
        :open="showDeleteModal"
        title="Delete Permission"
        :description="`Are you sure you want to delete the permission '${props.permission.name}'? This action cannot be undone.`"
        confirm-text="Delete Permission"
        :processing="processing"
        @confirm="handleDelete"
        @cancel="handleCancel"
    />
</template>