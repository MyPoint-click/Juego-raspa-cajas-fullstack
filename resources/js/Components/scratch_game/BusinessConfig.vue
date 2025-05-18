<script setup>
import { ref } from "vue";

const props = defineProps({
    config: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["update", "close"]);

// Create local copy of config for editing
const localConfig = ref({ ...props.config });

// Business types options
const businessTypes = [
    { value: "tienda", label: "Tienda" },
    { value: "consultorio", label: "Consultorio Dental" },
    { value: "restaurante", label: "Restaurante" },
    { value: "salon", label: "Salón de Belleza" },
    { value: "gimnasio", label: "Gimnasio" },
    { value: "otro", label: "Otro" },
];

// Save changes
const saveChanges = () => {
    emit("update", localConfig.value);
};

// Close without saving
const close = () => {
    emit("close");
};
</script>

<template>
    <div
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
    >
        <div
            class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
        >
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Configuración del Negocio
                    </h2>
                    <button
                        @click="close"
                        class="text-gray-500 hover:text-gray-700 transition-colors"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="saveChanges" class="space-y-4">
                    <!-- Business name -->
                    <div>
                        <label
                            for="business-name"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Nombre del Negocio
                        </label>
                        <input
                            id="business-name"
                            v-model="localConfig.name"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                            required
                        />
                    </div>

                    <!-- Business type -->
                    <div>
                        <label
                            for="business-type"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Tipo de Negocio
                        </label>
                        <select
                            id="business-type"
                            v-model="localConfig.type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                        >
                            <option
                                v-for="type in businessTypes"
                                :key="type.value"
                                :value="type.value"
                            >
                                {{ type.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Logo URL -->
                    <div>
                        <label
                            for="logo-url"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            URL del Logo
                        </label>
                        <input
                            id="logo-url"
                            v-model="localConfig.logo"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                            placeholder="https://ejemplo.com/logo.png"
                        />
                    </div>

                    <!-- Color settings -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                for="primary-color"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Color Primario
                            </label>
                            <div class="flex items-center">
                                <input
                                    id="primary-color"
                                    v-model="localConfig.primaryColor"
                                    type="color"
                                    class="w-10 h-10 border border-gray-300 rounded mr-2"
                                />
                                <input
                                    v-model="localConfig.primaryColor"
                                    type="text"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                                />
                            </div>
                        </div>

                        <div>
                            <label
                                for="secondary-color"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Color Secundario
                            </label>
                            <div class="flex items-center">
                                <input
                                    id="secondary-color"
                                    v-model="localConfig.secondaryColor"
                                    type="color"
                                    class="w-10 h-10 border border-gray-300 rounded mr-2"
                                />
                                <input
                                    v-model="localConfig.secondaryColor"
                                    type="text"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Instruction text -->
                    <div>
                        <label
                            for="instruction"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Texto de Instrucción
                        </label>
                        <input
                            id="instruction"
                            v-model="localConfig.instruction"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                        />
                    </div>

                    <!-- Footer text -->
                    <div>
                        <label
                            for="footer-text"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Texto del Pie de Página
                        </label>
                        <input
                            id="footer-text"
                            v-model="localConfig.footerText"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                        />
                    </div>

                    <!-- Action buttons -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button
                            type="button"
                            @click="close"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors"
                        >
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
