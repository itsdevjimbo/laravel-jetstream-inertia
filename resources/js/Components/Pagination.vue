<template>
    <template v-for="(link, i) in links" :key="i">
        <inertia-link
            :class="{
                'rounded-l-md': i == 0,
                'rounded-r-md': links.length == i + 1,
                'bg-blue-500 text-white': link.active,
            }"
            v-html="link.label"
            class="disabled:opacity-50 relative inline-flex items-center px-4 py-2 bg-white text-sm font-medium"
            :href="link.url ? link.url : ''"
            preserve-scroll
            as="button"
            type="button"
            :disabled="!link.url"
        >
        </inertia-link>
    </template>
</template>

<script>
export default {
    props: {
        links: Array,
    },
};
</script>
