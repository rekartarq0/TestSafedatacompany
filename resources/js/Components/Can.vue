<template>
    <div v-if="can(permission)">
        <slot />
    </div>
</template>

<script>
export default {
    props: {
        permission: {
            type: String,
            required: true,
        },
    },
    methods: {
        can(permission) {
            return this.$page.props.user?.permissions?.includes(permission);
        },
    },
};
</script>
