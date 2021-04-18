<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Roles
            </h2>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <data-table
                    :headers="headers"
                    :data="data.data"
                    :create-url="'roles.create'"
                    :edit-url="'roles.edit'"
                    :selectable="true"
                    :mass-destroy="true"
                    model="Role"
                    @onMassDestroy="onMassDestroy($event)"
                    @onSearch="onSearch($event)"
                    @onDelete="onDelete($event)"
                    @onPerPageChanged="onPerPageChanged($event)"
                ></data-table>
                <div class="mt-3 flex flex-row justify-center">
                    <div class="shadow">
                        <pagination :links="data.links"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/Backend/Default";
import DataTable from "@/Components/DataTable";
import Pagination from "../../Components/Pagination.vue";
export default {
    props: {
        data: Object,
    },
    components: {
        AppLayout,
        DataTable,
        Pagination,
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
                id: "action",
                name: "Action",
                action: true,
                style: {
                    width: "100px",
                },
            },
        ],
    }),
    methods: {
        onSearch(term) {
            this.$inertia.replace(
                route("roles.index", { ...route().params, term })
            );
        },
        onDelete(id) {
            this.$inertia.delete(route("roles.destroy", { id }));
        },
        onMassDestroy(ids) {
            this.$inertia.post(route("roles.massDestroy", { ids }));
        },
        onPerPageChanged(perPage) {
            this.$inertia.replace(
                route("roles.index", { ...route().params, perPage })
            );
        },
    },
};
</script>
