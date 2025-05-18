<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PostsTable from "./PostsTable.vue";
import { ref, reactive } from "vue";
import { ElButton, ElMessage, ElMessageBox } from 'element-plus';
import { router, usePage } from '@inertiajs/vue3';
import debounce from "lodash/debounce"; // For efficient input handling

const props = defineProps({
    data: Array, // Ensure type matches Laravel's response
    errors: Object,
    filter: Array
});


const dialogFormVisible = ref(false);
const formLabelWidth = '190px';
const form = reactive({
    id: null,

    title: null,
    content: null,
    author_id: null,
    is_published: true,
});

const searchQuery = ref("");

function resetForm() {
    form.id = null;
    form.title = null;
    form.content = null;
    form.author_id = null;
    form.is_published = true;
}
function submit(event) {
    event.preventDefault();

    const method = form.id ? 'put' : 'post'; // Determine HTTP method
    const url = form.id ? `/posts/${form.id}` : '/posts'; // Determine URL

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
            router.delete('posts/' + id, {
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
    router.get('posts/' + id, {}, {
        preserveState: true, preserveScroll: true, onSuccess: (result) => {
            const user = result.props.flash.data;
            form.id = user.id;
            form.title = user.title;
            form.content = user.content;
            form.author_id = user.author_id;
            form.is_published = Boolean(user.is_published);
            dialogFormVisible.value = true; // Open dialog
        }
    })
}



</script>

<template>
    <AppLayout title="posts Index">
        <template #header>
            <div dir="rtl" class="flex w-full items-center justify-between">
                <div class="flex items-center justify-between w-full">
                    <h4 dir="rtl"
                        class="font-bold font-Sarchia_Qaisy_bold sm:text-xl text-black dark:text-black pl-1 sm:pl-2">
                        پۆستەکان
                    </h4>
                    <div class="flex items-center border border-primary rounded-sm py-2 sm:pl-32 h-10">
                        <i class="fa fa-search text-primary mx-2"></i>
                        <input v-model="searchQuery" type="text" placeholder="گــــــەڕان ئــــەنــجـــــام بـــدە..."
                            class="outline-none border-none focus:outline-none focus:ring-0 focus:border-transparent sm:text-sm text-xs font-droidkufi rounded-md w-full text-primary bg-transparent placeholder:text-primary" />
                    </div>
                </div>
                <el-button v-if="can('write authors')" style="height: 40px;" @click="dialogFormVisible = true"
                    type="primary" class="font-droidkufi mr-2 object-cover">
                    <i class="fa fa-plus ml-2"></i>
                    تۆمارکردن
                </el-button>
            </div>
        </template>

        <PostsTable :data="data" :destroy="destroy" :edit="edit" :search-query="searchQuery" />
    </AppLayout>

    <el-dialog class="font-droidkufi" dir="rtl" v-model="dialogFormVisible" title="فۆڕمی زیاکردنی پۆستەکان" width="700"
        align-center="true" :close-on-click-modal="false" @close="resetForm">
        <el-form :model="form" @keydown.enter="submit">
            <el-form-item label-position="left" :label-width="formLabelWidth">
                <template #label>
                    <span class="ml-2 text-danger">*</span>تایتڵ
                </template>
                <el-input clearable v-model="form.title" />
            </el-form-item>
            <el-form-item label-position="left" :label-width="formLabelWidth">
                <template #label>
                    <span class="ml-2 text-danger">*</span> دەق
                </template>
                <el-input type="textarea" autosize clearable v-model="form.content" />
            </el-form-item>

            <!-- Category Field -->
            <el-form-item label-position="left" :label-width="formLabelWidth">
                <template #label>
                    <span class="ml-2 text-danger">*</span> هەڵبژاردنی نووسەر
                </template>
                <el-select filterable clearable v-model="form.author_id" placeholder="نووسەرێک هەڵبژێرە"
                    class="rtl-select">
                    <el-option v-for="cat in filter.authors" :key="cat.id" :label="cat.name" :value="cat.id" />
                </el-select>
            </el-form-item>
            <!-- is publisheds -->
            <div class="text-end flex items-center justify-end w-full" style="margin-top: 0px" dir="ltr">
                <el-radio-group v-model="form.is_published">
                    <el-radio-button label="not published" :value="false" />
                    <el-radio-button label="published" :value="true" />
                </el-radio-group>
                <p class="mx-2 text-sm text-end " style="width: 190px;">
                    is published
                    <span class="text-danger ml-1" style="color: red;">*</span>
                </p>
            </div>

        </el-form>

        <template #footer class="w-full">
            <div class="dialog-footer">
                <el-button @click="submit" type="success" plain style="float: left;">
                    زەخیرەکردنی زانییاری پۆست
                </el-button>
            </div>
        </template>
    </el-dialog>

</template>
<style scoped>
.custom-input .el-input__inner {
    border-radius: 0 !important;
    padding: 0 !important;
}

.el-select__wrapper.is-filterable {
    text-align: right !important;
    font-family: "DroidKufi", sans-serif;
    padding: 0 !important;
    border: 0 !important;
    border-radius: 0 !important;
}

/* Remove the border on focus specifically */
.el-select__wrapper.is-filterable:focus {
    border: none !important;
    box-shadow: none !important;
}

.el-form-item__label {
    align-items: center !important;
}
</style>
