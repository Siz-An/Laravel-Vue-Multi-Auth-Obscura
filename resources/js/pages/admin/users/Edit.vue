<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';

interface Role {
    id: number;
    name: string;
    description: string | null;
}

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

const props = defineProps<{ user: User; roles: Role[] }>();

const form = ref({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    is_super_admin: props.user.is_super_admin,
    roles: props.user.roles.map(role => role.id),
});

const processing = ref(false);

const toggleRole = (roleId: number) => {
    const index = form.value.roles.indexOf(roleId);
    if (index === -1) {
        form.value.roles.push(roleId);
    } else {
        form.value.roles.splice(index, 1);
    }
};

const submit = () => {
    processing.value = true;
    router.put(`/admin/users/${props.user.id}`, form.value, {
        onSuccess: () => {
            processing.value = false;
            toast.success('User updated successfully');
        },
        onError: () => {
            processing.value = false;
            toast.error('Failed to update user');
        },
    });
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Users', href: '/admin/users' },
    { title: 'Edit User', href: `/admin/users/${props.user.id}/edit` },
];
</script>

<template>
    <Head title="Edit User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Edit User</h2>
                    <p class="text-sm text-muted-foreground">
                        Update the user details
                    </p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>User Details</CardTitle>
                    <CardDescription>
                        Update the details for this user
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="name">Name</Label>
                            <Input 
                                id="name" 
                                v-model="form.name" 
                                placeholder="Enter user name" 
                                required 
                            />
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="email">Email</Label>
                            <Input 
                                id="email" 
                                v-model="form.email" 
                                type="email"
                                placeholder="Enter user email" 
                                required 
                            />
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="password">Password</Label>
                            <Input 
                                id="password" 
                                v-model="form.password" 
                                type="password"
                                placeholder="Enter new password (leave blank to keep current)" 
                            />
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="password_confirmation">Confirm Password</Label>
                            <Input 
                                id="password_confirmation" 
                                v-model="form.password_confirmation" 
                                type="password"
                                placeholder="Confirm new password" 
                            />
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <Checkbox 
                                    id="is_super_admin" 
                                    v-model:checked="form.is_super_admin"
                                />
                                <Label for="is_super_admin">Super Admin</Label>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                Super admins have access to all features and can manage other admins.
                            </p>
                        </div>
                        
                        <div class="space-y-2">
                            <Label>Roles</Label>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                <div 
                                    v-for="role in roles" 
                                    :key="role.id"
                                    class="flex items-center space-x-2 rounded border p-3"
                                >
                                    <Checkbox 
                                        :id="`role-${role.id}`"
                                        :checked="form.roles.includes(role.id)"
                                        @update:checked="toggleRole(role.id)"
                                    />
                                    <Label :for="`role-${role.id}`">{{ role.name }}</Label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <Button type="submit" :disabled="processing">
                                {{ processing ? 'Updating...' : 'Update User' }}
                            </Button>
                            <Button variant="outline" as-child>
                                <Link :href="'/admin/users'">
                                    Cancel
                                </Link>
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>