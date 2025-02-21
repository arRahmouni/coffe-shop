<template>
    <div v-if="errors && errors[field] && errors[field].length" class="invalid-feedback">
        {{ errors[field][0] }}
    </div>
</template>

<script setup>
    defineProps({
        errors: {
            type: Object,
            required: true,
        },
        field: {
            type: String,
            required: true,
        },
    });
</script>

<style scoped>
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
