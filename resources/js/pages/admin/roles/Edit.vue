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

interface Role {
    id: number;
    name: string;
    description: string | null;
}

const props = defineProps<{ role: Role }>();

const form = ref({
    name: props.role.name,
    description: props.role.description || '',
});

const processing = ref(false);

const submit = () => {
    processing.value = true;
    router.put(`/admin/roles/${props.role.id}`, form.value, {
        onSuccess: () => {
            processing.value = false;
            toast.success('Role updated successfully');
        },
        onError: () => {
            processing.value = false;
            toast.error('Failed to update role');
        },
    });
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Roles', href: '/admin/roles' },
    { title: 'Edit Role', href: `/admin/roles/${props.role.id}/edit` },
];
</script>

<template>
    <Head title="Edit Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Edit Role</h2>
                    <p class="text-sm text-muted-foreground">
                        Update the role details
                    </p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Role Details</CardTitle>
                    <CardDescription>
                        Update the details for this role
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
                        
                        <div class="flex items-center gap-2">
                            <Button type="submit" :disabled="processing">
                                {{ processing ? 'Updating...' : 'Update Role' }}
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