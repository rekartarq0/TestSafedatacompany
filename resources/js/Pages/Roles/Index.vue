<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import RoleTable from "./RoleTable.vue";
import { ref, reactive, watch } from "vue";
import { ElButton, ElMessage, ElMessageBox } from 'element-plus';
import { router, usePage } from '@inertiajs/vue3';
import debounce from "lodash/debounce"; // For efficient input handling

const props = defineProps({
    data: [],
    roles: [],
    errors: Object,
});

const dialogFormVisible = ref(false);
const formLabelWidth = '190px';
const form = reactive({
    id: null,
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
    roles: null,
});

const searchQuery = ref("");

function resetForm() {
    form.id = null;
    form.name = null;
    form.email = null;
    form.password = null;
    form.password_confirmation = null;
    form.roles = null;
}
function submit() {
    const method = form.id ? 'put' : 'post'; // Determine HTTP method
    const url = form.id ? `/users/${form.id}` : '/users'; // Determine URL

    router[method](url, form, {
        onError: (errors) => {
            const errorMessages = Object.values(errors)
                .flat()
                .join('<br>');

            ElMessage({
                center: false,
                message: errorMessages,
                type: 'error',
                dangerouslyUseHTMLString: true,
                customClass: 'custom-confirm-box', // Add a custom class
                duration: 5000,
            });
        },
        onSuccess: (success) => {
            ElMessage({
                center: false,
                message: success.props.flash?.message || 'Operation completed successfully.',
                type: 'success',
                customClass: 'custom-confirm-box', // Add a custom class
                duration: 5000,
            });
            resetForm();
            dialogFormVisible.value = false;
        },
    });
}

function destroy(id) {
    ElMessageBox.confirm(
        'ئایە دڵنییایت لەسڕینەوەی ئەم داتاییە',
        'سڕینەوە',
        {
            confirmButtonText: 'بەڵێ',
            cancelButtonText: 'نەخێر',
            type: 'info',
            customClass: 'custom-confirm-box', // Add a custom class
        }
    )
        .then(() => {
            router.delete('roles/' + id, {
                onError: (errors) => {
                    const errorMessages = Object.values(errors)
                        .flat()
                        .join('<br>');

                    ElMessage({
                        customClass: 'custom-confirm-box', // Add a custom class
                        type: 'info',
                        message: errorMessages,
                    })
                },
                onSuccess: (success) => {
                    ElMessage({
                        type: 'success',
                        message: success.props.flash?.message,
                        customClass: 'custom-confirm-box', // Add a custom class

                    })
                }
            })

        })
        .catch(() => {
            ElMessage({
                customClass: 'custom-confirm-box', // Add a custom class
                type: 'info',
                message: 'پاشگەزبوونەوە',
            })
        })
}// Fetch user data for editing

function edit(id) {
    router.get('roles/' + id);
}



// Search Handling with Debounce
const handleSearch = debounce(() => {
    router.get('/users', { q: searchQuery.value }, {
        preserveState: true, preserveScroll: true,
    });
}, 300); // Delay of 300ms

const RoutetoRoleCreateOrUpdate = () => {
    router.get('roles/create');
}

</script>

<template>
    <AppLayout title="Roles Index">
        <template #header>
            <div dir="rtl" class="flex w-full items-center justify-between">
                <div class="flex items-center justify-between w-full">
                    <h4 dir="rtl"
                        class="font-bold font-Sarchia_Qaisy_bold sm:text-xl text-black dark:text-black pl-1 sm:pl-2">
                        ڕۆڵەکان
                    </h4>
                    <div class="flex items-center border border-primary rounded-sm py-2 sm:pl-32 h-10">
                        <i class="fa fa-search text-primary mx-2"></i>
                        <input v-model="searchQuery" @input="handleSearch" type="text"
                            placeholder="گــــــەڕان ئــــەنــجـــــام بـــدە..."
                            class="outline-none border-none focus:outline-none focus:ring-0 focus:border-transparent sm:text-sm text-xs font-droidkufi rounded-md w-full text-primary bg-transparent placeholder:text-primary" />
                    </div>
                </div>
                <el-button v-if="can('write roles')" style="height: 40px;" @click="RoutetoRoleCreateOrUpdate"
                    type="primary" class="font-droidkufi mr-2 object-cover">
                    <i class="fa fa-plus ml-2"></i>
                    تۆمارکردن
                </el-button>
            </div>
        </template>

        <RoleTable :data="data" :destroy="destroy" :edit="edit" />

    </AppLayout>

</template>
<style scoped>
.custom-input .el-input__inner {
    border-radius: 0 !important;
    padding: 0 !important;
}
</style>