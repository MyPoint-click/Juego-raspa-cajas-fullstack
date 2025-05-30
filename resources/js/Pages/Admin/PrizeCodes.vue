<script setup>
import { ref, watch } from "vue";

import { Head, useForm, router, Link, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import debounce from "lodash/debounce";
import Campaigns from "./Campaigns.vue";

const props = defineProps({
    codes: Object,
    filters: Object,
    campaigns: {
        // Cambiado de Campaigns a campaigns
        type: Array,
        required: true,
    },
    message: Object,
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

// Show messages exit and error
const page = usePage();
const showMessage = ref(true);

// Watch para manejar los mensajes flash
watch(
    () => page.props.message,
    (newMessage) => {
        if (newMessage) {
            showMessage.value = true;
            // Ocultar el mensaje después de 5 segundos
            setTimeout(() => {
                showMessage.value = false;
            }, 5000);
        }
    },
    { immediate: true }
);

// Formulario de generación de códigos
const form = useForm({
    campaign_id: "",
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
    campaign_id: "", // opcional, para filtrar por campaña
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
                <!-- Mensajes de éxito y error -->
                <TransitionGroup name="fade">
                    <div
                        v-if="$page.props.message && showMessage"
                        class="mb-4"
                        key="message"
                    >
                        <div
                            :class="{
                                'bg-green-100 border-green-400 text-green-700':
                                    $page.props.message.type === 'success',
                                'bg-red-100 border-red-400 text-red-700':
                                    $page.props.message.type === 'error',
                            }"
                            class="border px-4 py-3 rounded relative"
                            role="alert"
                        >
                            <span class="block sm:inline">{{
                                $page.props.message.text
                            }}</span>
                            <button
                                @click="showMessage = false"
                                class="absolute top-0 bottom-0 right-0 px-4 py-3"
                            >
                                <svg
                                    class="fill-current h-6 w-6"
                                    role="button"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <title>Cerrar</title>
                                    <path
                                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </TransitionGroup>
                <!-- .Mensajes de éxito y error -->

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

                <!-- Formulario de eliminación -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6"
                >
                    <h2 class="text-lg font-medium mb-4 text-gray-900">
                        Eliminación Masiva de Códigos
                    </h2>
                    <form
                        @submit.prevent="bulkDelete"
                        class="space-y-4"
                        novalidate
                    >
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
                                    :class="{
                                        'border-red-500':
                                            deleteForm.errors.quantity,
                                    }"
                                />
                                <div
                                    v-if="deleteForm.errors.quantity"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ deleteForm.errors.quantity }}
                                </div>
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
                                    :class="{
                                        'border-red-500':
                                            deleteForm.errors.status,
                                    }"
                                >
                                    <option value="all">
                                        Todos los estados
                                    </option>
                                    <option value="unused">Sin usar</option>
                                    <option value="viewed">Visualizados</option>
                                    <option value="used">Usados</option>
                                </select>
                                <div
                                    v-if="deleteForm.errors.status"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ deleteForm.errors.status }}
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Campaña
                                </label>
                                <select
                                    v-model="deleteForm.campaign_id"
                                    class="mt-1 block w-full rounded-md border-gray-300"
                                    :class="{
                                        'border-red-500':
                                            deleteForm.errors.campaign_id,
                                    }"
                                >
                                    <option value="">Todas las campañas</option>
                                    <option
                                        v-for="campaign in props.campaigns"
                                        :key="campaign.id"
                                        :value="campaign.id"
                                    >
                                        {{ campaign.name }}
                                    </option>
                                </select>
                                <div
                                    v-if="deleteForm.errors.campaign_id"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ deleteForm.errors.campaign_id }}
                                </div>
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
                                    :class="{
                                        'border-red-500':
                                            deleteForm.errors.date_before,
                                    }"
                                />
                                <div
                                    v-if="deleteForm.errors.date_before"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ deleteForm.errors.date_before }}
                                </div>
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
                    <form
                        @submit.prevent="generateCodes"
                        class="flex gap-4"
                        novalidate
                    >
                        <!-- relacionar con una campaña -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Campaña</label
                            >
                            <select
                                v-model="form.campaign_id"
                                class="mt-1 block w-full rounded-md border-gray-300"
                                :class="{
                                    'border-red-500': form.errors.campaign_id,
                                }"
                                required
                            >
                                <option value="">Seleccione una campaña</option>
                                <option
                                    v-for="campaign in props.campaigns"
                                    :key="campaign.id"
                                    :value="campaign.id"
                                >
                                    {{ campaign.name }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.campaign_id"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.campaign_id }}
                            </div>
                        </div>
                        <!-- .relacionar con una campaña -->
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
                                :class="{
                                    'border-red-500': form.errors.quantity,
                                }"
                            />
                            <div
                                v-if="form.errors.quantity"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.quantity }}
                            </div>
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
                                :class="{
                                    'border-red-500': form.errors.expires_at,
                                }"
                            />
                            <div
                                v-if="form.errors.expires_at"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.expires_at }}
                            </div>
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

                <!-- Agregar el switch -->
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
                                    Campaña
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
                                            'bg-green-500 text-white shadow-md hover:bg-green-600':
                                                code.status === 'unused',
                                            'bg-yellow-500 text-white shadow-md hover:bg-yellow-600':
                                                code.status === 'viewed',
                                            'bg-red-500 text-white shadow-md hover:bg-red-600':
                                                code.status === 'used',
                                        }"
                                        class="px-3 py-1.5 rounded-full text-sm font-semibold inline-block min-w-[90px] text-center transition-all duration-200"
                                    >
                                        {{
                                            code.status === "unused"
                                                ? "Sin Usar"
                                                : code.status === "viewed"
                                                ? "Visualizado"
                                                : code.status === "used"
                                                ? "Canjeado"
                                                : code.status
                                        }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        v-if="code.campaign"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                    >
                                        {{ code.campaign.name }}
                                    </span>
                                    <span v-else class="text-gray-400 text-sm">
                                        Sin campaña
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
                                        class="inline-flex items-center px-2.5 py-1.5 text-sm font-medium text-white bg-green-400 border border-gray-300 rounded-md hover:bg-white hover:border-gray-400 hover:text-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                        Verificar
                                    </button>
                                    <button
                                        @click="deletePrizeCode(code.id)"
                                        class="inline-flex items-center px-2.5 py-1.5 text-sm font-medium text-white bg-red-400 border border-gray-300 rounded-md hover:bg-gray-50 hover:border-red-400 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1"
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
