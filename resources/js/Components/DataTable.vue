<template>
    <div class="flex flex-row space-x-2 justify-end mb-3">
        <danger-button
            v-if="massDestroy"
            @click="onMassDestroy()"
            :disabled="!selected.length"
        >
            Delete
        </danger-button>
        <inertia-link
            v-if="createUrl"
            :href="route(createUrl)"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
            >Create
        </inertia-link>
    </div>
    <div class="mb-4 flex flex-row mb-3 justify-between items-center">
        <div class="flex flex-row items-center space-x-3">
            <span>Show</span>
            <div class="relative">
                <select
                    v-model="perPage"
                    @change="onPerPageChange()"
                    class="appearance-none h-full rounded border block appearance-none w-full bg-white border-gray-400 text-gray-700 py-2 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                >
                    <option v-for="page in perPages" :key="page" :value="page">
                        {{ page }}
                    </option>
                </select>
            </div>
            <span> Entries </span>
        </div>
        <div class="flex flex-row space-x-3">
            <div class="relative lg:mx-0 flex-full">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg
                        class="h-5 w-5 text-gray-500"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </span>

                <input
                    class="w-32 sm:w-64 rounded pl-10 pr-4 focus:border-none"
                    type="search"
                    placeholder="Search"
                    v-model="term"
                    @keyup="onSearch()"
                    @search="onSearch()"
                />
            </div>
            <menu-dropdown>
                <template #trigger>
                    <button
                        class="flex flex-row items-center bg-blue-100 p-2 text-sm font-semibold tracking-wider text-blue-600 rounded hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                            />
                        </svg>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </button>
                </template>
                <template #content>
                    <div class="p-4 flex flex-col items-start">
                        <label
                            class="cursor-pointer inline-flex items-center my-2"
                            v-for="(header, i) in headers"
                            :key="(header.id ?? '') + i"
                        >
                            <checkbox
                                class="mr-2"
                                v-model:checked="columns[header.id]"
                            ></checkbox>
                            {{ header.name }}
                        </label>
                    </div>
                </template>
            </menu-dropdown>
        </div>
    </div>
    <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
        <table class="min-w-max w-full table-auto">
            <thead>
                <tr
                    class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal"
                >
                    <th class="sticky top-0 py-3 px-6 w-6">
                        <checkbox
                            v-model:checked="selectAll"
                            @update:checked="onSelectAll()"
                        ></checkbox>
                    </th>
                    <template v-for="header in headers" :key="header.id">
                        <th
                            class="sticky top-0 py-3 px-6 text-left"
                            v-if="columns[header.id]"
                            v-bind:style="header.style"
                        >
                            {{ header.name }}
                        </th>
                    </template>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <tr
                    v-for="(d, i) in data"
                    :key="(d.id ?? '') + i"
                    class="border-b border-gray-200 hover:bg-gray-100"
                >
                    <td class="py-3 px-6">
                        <checkbox
                            :checked="selected.includes(d.id)"
                            @update:checked="onSelect($event, d)"
                        ></checkbox>
                    </td>
                    <template v-for="header in headers" :key="header.id">
                        <td
                            class="py-3 px-6 text-left whitespace-nowrap"
                            v-if="columns[header.id]"
                        >
                            <div
                                class="flex items-center"
                                v-if="!header.action"
                            >
                                <span class="font-medium">{{
                                    d[header.id]
                                }}</span>
                            </div>
                            <template v-else>
                                <div class="flex item-center">
                                    <inertia-link
                                        :href="route(editUrl, { id: d.id })"
                                        type="button"
                                        class="w-6 mr-2 transform hover:text-purple-500 hover:scale-110"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                            />
                                        </svg>
                                    </inertia-link>
                                    <button
                                        @click="onDelete(d.id)"
                                        class="w-6 mr-2 transform hover:text-purple-500 hover:scale-110"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </td>
                    </template>
                </tr>
            </tbody>
        </table>
        <div class="py-6 text-center w-full" v-if="data.length == 0">
            No data
        </div>
        <confirmation-modal :show="beingDeleted" @close="beingDeleted = false">
            <template #title>
                {{ `Delete ${model}${deleteType == "multiple" ? "s" : ""}` }}
            </template>

            <template #content>
                {{
                    `Are you sure you want to delete ${
                        deleteType == "multiple" ? "these" : "this"
                    } ${model}${deleteType == "multiple" ? "s" : ""}?`
                }}
            </template>

            <template #footer>
                <secondary-button @click="beingDeleted = false">
                    Cancel
                </secondary-button>

                <danger-button class="ml-2" @click="onConfirmDelete">
                    Delete
                </danger-button>
            </template>
        </confirmation-modal>
    </div>
</template>
<script>
import _ from "lodash";
import MenuDropdown from "./MenuDropown";
import Checkbox from "./Checkbox.vue";
import ConfirmationModal from "@/Jetstream/ConfirmationModal";
import SecondaryButton from "@/Jetstream/SecondaryButton";
import DangerButton from "@/Jetstream/DangerButton";
export default {
    components: {
        MenuDropdown,
        Checkbox,
        ConfirmationModal,
        SecondaryButton,
        DangerButton,
    },
    props: {
        headers: {
            type: Array,
        },
        data: {
            type: Array,
        },
        createUrl: {
            type: String,
            default: null,
        },
        editUrl: {
            type: String,
            default: null,
        },
        selectable: {
            type: Boolean,
            default: false,
        },
        massDestroy: {
            type: Boolean,
            default: false,
        },
        model: {
            type: String,
        },
        perPages: {
            type: Array,
            default: [10, 15, 30],
        },
    },
    emits: ["onSearch", "onDelete", "onMassDestroy", "onPerPageChanged"],
    data: () => ({
        term: "",
        columns: {},
        allColumns: [],
        selected: [],
        beingDeleted: null,
        deleteType: "single",
        deleteId: "",
        perPage: 10,
    }),
    computed: {
        selectAll: {
            get: function () {
                return this.data.length > 0
                    ? this.selected.length == this.data.length
                    : false;
            },
        },
    },
    mounted() {
        this.headers.forEach((element) => {
            this.columns[element.id] = true;
        });
        this.perPage = route().params.perPage ?? this.perPages[0];
        this.term = route().params.term ?? "";
    },
    methods: {
        onSearch: _.throttle(function () {
            this.$emit("onSearch", this.term);
        }, 500),
        onDelete(id) {
            this.deleteId = id;
            this.deleteType = "single";
            this.beingDeleted = true;
        },
        onSelectAll() {
            if (this.selected.length == this.data.length) {
                this.selected = [];
            } else {
                this.selected = this.data.map((d) => d.id);
            }
        },
        onSelect(event, dataSelected) {
            if (!event)
                this.selected = this.selected.filter(
                    (d) => d != dataSelected.id
                );
            else this.selected = [...this.selected, dataSelected.id];
        },
        onMassDestroy() {
            this.deleteType = "multiple";
            if (this.selected.length == 1) {
                this.deleteType = " single";
            }
            this.beingDeleted = true;
        },
        onConfirmDelete() {
            this.beingDeleted = false;
            if (this.deleteType == "single") {
                this.$emit("onDelete", this.deleteId);
            } else {
                this.$emit("onMassDestroy", this.selected);
            }
            this.selected = [];
        },
        onPerPageChange() {
            this.$emit("onPerPageChanged", this.perPage);
        },
    },
};
</script>
