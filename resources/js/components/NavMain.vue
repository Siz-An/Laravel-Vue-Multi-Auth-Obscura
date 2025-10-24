<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    items: NavItem[];
}>();

const page = usePage();

const openItems = ref<Record<string, boolean>>({});

const toggleItem = (title: string) => {
    openItems.value[title] = !openItems.value[title];
};

const isItemOpen = (title: string) => {
    return openItems.value[title] || false;
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <template v-for="item in items" :key="item.title">
                <SidebarMenuItem v-if="!item.children">
                    <SidebarMenuButton
                        as-child
                        :is-active="urlIsActive(item.href, page.url)"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component v-if="item.icon" :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
                
                <SidebarMenuItem v-else>
                    <SidebarMenuButton
                        as-child
                        :is-active="item.children?.some(child => urlIsActive(child.href, page.url))"
                        :tooltip="item.title"
                        @click="toggleItem(item.title)"
                    >
                        <div>
                            <component v-if="item.icon" :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </div>
                    </SidebarMenuButton>
                    
                    <SidebarMenuSub v-show="isItemOpen(item.title) || item.children?.some(child => urlIsActive(child.href, page.url))">
                        <SidebarMenuSubItem v-for="child in item.children" :key="child.title">
                            <SidebarMenuSubButton
                                as-child
                                :is-active="urlIsActive(child.href, page.url)"
                            >
                                <Link :href="child.href">
                                    <span>{{ child.title }}</span>
                                </Link>
                            </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                    </SidebarMenuSub>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>