<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Users
            </h2>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <data-table
                    :headers="headers"
                    :data="users.data"
                    :create-url="'users.create'"
                    :edit-url="'users.edit'"
                    @onSearch="onSearch($event)"
                    @onDelete="onDelete($event)"
                ></data-table>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/Backend/Default";
import DataTable from "@/Components/DataTable";
export default {
    props: {
        users: Object,
    },
    components: {
        AppLayout,
        DataTable,
    },
    data: () => ({
        headers: [
            {
                id: "id",
                name: "ID",
            },
            {
                id: "name",
                name: "Name",
            },
            {
                id: "email",
                name: "Email",
            },
            {
                id: "action",
                name: "Action",
                action: true,
            },
        ],
    }),
    mounted() {
        console.log(this.users);
    },
    methods: {
        onSearch(term) {
            this.$inertia.replace(route("users.index", { term }));
        },
        onDelete(id) {
            this.$inertia.delete(route("users.destroy", { id }));
        },
    },
};
</script>
