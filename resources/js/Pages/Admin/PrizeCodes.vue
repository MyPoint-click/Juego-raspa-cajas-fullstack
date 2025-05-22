<script setup>
import { ref, watch } from "vue";

import { Head, useForm, router, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import debounce from "lodash/debounce";

const props = defineProps({
    codes: Object,
    filters: Object,
});

// Formulario de búsqueda
const search = ref(props.filters.search);

// Función debounced para la búsqueda
watch(
    search,
    debounce((value) => {
        router.get(
            route("admin.prize-codes.index"),
            { search: value },
            { preserveState: true, preserveScroll: true }
        );
    }, 100)
);

// Formulario de generación de códigos
const form = useForm({
    quantity: 1,
    expires_at: "",
});

// Función para generar códigos
const generateCodes = () => {
    form.post(route("admin.prize-codes.store"), {
        onSuccess: () => form.reset(),
    });
};

// Función para eliminar un código
const deletePrizeCode = (id) => {
    if (confirm("¿Estás seguro de eliminar este código?")) {
        form.delete(route("admin.prize-codes.destroy", id));
    }
};

// Formulario de verificación de códigos
const verifyForm = useForm({
    code: "",
});

// Agregar ref para el switch de permitir verificación de expirados
const allowExpiredVerification = ref(false);

const verifyCode = (codeToVerify = null) => {
    // Si se pasa un código, lo asignamos al formulario
    if (codeToVerify) {
        verifyForm.code = codeToVerify;
    }
    verifyForm.post(
        route("admin.prize-codes.verify", {
            allow_expired: allowExpiredVerification.value,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                verifyForm.reset();
            },
        }
    );
};

// Formulario de eliminación masiva
const deleteForm = useForm({
    quantity: 1,
    status: "all", // 'all', 'unused', 'viewed', 'used'
    date_before: "", // opcional, para filtrar por fecha
});

const bulkDelete = () => {
    if (!confirm("¿Estás seguro de realizar esta eliminación?")) return;

    deleteForm.post(route("admin.prize-codes.bulk-delete"), {
        preserveScroll: true,
        onSuccess: () => deleteForm.reset(),
    });
};
</script>

<template>
    <Head title="Gestión de Códigos" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Barra de búsqueda -->
                <div class="mb-6">
                    <input
                        type="text"
                        v-model="search"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="Buscar por código o estado..."
                    />
                </div>
                <!-- Formulario de verificación -->
                <!-- <div class="mb-6 bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">
                        Verificar Código
                    </h3>
                    <form @submit.prevent="verifyCode" class="flex gap-4">
                        <div class="flex-1">
                            <input
                                type="text"
                                v-model="verifyForm.code"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                placeholder="Ingrese el código a verificar"
                            />
                        </div>
                        <button
                            type="submit"
                            :disabled="verifyForm.processing"
                            class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600"
                        >
                            <span v-if="verifyForm.processing"
                                >Verificando...</span
                            >
                            <span v-else>Verificar Código</span>
                        </button>
                    </form>
                </div> -->

                <!-- Formulario de generación -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6"
                >
                    <h2 class="text-lg font-medium mb-4 text-gray-900">
                        Eliminación Masiva de Códigos
                    </h2>
                    <form @submit.prevent="bulkDelete" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Cantidad a eliminar
                                </label>
                                <input
                                    type="number"
                                    v-model="deleteForm.quantity"
                                    min="1"
                                    class="mt-1 block w-full rounded-md border-gray-300"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Estado de códigos
                                </label>
                                <select
                                    v-model="deleteForm.status"
                                    class="mt-1 block w-full rounded-md border-gray-300"
                                >
                                    <option value="all">
                                        Todos los estados
                                    </option>
                                    <option value="unused">Sin usar</option>
                                    <option value="viewed">Visualizados</option>
                                    <option value="used">Usados</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Fecha de expiración
                                </label>
                                <input
                                    type="date"
                                    v-model="deleteForm.date_before"
                                    class="mt-1 block w-full rounded-md border-gray-300"
                                />
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                :disabled="deleteForm.processing"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                            >
                                <span v-if="deleteForm.processing"
                                    >Eliminando...</span
                                >
                                <span v-else>Eliminar Códigos</span>
                            </button>
                        </div>
                    </form>

                    <!-- Generar Códigos -->
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

                <!-- Agregar el switch antes de la tabla -->
                <div class="mb-4 flex items-center">
                    <label
                        class="relative inline-flex items-center cursor-pointer"
                    >
                        <input
                            type="checkbox"
                            v-model="allowExpiredVerification"
                            class="sr-only peer"
                        />
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"
                        ></div>
                        <span class="ml-3 text-sm font-medium text-gray-900">
                            Permitir verificación de códigos expirados
                        </span>
                    </label>
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
                                    Sesion Expira en
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Fecha de expiración
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
                                    {{ code.session_expires_at ?? "No expira" }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ code.expires_at ?? "No expira" }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap space-x-2"
                                >
                                    <!-- Verificar código con condición fecha de expiración pasada -->
                                    <button
                                        v-if="
                                            code.status === 'viewed' &&
                                            (allowExpiredVerification ||
                                                !code.expires_at ||
                                                new Date(
                                                    code.expires_at.replace(
                                                        /(\d{2})\/(\d{2})\/(\d{4})/,
                                                        '$3-$2-$1'
                                                    )
                                                ) >= new Date())
                                        "
                                        @click="verifyCode(code.code)"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        Verificar
                                    </button>
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
                <!-- Paginación -->
                <div
                    class="mt-4 flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6"
                >
                    <div class="flex flex-1 justify-between sm:hidden">
                        <Link
                            v-if="codes.prev_page_url"
                            :href="codes.prev_page_url"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Anterior
                        </Link>
                        <Link
                            v-if="codes.next_page_url"
                            :href="codes.next_page_url"
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Siguiente
                        </Link>
                    </div>
                    <div
                        class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between"
                    >
                        <div>
                            <p class="text-sm text-gray-700">
                                Mostrando
                                <span class="font-medium">{{
                                    codes.from
                                }}</span>
                                a
                                <span class="font-medium">{{ codes.to }}</span>
                                de
                                <span class="font-medium">{{
                                    codes.total
                                }}</span>
                                resultados
                            </p>
                        </div>
                        <div>
                            <nav
                                class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                                aria-label="Pagination"
                            >
                                <!-- Solo renderizar Link si link.url existe -->
                                <template
                                    v-for="(link, key) in codes.links"
                                    :key="key"
                                >
                                    <Link
                                        v-if="link.url"
                                        :href="link.url"
                                        :class="[
                                            link.active
                                                ? 'relative z-10 inline-flex items-center bg-primary-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600'
                                                : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0',
                                        ]"
                                        v-html="link.label"
                                    />
                                    <span
                                        v-else
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 cursor-not-allowed opacity-50"
                                        v-html="link.label"
                                    />
                                </template>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
