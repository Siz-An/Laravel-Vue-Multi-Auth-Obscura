<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import DeleteConfirmationModal from '@/components/DeleteConfirmationModal.vue';

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

const props = defineProps<{ user: User }>();

const showDeleteModal = ref(false);
const processing = ref(false);

const confirmDelete = () => {
    showDeleteModal.value = true;
};

const handleDelete = () => {
    processing.value = true;
    router.delete(`/admin/users/${props.user.id}`, {
        onSuccess: () => {
            processing.value = false;
            showDeleteModal.value = false;
            toast.success('User deleted successfully');
        },
        onError: () => {
            processing.value = false;
            showDeleteModal.value = false;
            toast.error('Failed to delete user');
        },
    });
};

const handleCancel = () => {
    showDeleteModal.value = false;
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Users', href: '/admin/users' },
    { title: 'Delete User', href: `/admin/users/${props.user.id}/delete` },
];
</script>

<template>
    <Head title="Delete User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Delete User</h2>
                    <p class="text-sm text-muted-foreground">
                        Permanently remove this user
                    </p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Delete User</CardTitle>
                    <CardDescription>
                        This action cannot be undone. This will permanently delete the user "{{ props.user.name }}".
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex items-center gap-2">
                        <Button 
                            variant="destructive" 
                            @click="confirmDelete"
                            :disabled="props.user.is_super_admin && props.user.id === $page.props.auth.user.id"
                        >
                            Delete User
                        </Button>
                        <Button variant="outline" @click="$inertia.visit('/admin/users')">
                            Cancel
                        </Button>
                    </div>
                    <p 
                        v-if="props.user.is_super_admin && props.user.id === $page.props.auth.user.id" 
                        class="mt-2 text-sm text-muted-foreground"
                    >
                        You cannot delete yourself as a super admin.
                    </p>
                </CardContent>
            </Card>
        </div>
    </AppLayout>

    <DeleteConfirmationModal
        :open="showDeleteModal"
        title="Delete User"
        :description="`Are you sure you want to delete the user '${props.user.name}'? This action cannot be undone.`"
        confirm-text="Delete User"
        :processing="processing"
        @confirm="handleDelete"
        @cancel="handleCancel"
    />
</template>