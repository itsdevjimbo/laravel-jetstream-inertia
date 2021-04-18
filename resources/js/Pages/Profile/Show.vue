<template>
    <backend-layout v-if="canSeeBackend">
        <template #header>
            <h2
                :class="canSeeBackend ? '' : 'text-gray-800'"
                class="font-semibold text-xl leading-tight text-white"
            >
                Profile
            </h2>
        </template>
        <show-content :sessions="sessions"></show-content>
    </backend-layout>
    <frontend-layout v-else>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight text-gray-800">
                Profile
            </h2>
        </template>
        <show-content :sessions="sessions"></show-content>
    </frontend-layout>
</template>

<script>
import FrontendLayout from "@/Layouts/AppLayout";
import BackendLayout from "@/Layouts/Backend/Default";
import ShowContent from "./ShowContent";
export default {
    props: ["sessions"],

    components: {
        BackendLayout,
        FrontendLayout,
        ShowContent,
    },
    data: () => ({
        canSeeBackend: false,
    }),
    mounted() {
        this.canSeeBackend = this.$page.props.user.permissions.includes(
            "see-backend"
        );
    },
};
</script>
