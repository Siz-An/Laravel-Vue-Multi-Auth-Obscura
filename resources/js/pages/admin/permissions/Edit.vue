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

const props = defineProps<{ permission: Permission }>();

const form = ref({
    name: props.permission.name,
    description: props.permission.description || '',
});

const processing = ref(false);

const submit = () => {
    processing.value = true;
    router.put(`/admin/permissions/${props.permission.id}`, form.value, {
        onSuccess: () => {
            processing.value = false;
            toast.success('Permission updated successfully');
        },
        onError: () => {
            processing.value = false;
            toast.error('Failed to update permission');
        },
    });
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Permissions', href: '/admin/permissions' },
    { title: 'Edit Permission', href: `/admin/permissions/${props.permission.id}/edit` },
];
</script>

<template>
    <Head title="Edit Permission" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex-1 space-y-4 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Edit Permission</h2>
                    <p class="text-sm text-muted-foreground">
                        Update the permission details
                    </p>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Permission Details</CardTitle>
                    <CardDescription>
                        Update the details for this permission
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="name">Name</Label>
                            <Input 
                                id="name" 
                                v-model="form.name" 
                                placeholder="Enter permission name" 
                                required 
                            />
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Textarea 
                                id="description" 
                                v-model="form.description" 
                                placeholder="Enter permission description" 
                                rows="3"
                            />
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <Button type="submit" :disabled="processing">
                                {{ processing ? 'Updating...' : 'Update Permission' }}
                            </Button>
                            <Button variant="outline" as-child>
                                <Link :href="'/admin/permissions'">
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