<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { reactive, computed } from "vue";
import { router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

// Pre-fill form if editing
// Reactive form data
const form = reactive({
    id: null,
    name: null,
    permission: []
});

onMounted(() => {
    if (props.role) {
        form.id = props.role.id;
        form.name = props.role.name;
        form.permission = props.role.permissions.map((perm) => perm.name);
    }
});

// Label width for form items
const formLabelWidth = "140px";

// Props for incoming permissions
const props = defineProps({
    permissions: Array,
    role: Object | null
});

// Reactive state for grouping mode
const groupingMode = reactive({
    mode: "resource"
});

// Dynamic grouping logic based on selected mode
const groupedPermissions = computed(() => {
    return props.permissions.reduce((acc, perm) => {
        const key = groupingMode.mode === "resource"
            ? perm.name.split(" ")[1] // Group by the resource (e.g., "articles")
            : perm.name.split(" ")[0]; // Group by the action (e.g., "read");

        if (!acc[key]) {
            acc[key] = [];
        }
        acc[key].push(perm);
        return acc;
    }, {});
});

// Flatten all permissions into a single array
const allPermissions = computed(() =>
    props.permissions.map((perm) => perm.name)
);

// Check if all permissions are selected globally
const isGlobalSelected = computed(() =>
    allPermissions.value.every((perm) => form.permission.includes(perm))
);

// Check if some permissions are selected globally
const isGlobalIndeterminate = computed(() => {
    const selectedCount = allPermissions.value.filter((perm) =>
        form.permission.includes(perm)
    ).length;
    return selectedCount > 0 && selectedCount < allPermissions.value.length;
});

// Toggle global "Select All" functionality
const toggleGlobalSelectAll = () => {
    if (isGlobalSelected.value) {
        form.permission = [];
    } else {
        form.permission = [...allPermissions.value];
    }
};

// // Submit form function (example implementation)
// const submitForm = () => {
//     router.post('/roles', form, {
//         onSuccess: (success) => {
//             ElMessage({
//                 center: false,
//                 message: success.props.flash?.message || 'Operation completed successfully.',
//                 type: 'success',
//                 customClass: 'custom-confirm-box', // Add a custom class
//                 duration: 5000,
//             });
//         },
//         onError: (errors) => {
//             const errorMessages = Object.values(errors)
//                 .flat()
//                 .join('<br>');

//             ElMessage({
//                 center: false,
//                 message: errorMessages,
//                 type: 'error',
//                 dangerouslyUseHTMLString: true,
//                 // customClass: 'custom-confirm-box', // Add a custom class
//                 duration: 5000,
//             });
//         }
//     });
// };

function submitForm() {
    const method = form.id ? 'put' : 'post'; // Determine HTTP method
    const url = form.id ? `/roles/${form.id}` : '/roles'; // Determine URL

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
                // customClass: 'custom-confirm-box', // Add a custom class
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
        },
    });
}

const goback = () => {
    history.back();
}
</script>

<template>
    <AppLayout title="Roles Index">
        <template #header>
            <div dir="rtl" class="flex w-full items-center justify-between">
                <div class="flex items-center justify-between w-full">
                    <h4 dir="rtl"
                        class="font-bold font-Sarchia_Qaisy_bold sm:text-xl text-black dark:text-black pl-1 sm:pl-2">
                        دروستکردنی ڕۆڵەکان
                    </h4>
                </div>
            </div>
        </template>
        <div className="rounded-sm font-droidkufi border border-stroke bg-white px-5 pt-6 m-5 pb-2.5 
    shadow-default dark:border-strokedark dark:bg-boxdark-2 sm:px-7.5 xl:pb-1">
            <div className="w-full mb-4">
                <div className="w-full" dir="ltr">
                    <el-form ref="ruleFormRef" :model="form" label-width="auto" class="demo-ruleForm font-droidkufi"
                        style="max-width: 100%">
                        <!-- Name Input -->
                        <el-form-item dir="rtl" label-position="left" label="ناوی بەکارهێنەر"
                            :label-width="formLabelWidth">
                            <el-input clearable v-model="form.name" />
                        </el-form-item>

                        <!-- Global Select All -->
                        <div class="mb-4">
                            <el-checkbox class="text-sm font-NRT" :indeterminate="isGlobalIndeterminate"
                                :checked="isGlobalSelected" @change="toggleGlobalSelectAll">
                                Select All Permissions
                            </el-checkbox>
                        </div>

                        <!-- Permissions Grouped by Resource -->
                        <div v-for="(permissions, resource) in groupedPermissions" :key="resource">
                            <!-- Resource Title -->
                            <p class="font-NRT text-primary">{{ resource }}</p>

                            <!-- Permissions List -->
                            <div class="flex flex-wrap font-NRT">
                                <el-checkbox-group v-model="form.permission">
                                    <el-checkbox class="w-32" v-for="permission in permissions" :key="permission.id"
                                        :label="permission.name">
                                        {{ permission.name }}
                                    </el-checkbox>
                                </el-checkbox-group>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <el-form-item class="dialog-footer w-full mt-6">
                            <div class="flex justify-start w-full">
                                <el-button type="success" @click="submitForm">زەخیرەکردن</el-button>
                                <el-button @click="goback">پاشگەزبوونەوە</el-button>
                            </div>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
        </div>

    </AppLayout>

</template>