<template>
    <div class="skeleton-container animate-pulse">
        <div class="flex items-center space-x-4">
            <div class="rounded-full bg-gray-300 h-12 w-12"></div>
            <div class="flex-1 space-y-2">
                <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                <div class="h-4 bg-gray-300 rounded w-1/2"></div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
// Skeleton loader component
</script>

<style scoped>
.skeleton-container {
    background: linear-gradient(
        90deg,
        #f0f0f0 25%,
        #e0e0e0 50%,
        #f0f0f0 75%
    );
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    to {
        background-position: 200% center;
    }
}
</style>

