<template>
    <div class="mb-4 flex flex-row mb-4 justify-between items-center">
        <div class="relative lg:mx-0">
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
                class="w-32 sm:w-64 rounded-md pl-10 pr-4 focus:border-none"
                type="search"
                placeholder="Search"
                v-model="term"
                @keyup="onSearch()"
            />
        </div>
        <inertia-link
            v-if="createUrl"
            :href="route(createUrl)"
            class="bg-blue-100 px-4 py-2 text-sm font-semibold tracking-wider text-blue-600 rounded hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            Create
        </inertia-link>
    </div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <table class="min-w-max w-full table-auto">
            <thead>
                <tr
                    class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal"
                >
                    <th
                        class="py-3 px-6 text-left"
                        v-for="header in headers"
                        :key="header.id"
                    >
                        {{ header.name }}
                    </th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <tr
                    v-for="(d, i) in data"
                    :key="(d.id ?? '') + i"
                    class="border-b border-gray-200 hover:bg-gray-100"
                >
                    <td
                        class="py-3 px-6 text-left whitespace-nowrap"
                        v-for="header in headers"
                        :key="header.id"
                    >
                        <div class="flex items-center" v-if="!header.action">
                            <span class="font-medium">{{ d[header.id] }}</span>
                        </div>
                        <template v-else>
                            <div class="flex item-center">
                                <button
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
                                </button>
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
                </tr>
            </tbody>
        </table>
        <div class="py-6 text-center w-full" v-if="data.length == 0">
            No data
        </div>
    </div>
</template>
<script>
import _ from "lodash";
export default {
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
    },
    emits: ["onSearch", "onDelete"],
    data: () => ({
        term: "",
    }),
    methods: {
        onSearch: _.throttle(function () {
            this.$emit("onSearch", this.term);
        }, 500),
        onDelete(id) {
            this.$emit("onDelete", id);
        },
    },
};
</script>
