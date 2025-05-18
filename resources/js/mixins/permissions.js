export default {
    methods: {
        can(permission) {
            return this.$page.props.user?.permissions?.includes(permission);
        },
        hasRole(role) {
            return this.$page.props.user?.roles?.includes(role);
        },
    },
};
