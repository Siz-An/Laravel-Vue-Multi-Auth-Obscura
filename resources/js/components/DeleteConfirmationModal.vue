<script setup lang="ts">
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';

const props = defineProps<{
    open: boolean;
    title?: string;
    description?: string;
    confirmText?: string;
    cancelText?: string;
    processing?: boolean;
}>();

const emit = defineEmits<{
    (e: 'confirm'): void;
    (e: 'cancel'): void;
}>();

const title = props.title || 'Confirm Deletion';
const description = props.description || 'Are you sure you want to delete this item? This action cannot be undone.';
const confirmText = props.confirmText || 'Delete';
const cancelText = props.cancelText || 'Cancel';
</script>

<template>
    <Dialog :open="open">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>
                    {{ description }}
                </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <Button variant="outline" @click="emit('cancel')" :disabled="processing">
                    {{ cancelText }}
                </Button>
                <Button variant="destructive" @click="emit('confirm')" :disabled="processing">
                    {{ processing ? 'Deleting...' : confirmText }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>