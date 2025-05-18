<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import second from '@/components/Can.vue';
</script>

<template>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
        <div v-if="can('read articles')">
            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm
            font-medium text-white shadow-sm hover:bg-indigo-700">
                read article
            </button>  
        </div>
        <div class="mt-4">
            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm
            font-medium text-white shadow-sm hover:bg-blue-700">
                write article
            </button>
        </div>
        <div class="mt-4">
            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-orange-600 px-4 py-2 text-sm
            font-medium text-white shadow-sm hover:bg-orange-700">
                edit article
            </button>
        </div>
        <div class="mt-4">
            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm
            font-medium text-white shadow-sm hover:bg-red-700">
                delete article
            </button>
        </div>
        <!-- <button v-if="can('read articles')">Read Article</button> -->
        <!-- <button v-if="can('read articles')">Read Article</button> -->
        <div>
            {{ $page.props }}
        </div>
    </div>
</template>
