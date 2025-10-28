<template>
    <div v-if="hasError" class="error-boundary">
        <v-container>
            <v-row justify="center">
                <v-col cols="12" md="8">
                    <v-card class="text-center pa-8">
                        <v-icon size="64" color="error" class="mb-4">mdi-alert-circle</v-icon>
                        <h2 class="mb-4">{{ $t('error.title') }}</h2>
                        <p class="mb-4">{{ $t('error.message') }}</p>
                        <v-btn color="primary" @click="resetError">
                            {{ $t('error.reload') }}
                        </v-btn>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
    <slot v-else />
</template>

<script setup lang="ts">
import { ref, onErrorCaptured } from 'vue';

const hasError = ref(false);
const error = ref<Error | null>(null);

onErrorCaptured((err: any) => {
    hasError.value = true;
    error.value = err;
    console.error('Error caught by boundary:', err);
    return false;
});

const resetError = () => {
    hasError.value = false;
    error.value = null;
    window.location.reload();
};
</script>

<style scoped>
.error-boundary {
    min-height: 100vh;
    display: flex;
    align-items: center;
}
</style>

