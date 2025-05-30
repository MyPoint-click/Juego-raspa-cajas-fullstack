<script setup>
import { ref, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import debounce from "lodash/debounce";

const props = defineProps({
    campaigns: Object,
    filters: Object,
});

// Búsqueda
const search = ref(props.filters.search);

watch(
    search,
    debounce((value) => {
        router.get(
            route("admin.campaigns.index"),
            { search: value },
            { preserveState: true, preserveScroll: true }
        );
    }, 300)
);

// Form para crear/editar campaña
const form = useForm({
    id: null,
    name: "",
    description: "",
    is_active: true,
    starts_at: "",
    ends_at: "",
});

const showModal = ref(false);
const isEditing = ref(false);

const openCreateModal = () => {
    form.reset();
    isEditing.value = false;
    showModal.value = true;
};

const openEditModal = (campaign) => {
    form.id = campaign.id;
    form.name = campaign.name;
    form.description = campaign.description;
    form.is_active = campaign.is_active;
    form.starts_at = campaign.starts_at;
    form.ends_at = campaign.ends_at;
    isEditing.value = true;
    showModal.value = true;
};

const submit = () => {
    if (isEditing.value) {
        form.put(route("admin.campaigns.update", form.id), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    } else {
        form.post(route("admin.campaigns.store"), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
};

const deleteCampaign = (id) => {
    if (confirm("¿Estás seguro de eliminar esta campaña?")) {
        router.delete(route("admin.campaigns.destroy", id));
    }
};
</script>

<template>
    <Head title="Gestión de Campañas" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Barra superior -->
                <div class="mb-6 flex justify-between items-center">
                    <input
                        type="text"
                        v-model="search"
                        class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="Buscar campaña..."
                    />
                    <button
                        @click="openCreateModal"
                        class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700"
                    >
                        Nueva Campaña
                    </button>
                </div>

                <!-- Tabla de campañas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Nombre
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Estado
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Fecha Inicio
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Fecha Fin
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Códigos
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="campaign in campaigns.data"
                                :key="campaign.id"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ campaign.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="{
                                            'bg-green-100 text-green-800':
                                                campaign.is_active,
                                            'bg-gray-100 text-gray-800':
                                                !campaign.is_active,
                                        }"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    >
                                        {{
                                            campaign.is_active
                                                ? "Activa"
                                                : "Inactiva"
                                        }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ campaign.starts_at || "No definida" }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ campaign.ends_at || "No definida" }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ campaign.codes_count }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap space-x-2"
                                >
                                    <button
                                        @click="openEditModal(campaign)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        @click="deleteCampaign(campaign.id)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal para crear/editar -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center"
        >
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium mb-4">
                    {{ isEditing ? "Editar Campaña" : "Nueva Campaña" }}
                </h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Campos del formulario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Nombre
                        </label>
                        <input
                            type="text"
                            v-model="form.name"
                            class="mt-1 block w-full rounded-md border-gray-300"
                        />
                        <div
                            v-if="form.errors.name"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ form.errors.name }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Descripción
                        </label>
                        <textarea
                            v-model="form.description"
                            class="mt-1 block w-full rounded-md border-gray-300"
                        />
                        <div
                            v-if="form.errors.name"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ form.errors.description }}
                        </div>
                    </div>
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            v-model="form.is_active"
                            class="rounded border-gray-300 text-primary-600"
                        />
                        <label class="ml-2 block text-sm text-gray-900">
                            Activa
                        </label>
                        <div
                            v-if="form.errors.name"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ form.errors.is_active }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Fecha de inicio
                        </label>
                        <input
                            type="date"
                            v-model="form.starts_at"
                            class="mt-1 block w-full rounded-md border-gray-300"
                        />
                        <div
                            v-if="form.errors.name"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ form.errors.starts_at }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Fecha de fin
                        </label>
                        <input
                            type="date"
                            v-model="form.ends_at"
                            class="mt-1 block w-full rounded-md border-gray-300"
                        />
                        <div
                            v-if="form.errors.name"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ form.errors.ends_at }}
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2 border rounded-md"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700"
                        >
                            {{ isEditing ? "Actualizar" : "Crear" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
