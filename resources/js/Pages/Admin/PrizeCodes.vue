<script setup>
import { ref } from "vue";

import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    codes: Object,
});

const form = useForm({
    quantity: 1,
    expires_at: "",
});

const generateCodes = () => {
    form.post(route("admin.prize-codes.store"), {
        onSuccess: () => form.reset(),
    });
};

const deletePrizeCode = (id) => {
    if (confirm("¿Estás seguro de eliminar este código?")) {
        form.delete(route("admin.prize-codes.destroy", id));
    }
};
</script>

<template>
    <Head title="Gestión de Códigos" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Formulario de generación -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6"
                >
                    <h2 class="text-lg font-medium mb-4">
                        Generar Nuevos Códigos
                    </h2>
                    <form @submit.prevent="generateCodes" class="flex gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Cantidad</label
                            >
                            <input
                                type="number"
                                v-model="form.quantity"
                                min="1"
                                max="100"
                                class="mt-1 block w-24 rounded-md border-gray-300"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Fecha de expiración</label
                            >
                            <input
                                type="date"
                                v-model="form.expires_at"
                                class="mt-1 block rounded-md border-gray-300"
                            />
                        </div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="mt-6 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                        >
                            Generar Códigos
                        </button>
                    </form>
                </div>

                <!-- Tabla de códigos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Código
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Estado
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Expira
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="code in codes.data" :key="code.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ code.code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="{
                                            'bg-green-100 text-green-800':
                                                code.status === 'unused',
                                            'bg-yellow-100 text-yellow-800':
                                                code.status === 'viewed',
                                            'bg-red-100 text-red-800':
                                                code.status === 'redeemed',
                                        }"
                                        class="px-2 py-1 rounded-full text-xs font-medium"
                                    >
                                        {{ code.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ code.expires_at ?? "No expira" }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button
                                        @click="deletePrizeCode(code.id)"
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
    </AuthenticatedLayout>
</template>
