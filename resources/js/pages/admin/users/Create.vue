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
import Textarea from '@/components/ui/textarea.vue';

interface Role {
    id: number;
    name: string;
    description: string | null;
}

const props = defineProps<{ roles: Role[] }>();

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    is_super_admin: false,
    roles: [] as number[],
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
    router.post('/admin/users', form.value, {
        onSuccess: () => {
            processing.value = false;
            toast.success('User created successfully');
        },
        onError: () => {
            processing.value = false;
            toast.error('Failed to create user');
        },
    });
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Users', href: '/admin/users' },
    { title: 'Create User', href: '/admin/users/create' },
];
</script>

<template>
    <Head title="Create User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Create User</h2>
                    <p class="text-sm text-muted-foreground">
                        Add a new user to the system
                    </p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>User Details</CardTitle>
                    <CardDescription>
                        Enter the details for the new user
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
                                placeholder="Enter password" 
                                required 
                            />
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="password_confirmation">Confirm Password</Label>
                            <Input 
                                id="password_confirmation" 
                                v-model="form.password_confirmation" 
                                type="password"
                                placeholder="Confirm password" 
                                required 
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
                                {{ processing ? 'Creating...' : 'Create User' }}
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