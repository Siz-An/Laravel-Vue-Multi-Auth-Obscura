<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Textarea from '@/components/ui/textarea.vue';

interface Permission {
    id: number;
    name: string;
    description: string | null;
}

const props = defineProps<{ permissions: Permission[] }>();

const form = ref({
    name: '',
    description: '',
    permissions: [] as number[],
});

const processing = ref(false);

const togglePermission = (permissionId: number) => {
    const index = form.value.permissions.indexOf(permissionId);
    if (index === -1) {
        form.value.permissions.push(permissionId);
    } else {
        form.value.permissions.splice(index, 1);
    }
};

const submit = () => {
    processing.value = true;
    router.post('/admin/roles', form.value, {
        onSuccess: () => {
            processing.value = false;
            toast.success('Role created successfully');
        },
        onError: () => {
            processing.value = false;
            toast.error('Failed to create role');
        },
    });
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Roles', href: '/admin/roles' },
    { title: 'Create Role', href: '/admin/roles/create' },
];
</script>

<template>
    <Head title="Create Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Create Role</h2>
                    <p class="text-sm text-muted-foreground">
                        Add a new role to the system
                    </p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Role Details</CardTitle>
                    <CardDescription>
                        Enter the details for the new role
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="name">Name</Label>
                            <Input 
                                id="name" 
                                v-model="form.name" 
                                placeholder="Enter role name" 
                                required 
                            />
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Textarea 
                                id="description" 
                                v-model="form.description" 
                                placeholder="Enter role description" 
                                rows="3"
                            />
                        </div>
                        
                        <div class="space-y-2">
                            <Label>Permissions</Label>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                <div 
                                    v-for="permission in permissions" 
                                    :key="permission.id"
                                    class="flex items-center space-x-2 rounded border p-3"
                                >
                                    <input 
                                        type="checkbox"
                                        :id="`permission-${permission.id}`"
                                        :checked="form.permissions.includes(permission.id)"
                                        @change="togglePermission(permission.id)"
                                    />
                                    <Label :for="`permission-${permission.id}`">{{ permission.name }}</Label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <Button type="submit" :disabled="processing">
                                {{ processing ? 'Creating...' : 'Create Role' }}
                            </Button>
                            <Button variant="outline" as-child>
                                <Link :href="'/admin/roles'">
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